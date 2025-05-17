<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold text-light" href="./">
            <i class="bi bi-speedometer2"></i> Admin Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-light" href="list-category.php">
                        <i class="bi bi-folder"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="list-product.php">
                        <i class="bi bi-box-seam"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="list-post.php">
                        <i class="bi bi-file-earmark-text"></i> Bài viết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="list-email.php">
                        <i class="bi bi-file-earmark-text"></i> Email
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['admin_username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Background Gradient + Hiệu ứng cuộn */
    .navbar {
        background: linear-gradient(90deg, #007bff, #6610f2);
        padding: 12px 0;
        transition: all 0.4s ease-in-out;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .navbar.scrolled {
        background: rgba(0, 0, 0, 0.9);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    /* Logo - Hiệu ứng Hover */
    .navbar-brand {
        font-size: 1.6rem;
        transition: transform 0.3s ease-in-out;
    }
    .navbar-brand:hover {
        transform: scale(1.1);
    }

    /* Link menu */
    .nav-link {
        font-weight: 500;
        transition: color 0.3s ease-in-out, transform 0.2s;
        padding: 10px 15px;
        border-radius: 5px;
    }
    .nav-link:hover {
        color: #ffdd57 !important;
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.2);
    }

    /* Active Menu */
    .nav-item.active .nav-link {
        color: #ffdd57 !important;
        font-weight: bold;
        background: rgba(255, 255, 255, 0.2);
    }

    /* Navbar Toggle Button */
    .navbar-toggler {
        border: none;
        outline: none;
        padding: 5px 10px;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease-in-out;
    }
    .navbar-toggler:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Dropdown */
    .dropdown-menu {
        background: #343a40;
        border: none;
        border-radius: 8px;
    }
    .dropdown-menu .dropdown-item {
        color: white;
        padding: 10px 20px;
    }
    .dropdown-menu .dropdown-item:hover {
        background: #495057;
    }
</style>

<script>
    // Active menu khi load trang
    document.addEventListener("DOMContentLoaded", function () {
        let links = document.querySelectorAll(".nav-link");
        let currentUrl = window.location.href;
        links.forEach(link => {
            if (currentUrl.includes(link.getAttribute("href"))) {
                link.parentElement.classList.add("active");
            }
        });

        // Thêm hiệu ứng navbar khi cuộn trang
        window.addEventListener("scroll", function () {
            let navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
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