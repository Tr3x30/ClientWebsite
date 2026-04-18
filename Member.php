<?php
session_start();
require_once 'php/connect.php';

$stmt = $pdo->prepare("
    SELECT id, username, display_name, title, description, profile_picture, email, is_admin
    FROM users
    WHERE active = 1 AND is_approved = 1
    ORDER BY is_admin DESC, display_name ASC
");
$stmt->execute();
$members = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Members</title>
    <link rel="stylesheet" href="css/globalStyle.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/stylesheet_members.css">
    <script src="js/header.js"></script>
</head>
<body>
    <div id="header">
        <a href="index.html" id="logo">
            <img src="images/9062_logo.jpg" alt="9062 Logo">
        </a>
        <div id="flexnav">
            <a href="orginal.html">Orginal Intention</a>
            <a href="Robot.html">Robot</a>
            <a href="Member.php">Member</a>
            <a href="Sponsors.html">Sponsors</a>
            <a href="Location.html">Location</a>
        </div>
        <p>"Try More.<br>Try Smarter"</p>
    </div>

    <div id="comment">
        <section class="team">
            <h1>Our Robotics Team</h1>

            <?php if (isset($_SESSION['user_id'])): ?>
                <p>
                    Logged in as <strong><?php echo htmlspecialchars($_SESSION['display_name'] ?? $_SESSION['username']); ?></strong>
                    | <a href="php/profile.php">Edit Profile</a> <?php if (!empty($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                        | <a href="php/admin.php">Admin Panel</a>
                    <?php endif; ?>
                    | <a href="php/logout.php">Logout</a>
                </p>
            <?php endif; ?>

            <div class="team-container">
                <?php foreach ($members as $member): ?>
                    <div class="member-card">
                        <img src="<?php echo htmlspecialchars($member['profile_picture'] ?: 'images/empty_icon.webp'); ?>" alt="<?php echo htmlspecialchars($member['display_name']); ?>">
                        <h2><?php echo htmlspecialchars($member['display_name']); ?></h2>
                        <p class="role"><?php echo htmlspecialchars($member['title']); ?></p>
                        <p><?php echo htmlspecialchars($member['description'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <div id="footer">
        <div class="column1">
            <h3>Social:</h3>

            <a href="https://www.youtube.com/@CriticalCircuits" target="_blank">
                <img src="images/youtube_image.png" alt="YouTube Logo" class="icon"> Youtube
            </a>

            <a href="https://www.thebluealliance.com/team/9062" target="_blank">
                <img src="images/Screenshot 2026-03-11 144539.png" alt="TheBlueAlliance Logo" class="icon">Blue Alliance
            </a>

            <a href="https://www.instagram.com/9062photographer" target="_blank">
                <img src="images/instagram_icon.png" alt="Instagram Logo" class="icon"> Instagram
            </a>
        </div>

        <div class="column1">
            <h3>Connect:</h3>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p><a href="javascript:void(0)" onclick="openModal('loginModal')">Login</a></p>
                <p><a href="javascript:void(0)" onclick="openModal('signupModal')">Sign Up</a></p>
            <?php else: ?>
                <p><a href="php/logout.php">Logout</a></p>
                <?php if (!empty($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1): ?>
                    <p><a href="php/admin.php">Admin Panel</a></p>
                <?php endif; ?>
            <?php endif; ?>
            <p><a href="scouting.html">Scouting</a></p>
        </div>

        <div class="column1">
            <h3>Contact:</h3>
            <p><a href="mailto:chenghongkiu6@gmail.com">Email</a></p>
            <p><a href="https://www.example.com/">Support</a></p>
            <p><a href="feedback.html">Feedback</a></p>
        </div>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('loginModal')">&times;</span>
            <h2>Login</h2>
            <form action="php/login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn-primary">Sign In</button>
            </form>
        </div>
    </div>

    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal('signupModal')">&times;</span>
            <h2>Request Access</h2>
            <p style="font-size: 0.8em; color: #666; margin-bottom: 10px;">Your account will require admin approval.</p>
            <form action="php/sign_up.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Create Password" required>
                <button type="submit" class="btn-primary">Request Account</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
    }
}
</script>
</body>
</html>