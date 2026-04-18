<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    die('Access denied.');
}

$pendingStmt = $pdo->query("SELECT * FROM pending_users ORDER BY request_date ASC");
$pendingUsers = $pendingStmt->fetchAll();

$usersStmt = $pdo->query("SELECT * FROM users ORDER BY created_at ASC");
$users = $usersStmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }

        h1, h2 {
            margin-bottom: 10px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section {
            background: white;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            vertical-align: top;
            text-align: left;
        }

        input, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin: 3px 0;
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        button {
            padding: 8px 12px;
            cursor: pointer;
        }

        img.preview {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div>
            <h1>Admin Panel</h1>
            <p>Logged in as <?php echo htmlspecialchars($_SESSION['display_name'] ?? $_SESSION['username']); ?></p>
        </div>
        <div>
            <a href="../Member.php">View Member Page</a> |
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="section">
        <h2>Pending User Requests</h2>

        <?php if (count($pendingUsers) === 0): ?>
            <p>No pending users.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Display Name</th>
                    <th>Email</th>
                    <th>Requested</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($pendingUsers as $pending): ?>
                    <tr>
                        <td><?php echo (int)$pending['id']; ?></td>
                        <td><?php echo htmlspecialchars($pending['username']); ?></td>
                        <td><?php echo htmlspecialchars($pending['display_name']); ?></td>
                        <td><?php echo htmlspecialchars($pending['email'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($pending['request_date']); ?></td>
                        <td class="actions">
                            <form action="approve_user.php" method="POST" style="display:inline;">
                                <input type="hidden" name="pending_id" value="<?php echo (int)$pending['id']; ?>">
                                <button type="submit">Approve</button>
                            </form>

                            <form action="reject_user.php" method="POST" style="display:inline;">
                                <input type="hidden" name="pending_id" value="<?php echo (int)$pending['id']; ?>">
                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div class="section">
        <h2>Approved Users</h2>

        <?php if (count($users) === 0): ?>
            <p>No approved users found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Profile</th>
                    <th>User Info</th>
                    <th>Edit</th>
                </tr>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <img class="preview" src="../<?php echo htmlspecialchars($user['profile_picture'] ?: 'images/empty_icon.webp'); ?>" alt="Profile Picture">
                        </td>

                        <td>
                            <strong>ID:</strong> <?php echo (int)$user['id']; ?><br>
                            <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
                            <strong>Display Name:</strong> <?php echo htmlspecialchars($user['display_name']); ?><br>
                            <strong>Title:</strong> <?php echo htmlspecialchars($user['title']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?><br>
                            <strong>Admin:</strong> <?php echo (int)$user['is_admin'] === 1 ? 'Yes' : 'No'; ?><br>
                            <strong>Description:</strong><br>
                            <?php echo nl2br(htmlspecialchars($user['description'] ?? '')); ?>
                        </td>

                        <td>
                            <form action="update_user.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo (int)$user['id']; ?>">

                                <label>Username</label>
                                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                                <label>Display Name</label>
                                <input type="text" name="display_name" value="<?php echo htmlspecialchars($user['display_name']); ?>" required>

                                <label>Title</label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($user['title']); ?>">

                                <label>Email</label>
                                <input type="text" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

                                <label>Profile Picture Path</label>
                                <input type="text" name="profile_picture" value="<?php echo htmlspecialchars($user['profile_picture']); ?>">

                                <label>Description</label>
                                <textarea name="description"><?php echo htmlspecialchars($user['description'] ?? ''); ?></textarea>

                                <label>New Password (leave blank to keep current password)</label>
                                <input type="password" name="password">

                                <label>Admin (1=yes, 0=no)</label>
                                <input type="number" min="0" max="1" name="is_admin" value="<?php echo (int)$user['is_admin']; ?>">

                                <label>Active (1=yes, 0=no)</label>
                                <input type="number" min="0" max="1" name="active" value="<?php echo (int)$user['active']; ?>">

                                <button type="submit">Update User</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>