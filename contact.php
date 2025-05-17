<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';
function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'trinhlinhnhi.work@gmail.com';
        $mail->Password = 'lfpx ijpt zkwe yzoj'; // Cân nhắc dùng biến môi trường thay vì hard-code
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom("trinhlinhnhi.work@gmail.com");
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
<?php
require_once './config/db.php';

if (isset($_POST["send"])) {
    $name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email = htmlspecialchars(trim($_POST["email"] ?? ""));
    $phone = htmlspecialchars(trim($_POST["phone"] ?? ""));
    $content = htmlspecialchars(trim($_POST["content"] ?? ""));
    $created_at = date('Y-m-d H:i:s');

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO emails (`name`, `email`, `phone`, `content`,`created_at`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $content, $created_at);

        if ($stmt->execute()) {
            $title = "📬 Bạn vừa nhận được một thông báo mới từ khách hàng";
            $body = "
                <h2>Thông tin khách hàng</h2>
                <p><strong>Họ và tên:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Số điện thoại:</strong> {$phone}</p>
                <p><strong>Nội dung:</strong><br>{$content}</p>
                <hr>
                <p style='font-style: italic; color: #888;'>Vui lòng phản hồi sớm nhất có thể.</p>
            ";

            if(sendMail($email, $title, $body)){
                echo "
                    <body><script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Gửi thành công!',
                            text: 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất.',
                            confirmButtonText: 'Đóng'
                        }).then(() => {
        window.location.href = 'contact.php'; // Chuyển hướng sau khi đóng alert
    });
                    </script>
                    </body>
                ";
            }
        } else {
            echo "
                <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không thể lưu thông tin. Vui lòng thử lại sau.',
                        confirmButtonText: 'Đóng'
                    }).then(() => {
        window.location.href = 'contact.php'; // Chuyển hướng sau khi đóng alert
    });
                </script>
                </body>
            ";
        }
    } else {
        echo "
            <body>
            <script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Thiếu thông tin!',
                    text: 'Vui lòng điền đầy đủ các trường bắt buộc.',
                    confirmButtonText: 'OK'
                }).then(() => {
        window.location.href = 'contact.php'; // Chuyển hướng sau khi đóng alert
    });
            </script>
            </body>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/reponsive.css">
    <link rel="stylesheet" href="./css/blog.css">
    <link rel="stylesheet" href="./css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        header.header-destop{
            padding: 100px 0px 20px 0px;
            /* background-image: url('./image/bg1.png');
            background-size: cover; */
        }
    </style>
</head>
<body>
    <?php include './includes/header.php' ?>
    <main>
        <br>
        <div class="padding-main">
            <div class="form-contact">
                <h1 style="color: #3E5B3F;text-transform:uppercase">Liên hệ với Chùa Mía</h1>
                <form action="" method="POST">
                    <label for="">Họ và tên</label>
                    <input type="text" name="name" placeholder="Nhập họ và tên">
                    <label for="">Số điện thoại</label>
                    <input type="text" name="phone" id="" placeholder="Nhập số điện thoại">
                    <label for="">Email</label>
                    <input type="email" name="email" id="" placeholder="Nhập email">
                    <label for="">Nội dung</label>
                    <textarea name="content" id="" placeholder="Nhập nội dung"></textarea>
                    <div class="button-contact">
                        <button name="send">Gửi đi</button>
                    </div>
                </form>
            </div>
        </div>
        <h1 style="text-align: center;margin-top: 50px;color: #3E5B3F;">ĐÁNH GIÁ</h1><br><br>
    </main>
    <?php include './includes/footer.php' ?>
    <script src="./js/index.js"></script>
</body>
</html>

