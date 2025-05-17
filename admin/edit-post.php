<?php
include '../config/db.php';
include './includes/nav.php'; // Thêm menu điều hướng

// Kiểm tra ID bài viết
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='list-post.php'</script>";
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts WHERE id = $id");
$post = $result->fetch_assoc();

if (!$post) {
    echo "<script>alert('Bài viết không tồn tại!'); window.location.href='list-post.php';</script>";
    exit;
}

// Xử lý cập nhật bài viết
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $image = $post['image']; // Giữ ảnh cũ
    $audio = $post['audio']; // Giữ audio cũ

    // Kiểm tra nếu có ảnh mới được tải lên
    if (!empty($_FILES["image"]["name"])) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image);
    }

    // Kiểm tra nếu có audio mới được tải lên
    if (!empty($_FILES["audio"]["name"])) {
        $audio = time() . "_" . basename($_FILES["audio"]["name"]);
        move_uploaded_file($_FILES["audio"]["tmp_name"], "uploads/" . $audio);
    }

    if (!empty($title) && !empty($description) && !empty($content)) {
        $stmt = $conn->prepare("UPDATE posts SET title = ?, description = ?, content = ?, image = ?, audio = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $title, $description, $content, $image, $audio, $id);
        $stmt->execute();
        
        echo "<script>window.location.href='list-post.php'</script>";
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
    <title>Chỉnh sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-lg p-4 mx-auto" style="max-width: 800px;">
        <h2 class="mb-4 text-center text-primary">📝 Chỉnh sửa bài viết</h2>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">📄 Mô tả</label>
                <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($post['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">📖 Nội dung</label>
                <textarea name="content" id="content" class="form-control" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">🖼 Ảnh hiện tại</label><br>
                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?= $post['image'] ?>" width="150" class="img-thumbnail mb-2">
                <?php else: ?>
                    <p>⚠️ Chưa có ảnh</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">📸 Chọn ảnh mới (nếu muốn thay đổi)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">🎶 Audio hiện tại</label><br>
                <?php if (!empty($post['audio'])): ?>
                    <audio controls>
                        <source src="uploads/<?= $post['audio'] ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                <?php else: ?>
                    <p>⚠️ Chưa có audio</p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">🎧 Chọn audio mới (nếu muốn thay đổi)</label>
                <input type="file" name="audio" class="form-control" accept="audio/*">
            </div>

            <button type="submit" class="btn btn-success w-100">✅ Cập nhật</button>
            <a href="list-post.php" class="btn btn-secondary w-100 mt-2">🔙 Quay lại</a>
        </form>
    </div>
</div>

<script>
    CKEDITOR.replace('content');

    function validateForm() {
        let title = document.getElementById("title").value.trim();
        let description = document.getElementById("description").value.trim();
        let content = document.getElementById("content").value.trim();

        if (title === "" || description === "" || content === "") {
            showError();
            return false;
        }
        return true;
    }

    function showError() {
        Swal.fire({
            icon: "error",
            title: "Lỗi!",
            text: "Vui lòng nhập đầy đủ thông tin bài viết.",
            confirmButtonColor: "#d33"
        });
    }

    function showSuccess() {
        Swal.fire({
            icon: "success",
            title: "Thành công!",
            text: "Bài viết đã được cập nhật.",
            confirmButtonColor: "#28a745"
        }).then(() => {
            window.location.href = "list-post.php";
        });
    }
</script>

</body>
</html>
