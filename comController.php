<?php
// Include file config.php để kết nối với database
include("config.php");

// Thêm bình luận
function addComment($post_id, $commenter_name, $comment_text, $parent_comment_id = null)
{
    global $conn;

    // Truy vấn SQL để thêm bình luận mới
    $stmt = $conn->prepare("INSERT INTO comments (post_id, parent_comment_id, commenter_name, comment_text, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $post_id, $parent_comment_id, $commenter_name, $comment_text);

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

    // Truy vấn SQL để xóa bình luận
    $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $comment_id);

    // Thực thi và kiểm tra nếu có lỗi
    if ($stmt->execute()) {
        echo "Bình luận đã được xóa thành công!";
    } else {
        echo "Lỗi khi xóa bình luận: " . $stmt->error;
    }
    $stmt->close();
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
