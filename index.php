<?php
include("config.php");
include("comController.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống bình luận</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <div class="container">
        <!-- Phần bài viết -->
        <div class="post">
            <h1 class="title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <em class="time"><?php echo htmlspecialchars($post['created_at']); ?></em>
            <p class="content"><?php echo htmlspecialchars($post['content']); ?></p>
        </div>

        <!-- Phần bình luận -->
        <div class="comment-section">
            <h2>Bình luận</h2>

            <!-- Form thêm bình luận -->
            <form action="" method="POST" class="comment-form">
                <input type="text" name="commenter_name" placeholder="Username" required><br>
                <textarea name="comment_text" placeholder="Viết bình luận ở đây..." required></textarea><br>
                <input type="hidden" name="parent_comment_id" value="">
                <button type="submit" name="add_comment" class="reply-button">Thêm bình luận</button>
            </form>
            
            <!-- Hiển thị các bình luận -->
            <?php
            displayComments($comments);
            ?>
        </div>
    </div>
</body>
</html>
