<?php
include("config.php");
include("comController.php");

// Kiểm tra nếu `comment_id` có trong URL
if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];
    
    // Lấy chi tiết bình luận hiện tại từ database
    global $conn;
    $stmt = $conn->prepare("SELECT comment_text FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Kiểm tra nếu bình luận tồn tại
    if ($result->num_rows > 0) {
        $comment = $result->fetch_assoc();
    } else {
        echo "Bình luận không tồn tại.";
        exit();
    }
    $stmt->close();
} else {
    echo "Không có `comment_id`.";
    exit();
}

// Xử lý khi form cập nhật bình luận được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['new_comment_text'])) {
    $comment_id = $_POST['comment_id'];
    $new_comment_text = $_POST['new_comment_text'];
    
    // Gọi hàm để chỉnh sửa bình luận
    editComment($comment_id, $new_comment_text);
    
    // Quay lại trang chính sau khi cập nhật
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bình luận</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa bình luận</h2>
        
        <!-- Form cập nhật bình luận -->
        <form action="edit_comment.php?comment_id=<?php echo $comment_id; ?>" method="POST">
            <textarea name="new_comment_text" required><?php echo htmlspecialchars($comment['comment_text']); ?></textarea><br>
            <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>"> <!-- Trường ẩn giữ lại comment_id -->
            <button type="submit" class="edit-button">Cập nhật bình luận</button>
        </form>

        <!-- Nút xóa bình luận -->
        <form action="delete_comment.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">
            <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>"> <!-- Giữ lại comment_id cho nút xóa -->
            <button type="submit" name="delete_comment" class="delete-button">Xóa</button>
        </form>
    </div>
</body>
</html>