<?php
// Kết nối database từ config.php
include("config.php");
// Import các chức năng xử lý từ commentController.php
include("comController.php");

// Lấy danh sách bài viết
$sql = "SELECT post_id, title FROM posts";
$result = $conn->query($sql);

// Hiển thị danh sách các bài viết dưới dạng liên kết
echo "<div class='post-list'><h2>Danh sách bài viết</h2><ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li><a href='index.php?post_id=" . $row['post_id'] . "'>" . $row['title'] . "</a></li>";
}
echo "</ul></div>";

// Lấy bài viết theo ID
$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 1; // Lấy post_id từ URL, mặc định là 1
$post_query = $conn->prepare("SELECT * FROM posts WHERE post_id = ?");
$post_query->bind_param("i", $post_id);
$post_query->execute();
$post_result = $post_query->get_result();
$post = $post_result->fetch_assoc();

if (!$post) {
    die("Bài viết không tồn tại.");
}

// Lấy danh sách bình luận của bài viết
$comments = getCommentsByPost($post_id);

// Thêm bình luận mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    $commenter_name = $_POST['commenter_name'];
    $comment_text = $_POST['comment_text'];
    $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;
    
    addComment($post_id, $commenter_name, $comment_text, $parent_comment_id);
    header("Location: index.php?post_id=$post_id"); // Refresh lại trang sau khi thêm bình luận
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        /* Styling cho UI */
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        .post { margin-bottom: 40px; }
        .comment-section { margin-top: 30px; }
        .comment { margin: 15px 0; padding: 10px; border: 1px solid #ddd; }
        .nested-comment { margin-left: 20px; border-left: 2px solid #ccc; padding-left: 10px; }
        textarea { width: 100%; height: 60px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Phần bài viết -->
        <div class="post">
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
        </div>

        <!-- Phần bình luận -->
        <div class="comment-section">
            <h2>Comments</h2>

            <!-- Form thêm bình luận -->
            <form action="" method="POST">
                <input type="text" name="commenter_name" placeholder="Your name" required><br>
                <textarea name="comment_text" placeholder="Write your comment here..." required></textarea><br>
                <input type="hidden" name="parent_comment_id" value="">
                <button type="submit">Add Comment</button>
            </form>

            <!-- Hiển thị các bình luận -->
            <?php
            function displayComments($comments, $parent_id = null, $level = 0) {
                foreach ($comments as $comment) {
                    if ($comment['parent_comment_id'] == $parent_id) {
                        echo '<div class="comment' . ($level > 0 ? ' nested-comment' : '') . '">';
                        echo '<strong>' . htmlspecialchars($comment['commenter_name']) . '</strong> <em>' . htmlspecialchars($comment['created_at']) . '</em>';
                        echo '<p>' . htmlspecialchars($comment['comment_text']) . '</p>';
                        
                        // Form trả lời bình luận (nested comments)
                        echo '<form action="" method="POST" style="margin-top: 10px;">';
                        echo '<input type="hidden" name="parent_comment_id" value="' . $comment['comment_id'] . '">';
                        echo '<input type="text" name="commenter_name" placeholder="Your name" required><br>';
                        echo '<textarea name="comment_text" placeholder="Write your reply here..." required></textarea><br>';
                        echo '<button type="submit">Reply</button>';
                        echo '</form>';

                        // Đệ quy để hiển thị bình luận con
                        displayComments($comments, $comment['comment_id'], $level + 1);
                        echo '</div>';
                    }
                }
            }

            displayComments($comments);
            ?>
        </div>
    </div>
</body>
</html>
