<?php
// Include file config.php để kết nối với database
include("config.php");


// Thêm bình luận
function addComment($post_id, $commenter_name, $comment_text, $parent_comment_id = null)
{
    global $conn;

    // Đảm bảo rằng parent_comment_id là NULL nếu không có bình luận cha
    if ($parent_comment_id == 0) {
        $parent_comment_id = null;
    }

    // Truy vấn SQL để thêm bình luận
    $stmt = $conn->prepare("INSERT INTO comments (post_id, commenter_name, comment_text, parent_comment_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $post_id, $commenter_name, $comment_text, $parent_comment_id);

    // Thực thi và kiểm tra nếu có lỗi
    if ($stmt->execute()) {
        echo "Bình luận đã được thêm thành công!";
    } else {
        echo "Lỗi khi thêm bình luận: " . $stmt->error;
    }
    $stmt->close();
}


// Chỉnh sửa bình luận
function editComment($comment_id, $new_comment_text)
{
    global $conn;
    // Truy vấn SQL để cập nhật nội dung bình luận
    $stmt = $conn->prepare("UPDATE comments SET comment_text = ?, updated_at = NOW() WHERE comment_id = ?");
    $stmt->bind_param("si", $new_comment_text, $comment_id);

    // Thực thi và kiểm tra nếu có lỗi
    if ($stmt->execute()) {
        echo "Bình luận đã được cập nhật thành công!";
    } else {
        echo "Lỗi khi cập nhật bình luận: " . $stmt->error;
    }
    $stmt->close();
}

// Xóa bình luận
function deleteComment($comment_id)
{
    global $conn;

    // Kiểm tra xem comment_id có tồn tại không
    $stmt_check = $conn->prepare("SELECT comment_id FROM comments WHERE comment_id = ?");
    $stmt_check->bind_param("i", $comment_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Nếu bình luận tồn tại, tiến hành xóa
        $stmt_delete = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt_delete->bind_param("i", $comment_id);

        if ($stmt_delete->execute()) {
            echo "Bình luận đã được xóa thành công!";
        } else {
            echo "Lỗi khi xóa bình luận: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } else {
        echo "Bình luận không tồn tại hoặc đã bị xóa.";
    }

    $stmt_check->close();
}


// Lấy danh sách bình luận theo bài viết
function getCommentsByPost($post_id)
{
    global $conn;

    // Truy vấn SQL để lấy danh sách bình luận
    $stmt = $conn->prepare("SELECT comment_id, parent_comment_id, commenter_name, comment_text, created_at FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $post_id);

    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    $stmt->close();
    return $comments;
}

// Lấy danh sách bài viết
$sql = "SELECT post_id, title FROM posts";
$result = $conn->query($sql);

// Hiển thị danh sách các bài viết dưới dạng liên kết
echo "<div class='post-list'><h1>DANH SÁCH BÀI VIẾT</h1><ul>";
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

// Hiển thị bình luận của bài viết
function displayComments($comments, $parent_id = null) {
    foreach ($comments as $comment) {
        if ($comment['parent_comment_id'] == $parent_id) {
            echo '<div class="comment">';
            echo '<strong>' . htmlspecialchars($comment['commenter_name']) . '</strong> <em>' . htmlspecialchars($comment['created_at']) . '</em>';
            echo '<p>' . htmlspecialchars($comment['comment_text']) . '</p>';
    
            // Nút chỉnh sửa dẫn đến trang edit_comment.php
            echo "<a href='edit_comment.php?comment_id=" . $comment['comment_id'] . "' class='edit-button'>Chỉnh sửa</a>";
    
            // Nút xóa bình luận
            echo '<form action="" method="POST" style="display: inline-block;">';
            echo '<input type="hidden" name="comment_id" value="' . $comment['comment_id'] . '">';
            echo '<button type="submit" name="delete_comment" class="delete-button">Xóa</button>';
            echo '</form>';
    
            // Form trả lời bình luận (nested comments)
            echo '<form action="" method="POST" style="margin-top: 10px;" class="comment-form">';
            echo '<input type="hidden" name="parent_comment_id" value="' . $comment['comment_id'] . '">';
            echo '<input type="text" name="commenter_name" placeholder="Username" required><br>';
            echo '<textarea name="comment_text" placeholder="Viết phản hồi ở đây..." required></textarea><br>';
            echo '<button type="submit" name="add_comment" class="reply-button">Phản hồi</button>';
            echo '</form>';
    
            // Gọi lại hàm để hiển thị bình luận con
            displayComments($comments, $comment['comment_id']);
            echo '</div>';
        }
    }
}

// Thêm bình luận mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $commenter_name = $_POST['commenter_name'];
    $comment_text = $_POST['comment_text'];
    $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;
    
    addComment($post_id, $commenter_name, $comment_text, $parent_comment_id);
    header("Location: index.php?post_id=$post_id"); // Refresh lại trang sau khi thêm bình luận
    exit();
}

// Xóa bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = intval($_POST['comment_id']);
    deleteComment($comment_id);
    header("Location: index.php?post_id=$post_id");
    exit();
}

// Chỉnh sửa bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_comment'])) {
    $comment_id = intval($_POST['comment_id']);
    $new_comment_text = $_POST['new_comment_text'];
    editComment($comment_id, $new_comment_text);
    header("Location: index.php?post_id=$post_id");
    exit();
}

