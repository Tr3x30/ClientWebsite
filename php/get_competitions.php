<?php
header("Content-Type: application/json");

$year = (int) date("Y");
$apiKey = "1z1rvTlo2S8iXFco9I8et09kOrln30gku4LnAa7gCVTSR5M0YpenZqKaelGzZo1L";

function fail($message, $code = 500)
{
    http_response_code($code);
    echo json_encode([
        "success" => false,
        "error" => $message
    ]);
    exit;
}

function tbaFetch($url, $apiKey)
{
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => [
                "X-TBA-Auth-Key: $apiKey",
                "Accept: application/json"
            ]
        ]
    ];

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        throw new Exception("Request failed");
    }

    // get HTTP status code
    $statusLine = $http_response_header[0] ?? "";
    preg_match('{HTTP/\S+\s(\d{3})}', $statusLine, $match);
    $status = (int) ($match[1] ?? 0);

    if ($status === 404) {
        return null;
    }

    if ($status < 200 || $status >= 300) {
        throw new Exception("TBA request failed with status $status");
    }

    $data = json_decode($response, true);

    if ($data === null) {
        throw new Exception("Invalid JSON returned");
    }

    return $data;
}

function toEpochMs($dateStr, $timezone)
{
    $dt = new DateTime($dateStr . " 08:00:00", new DateTimeZone($timezone));
    return $dt->getTimestamp() * 1000;
}

try {
    $worldsJson = tbaFetch(
        "https://www.thebluealliance.com/api/v3/event/{$year}cmptx",
        $apiKey
    );

    $provsJson = tbaFetch(
        "https://www.thebluealliance.com/api/v3/event/{$year}oncmp",
        $apiKey
    );

    $teamJson = tbaFetch(
        "https://www.thebluealliance.com/api/v3/team/frc9062/events/{$year}/simple",
        $apiKey
    );

    $worlds = null;
    if ($worldsJson) {
        $worlds = [
            "date" => $worldsJson["start_date"],
            "name" => "FRC Worlds",
            "time" => toEpochMs($worldsJson["start_date"], "America/Chicago")
        ];
    }

    $provs = null;
    if ($provsJson) {
        $provs = [
            "date" => $provsJson["start_date"],
            "name" => "Ontario Provincials",
            "time" => toEpochMs($provsJson["start_date"], "America/Toronto")
        ];
    }

    $team = [];
    if (is_array($teamJson)) {
        foreach (array_slice($teamJson, 0, 2) as $event) {
            $team[] = [
                "date" => $event["start_date"],
                "name" => $event["name"],
                "time" => toEpochMs($event["start_date"], "America/Toronto")
            ];
        }
    }

    $current = round(microtime(true) * 1000);

    $result = null;

    if (isset($team[0]) && $team[0]["time"] > $current) {
        $result = $team[0];
    } elseif (isset($team[1]) && $team[1]["time"] > $current) {
        $result = $team[1];
    } elseif ($provs && $provs["time"] > $current) {
        $result = $provs;
    } elseif ($worlds && $worlds["time"] > $current) {
        $result = $worlds;
    }

    echo json_encode($result);

} catch (Throwable $e) {
    fail($e->getMessage());
}