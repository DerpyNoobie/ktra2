<?php
include("config.php");
include("comController.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    deleteComment($comment_id);
    
    // Quay lại trang chính sau khi xóa
    header("Location: index.php");
    exit();
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
