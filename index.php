<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkedIn Clone</title>
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
        }

        .post-button {
            background-color: white;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 0 0.3rem rgba(0, 0, 0, 0.1);
        }

        .post-button a {
            display: block;
            text-decoration: none;
            color: #666666;
            padding: 0.5rem;
            border: 1px solid #666666;
            border-radius: 25px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .post-button a:hover {
            background-color: #f3f2ef;
        }

        .post {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0 0.3rem rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .post-header img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin-right: 0.8rem;
        }

        .post-author {
            color: #000;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }

        .post-content {
            color: #333;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .post-timestamp {
            color: #666666;
            font-size: 0.8rem;
        }

        .no-posts {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            color: #666666;
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
        <div class="post-button">
            <a href="post.php">Start a post</a>
        </div>

        <?php
        $result = mysqli_query($conn, "SELECT posts.content, posts.created_at, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
        if(mysqli_num_rows($result) > 0){
            while($post = mysqli_fetch_assoc($result)){
                ?>
                <div class="post">
                    <div class="post-header">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($post['name']); ?>&background=random" alt="Profile Picture">
                        <div>
                            <div class="post-author"><?php echo htmlspecialchars($post['name']); ?></div>
                            <div class="post-timestamp">Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></div>
                        </div>
                    </div>
                    <div class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                </div>
                <?php
            }
        } else {
            echo '<div class="no-posts">No posts yet.</div>';
        }
        ?>
    </main>
</body>
</html>
