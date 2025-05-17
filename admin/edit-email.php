<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Kiểm tra ID danh mục
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='list-email.php'</script>";
    exit;
}

$id = $_GET['id'];
// delete
$idD = $_GET["idD"] ?? "";
if(isset($_GET["delete"]) && $_GET["delete"] == 1){
    $conn->query("DELETE FROM email_send WHERE id = $idD");
    echo "<script>window.location.href='?id=$id'</script>";
}
// list email
$emails = $conn->query("SELECT * FROM email_send WHERE email_id = $id");
// email 
$result = $conn->query("SELECT * FROM emails WHERE id = $id");
$email = $result->fetch_assoc();

if (!$email) {
    echo "<script>alert('Email không tồn tại!'); window.location.href='list-email.php';</script>";
    exit;
}

// Xử lý cập nhật danh mục
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? "";
    $content = $_POST['content'] ?? "";
    $created_at = date('Y-m-d H:i:s');

    if (!empty($title) || !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO email_send (`email_id`,`title`, `content`, `created_at`) VALUES (?,?,?,?)");
        $stmt->bind_param("isss", $id, $title, $content, $created_at);
        if($stmt->execute()){
            function buildEmailBody($content) {
                return '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;">
                    <div style="text-align: center; padding-bottom: 20px;">
                        <h2 style="color: #333;">📩 Bạn nhận được một tin nhắn mới</h2>
                    </div>
                    <div style="background-color: #fff; padding: 15px 20px; border-radius: 5px; box-shadow: 0 0 5px rgba(0,0,0,0.05);">
                        <p style="font-size: 16px; color: #555; line-height: 1.6;">' . nl2br(htmlspecialchars($content)) . '</p>
                    </div>
                    <div style="text-align: center; margin-top: 30px; color: #aaa; font-size: 12px;">
                        <p>Chùa Mía</p>
                    </div>
                </div>
                ';
            }
            $body = buildEmailBody($content);
            if(sendMail($email['email'], $title, $body)){
                echo "<script>window.location.href='?id=$id'</script>";
            }
        }
    } else {
        echo "<script>showError();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trả lời Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 600px;">
        <?php if($emails->num_rows > 0) : ?>
            <?php foreach ($emails as $aEmail) : ?>
                <div class="shadow-sm mb-4 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-uppercase font-weight-bold text-success">EMAIL ĐÃ TRẢ LỜI</h6>
                        <a href="?id=<?= $id ?>&delete=1&idD=<?= $aEmail['id'] ?>" class="btn-sm btn btn-danger" onclick="return confirm('Bạn chắc chứ?')">Xóa</a>
                    </div>
                    <p>Tiêu đề: <?= $aEmail['title'] ?></p>
                    <p>Nội dung: <?= $aEmail['content'] ?></p>
                </div>
            <?php endforeach ?>
        <?php endif ?>
        <div class="shadow-sm mb-4 p-3">
            <h6 class="text-uppercase font-weight-bold text-danger">Thông tin người nhận</h6>
            <p>👤 <?= $email['name'] ?></p>
            <p>📧 <?= $email['email'] ?></p>
            <p>📞 <?= $email['phone'] ?></p>
            <p>💬 <?= $email['content'] ?></p>
        </div>
        <form action="" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề</label>
                <input type="text" class="form-control" name="title" placeholder="Nhập tiêu đề email">
            </div>
            <div class="mb-3">
                <label class="form-label">📝 Nội dung trả lời</label>
                <textarea name="content" class="form-control" rows="6" placeholder="Nhập nội dung email trả lời" id=""></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">✅ Trả lời</button>
            <a href="list-email.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
function showError() {
    Swal.fire({
        icon: "error",
        title: "Lỗi!",
        text: "Vui lòng nhập đầy đủ thông tin.",
        confirmButtonColor: "#d33"
    });
}

function showSuccess() {
    Swal.fire({
        icon: "success",
        title: "Thành công!",
        text: "Email đã được gửi đến khách hàng",
        confirmButtonColor: "#28a745"
    })
}
</script>

</body>
</html>
