<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.html');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}
?>
<!doctype html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/globalStyle.css">
    <style>
        .profile-container { max-width: 500px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Your Profile</h2>
        <form action="update_profile.php" method="POST">
            <label>Display Name</label>
            <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

            <label>Title (Role)</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($user['title'] ?? ''); ?>">

            <label>Description / Bio</label>
            <textarea name="description"><?php echo htmlspecialchars($user['description'] ?? ''); ?></textarea>

            <hr>
            <h3>Change Password</h3>
            <p style="font-size: 0.8em; color: #666;">Leave blank to keep your current password.</p>
            <label>New Password</label>
            <input type="password" name="new_password">

            <button type="submit">Save Changes</button>
            <a href="../Member.php" style="margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>