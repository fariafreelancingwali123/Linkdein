<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// Handle connection requests
if(isset($_GET['connect_id'])){
    $connect_id = mysqli_real_escape_string($conn, $_GET['connect_id']);
    mysqli_query($conn, "INSERT INTO connections (user_id, connection_id, status) VALUES ('$user_id', '$connect_id', 'pending')");
    header('Location: network.php');
    exit();
}

// Handle accepting requests
if(isset($_GET['accept_id'])){
    $accept_id = mysqli_real_escape_string($conn, $_GET['accept_id']);
    mysqli_query($conn, "UPDATE connections SET status='accepted' WHERE user_id='$accept_id' AND connection_id='$user_id'");
    header('Location: network.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Network - LinkedIn Clone</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f3f2ef;
        }

        .navbar {
            background-color: white;
            padding: 0.5rem 2rem;
            box-shadow: 0 0 0.3rem rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }

        .navbar-content {
            max-width: 1128px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: #0a66c2;
            font-size: 1.8rem;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #666666;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #0a66c2;
        }

        .main-content {
            max-width: 1128px;
            margin: 5rem auto 2rem;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 2.5fr;
            gap: 1.5rem;
        }

        .section-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0 0.3rem rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: rgba(0, 0, 0, 0.9);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid #ebebeb;
        }

        .profile-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.8rem;
            transition: background-color 0.3s;
        }

        .profile-card:hover {
            background-color: #f3f2ef;
        }

        .profile-picture {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-name {
            font-weight: 600;
            color: rgba(0, 0, 0, 0.9);
            margin-bottom: 0.3rem;
        }

        .connect-btn {
            background-color: white;
            color: #0a66c2;
            border: 1px solid #0a66c2;
            padding: 0.5rem 1rem;
            border-radius: 1.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .connect-btn:hover {
            background-color: rgba(10, 102, 194, 0.1);
        }

        .accept-btn {
            background-color: #0a66c2;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 1.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .accept-btn:hover {
            background-color: #004182;
        }

        .stats-sidebar {
            position: sticky;
            top: 5.5rem;
        }

        .connection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="index.php" class="logo">LinkedIn</a>
            <div class="nav-links">
                <a href="profile.php">My Profile</a>
                <a href="network.php">My Network</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="stats-sidebar">
            <div class="section-card">
                <h2 class="section-title">Network Statistics</h2>
                <?php
                $connections_count = mysqli_num_rows($connections = mysqli_query($conn, "SELECT * FROM connections WHERE (user_id='$user_id' OR connection_id='$user_id') AND status='accepted'"));
                $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM connections WHERE connection_id='$user_id' AND status='pending'"));
                ?>
                <p>Connections: <?php echo $connections_count; ?></p>
                <p>Pending Requests: <?php echo $pending_count; ?></p>
            </div>
        </div>

        <div class="network-content">
            <!-- Pending Requests Section -->
            <?php
            $pending = mysqli_query($conn, "SELECT users.* FROM connections JOIN users ON connections.user_id = users.id WHERE connections.connection_id='$user_id' AND connections.status='pending'");
            if(mysqli_num_rows($pending) > 0):
            ?>
            <div class="section-card">
                <h2 class="section-title">Pending Requests</h2>
                <?php while($row = mysqli_fetch_assoc($pending)): ?>
                    <div class="profile-card">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=random" alt="Profile Picture" class="profile-picture">
                        <div class="profile-info">
                            <div class="profile-name"><?php echo htmlspecialchars($row['name']); ?></div>
                        </div>
                        <a href="network.php?accept_id=<?php echo $row['id']; ?>" class="accept-btn">Accept</a>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <!-- Recommendations Section -->
            <div class="section-card">
                <h2 class="section-title">People You May Know</h2>
                <div class="connection-grid">
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM users WHERE id != '$user_id'");
                    while($row = mysqli_fetch_assoc($result)){
                        $check = mysqli_query($conn, "SELECT * FROM connections WHERE 
                            (user_id='$user_id' AND connection_id='{$row['id']}') OR 
                            (user_id='{$row['id']}' AND connection_id='$user_id')");
                        if(mysqli_num_rows($check) == 0):
                    ?>
                        <div class="profile-card">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=random" alt="Profile Picture" class="profile-picture">
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($row['name']); ?></div>
                            </div>
                            <a href="network.php?connect_id=<?php echo $row['id']; ?>" class="connect-btn">Connect</a>
                        </div>
                    <?php 
                        endif;
                    }
                    ?>
                </div>
            </div>

            <!-- Connections Section -->
            <div class="section-card">
                <h2 class="section-title">Your Connections</h2>
                <div class="connection-grid">
                    <?php
                    $connections = mysqli_query($conn, "SELECT DISTINCT users.* FROM connections 
                        JOIN users ON (
                            CASE 
                                WHEN connections.user_id = '$user_id' THEN connections.connection_id = users.id
                                ELSE connections.user_id = users.id
                            END
                        )
                        WHERE (connections.user_id='$user_id' OR connections.connection_id='$user_id') 
                        AND connections.status='accepted'");
                    while($row = mysqli_fetch_assoc($connections)):
                    ?>
                        <div class="profile-card">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=random" alt="Profile Picture" class="profile-picture">
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($row['name']); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
