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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
        <form action="" method="POST" class="comment-form" id="commentForm">
            <input type="text" name="commenter_name" placeholder="Username" required><br>
            <textarea name="comment_text" placeholder="Viết bình luận ở đây..." required></textarea><br>
            <input type="hidden" name="parent_comment_id" value="">

        <!-- Thêm reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LfX5H4qAAAAAKDVoVci3BoeQ8DVpfIuTYqHPrN5"></div>
        <button type="submit" name="add_comment" class="reply-button">Thêm bình luận</button>
        </form>

    <?php
        // Kiểm tra khi form được submit
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Kiểm tra reCAPTCHA
        $secretKey = "6LfX5H4qAAAAAAWMRwzR2l6oZ69sG09opuEcXCBa";  
        $responseKey = $_POST['g-recaptcha-response'];
        $userIP = $_SERVER['REMOTE_ADDR'];

        // Gửi yêu cầu xác thực reCAPTCHA đến Google
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
        $response = file_get_contents($url);
        $responseKeys = json_decode($response, true);

        // Kiểm tra kết quả xác thực reCAPTCHA
        if (intval($responseKeys["success"]) !== 1) {
            // Nếu xác thực không thành công, thông báo lỗi và không cho gửi bình luận
            echo "<p style='color:red;'>Vui lòng xác thực reCAPTCHA trước khi gửi bình luận.</p>";
        } else {
            // Nếu xác thực thành công, tiếp tục xử lý bình luận
            $commenter_name = $_POST['commenter_name']; // Tên người bình luận
            $comment_text = $_POST['comment_text']; // Nội dung bình luận

            // Xử lý bình luận 
            echo "<p>Bình luận của bạn đã được gửi thành công!</p>";
        }
    }
    ?>

        <!-- JavaScript kiểm tra reCAPTCHA trước khi gửi -->
        <script>
            document.getElementById("commentForm").addEventListener("submit", function(event) {
                var recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length == 0) {
                    // Nếu reCAPTCHA chưa được xác thực, ngừng gửi form và thông báo lỗi
                    event.preventDefault(); 
                    alert("Vui lòng xác thực reCAPTCHA trước khi gửi bình luận.");
                }
            });
        </script>


            <!-- Hiển thị các bình luận -->
            <?php
            // Lấy dữ liệu bình luận từ cơ sở dữ liệu
            displayComments($comments);
            ?>
        </div>
    </div>
</body>
</html>
