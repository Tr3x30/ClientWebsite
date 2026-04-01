<?php
header("Content-Type: application/json");
include './connect.php';
function fail($message)
{
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $message
    ]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    fail("Invalid JSON input.");
}

$allowedMatchTypes = ["qualification", "playoff", "practice"];
$allowedAllianceColours = ["red", "blue"];
$allowedWinners = ["them", "opponent"];
$allowedPaths = ["", "bump", "trench", "both"];
$allowedDisabled = ["no", "yes"];
$allowedCapabilities = ["preload", "cycles", "intake", "feed", "defended", "climb"];

$matchNumber = isset($input["matchNumber"]) ? (int) $input["matchNumber"] : 0;
$matchType = trim($input["matchType"] ?? "");
$allianceColor = trim($input["allianceColor"] ?? "");
$predictWin = trim($input["predictWin"] ?? "");
$actualWin = trim($input["actualWin"] ?? "");
$rankingPoints = isset($input["rankingPoints"]) && $input["rankingPoints"] !== "" ? (int) $input["rankingPoints"] : null;
$opponents = trim($input["opponents"] ?? "");
$details = trim($input["details"] ?? "");

$team = $input["team"] ?? null;
$teamNumber = isset($team["number"]) ? (int) $team["number"] : 0;
$teamPath = trim($team["path"] ?? "");
$teamDisabled = trim($team["disabled"] ?? "");
$teamCapabilities = $team["capabilities"] ?? [];

if ($matchNumber <= 0) {
    fail("Match number is required.");
}

if (!in_array($matchType, $allowedMatchTypes, true)) {
    fail("Invalid match type.");
}

if (!in_array($allianceColor, $allowedAllianceColours, true)) {
    fail("Invalid alliance colour.");
}

if (!in_array($predictWin, $allowedWinners, true)) {
    fail("Invalid predicted winner.");
}

if (!in_array($actualWin, $allowedWinners, true)) {
    fail("Invalid actual winner.");
}

if ($rankingPoints !== null && ($rankingPoints < 0 || $rankingPoints > 6)) {
    fail("Ranking points must be between 0 and 6.");
}

if (!$team || $teamNumber <= 0) {
    fail("Team number is required.");
}

if (!in_array($teamPath, $allowedPaths, true)) {
    fail("Invalid team path.");
}

if (!in_array($teamDisabled, $allowedDisabled, true)) {
    fail("Invalid disabled value.");
}

if (!is_array($teamCapabilities)) {
    fail("Capabilities must be an array.");
}

foreach ($teamCapabilities as $capability) {
    if (!in_array($capability, $allowedCapabilities, true)) {
        fail("Invalid capability: " . $capability);
    }
}

try {
    $stmt = $dbh->prepare("
        INSERT INTO matches (
            match_number,
            match_type,
            alliance_color,
            predicted_winner,
            actual_winner,
            ranking_points,
            opponents,
            details
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $matchNumber,
        $matchType,
        $allianceColor,
        $predictWin,
        $actualWin,
        $rankingPoints,
        $opponents,
        $details
    ]);

    $matchId = $dbh->lastInsertId();

    $stmt = $dbh->prepare("
        INSERT INTO match_teams (
            match_id,
            team_number,
            path,
            disabled,
            capabilities
        ) VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $matchId,
        $teamNumber,
        $teamPath,
        $teamDisabled,
        json_encode($teamCapabilities)
    ]);

    echo json_encode([
        "success" => true,
        "match_id" => $matchId
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}