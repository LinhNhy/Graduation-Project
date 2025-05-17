<style>
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap');
*:not(i){
    font-family: "Quicksand", sans-serif !important;
}
</style>
<header class="header-destop">
    <nav>
        <div class="search">
            <form action="./blog.php" method="GET">
                <input type="text" name="search" hidden value="1">
                <input type="text" name="keyword">
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="menu">
            <ul>
                <li><a href="./index.php">Trang chủ</a></li>
                <li>
                    <a href="./about.php">Giới thiệu <i class="fa-solid fa-angle-down"></i></a>
                    <div class="sub-menu">
                        <a href="">Lịch sử</a>
                    </div>
                </li>
                <li><a href="./blog.php">Thông tin</a></li>
                <li>
                    <a href="./shop.php">Sản phẩm <i class="fa-solid fa-angle-down"></i></a>
                    <div class="sub-menu">
                        <?php 
                        include './config/db.php';
                        $catesNav = $conn->query("SELECT * FROM categories");
                        ?>
                        <?php foreach($catesNav as $cateNav) : ?>
                            <a href="./shop.php?categoryId=<?= $cateNav['id'] ?>"><?= $cateNav['name'] ?></a>
                        <?php endforeach ?>
                    </div>
                </li>
                <li><a href="./contact.php">Liên hệ</a></li>
                <li>
                    <a href="#"><i class="fa-solid fa-globe"></i> VN</a>
                    <div class="sub-menu">
                        <a href="#" style="display: flex; align-items: center; gap: 10px;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/vn.png" alt=""> VN</a>
                        <a href="#" style="display: flex; align-items: center; gap: 10px;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/en.png" alt=""> EN</a>
                        <a href="#" style="display: flex; align-items: center; gap: 10px;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/cn.png" alt=""> CN</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<div class="head-mobi">
    <header class="header-mobile">
        <header class="header-mobile">
            <i class="fa-solid fa-bars menu-icon" onclick="toggleMenu()"></i>
            <span>LOGO</span>
            <i class="fa-solid fa-search search-icon" onclick="toggleSearch()"></i>
        </header>
        <nav class="mobile-menu">
            <ul>
                <li><a href="./index.php">Trang chủ</a></li>
                <li class="has-submenu">
                    <a href="./about.php" onclick="toggleSubmenu(event)">Giới thiệu <i class="fa-solid fa-angle-down"></i></a>
                    <ul class="submenu">
                        <li><a href="#">Lịch sử</a></li>
                    </ul>
                </li>
                <li><a href="./blog.php">Thông tin</a></li>
                <li class="has-submenu">
                    <a href="./shop.php" onclick="toggleSubmenu(event)">Sản phẩm <i class="fa-solid fa-angle-down"></i></a>
                    <ul class="submenu">
                        <?php 
                        include './config/db.php';
                        $catesNav = $conn->query("SELECT * FROM categories");
                        ?>
                        <?php foreach($catesNav as $cateNav) : ?>
                            <li><a href="./shop.php?categoryId=<?= $cateNav['id'] ?>"><?= $cateNav['name'] ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </li>
                <li class="has-submenu"><a href="./contact.php">Liên hệ</a></li>
                <li class="has-submenu">
                    <a href="#" onclick="toggleSubmenu(event)"><img src="./image/vn.png" width="20px" height="20px" style="border-radius: 50%;object-fit: cover;" alt=""> VN</a>
                    <ul class="submenu">
                        <li>
                            <a href="" style="display: flex; align-items: center; gap: 10px; justify-content: left;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/vn.png" alt=""> VN</a>
                        </li>
                        <li>
                            <a href="" style="display: flex; align-items: center; gap: 10px; justify-content: left;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/en.png" alt=""> EN</a>
                        </li>
                        <li>
                            <a href="" style="display: flex; align-items: center; gap: 10px; justify-content: left;"><img width="20px" style="border-radius: 100%; object-fit: cover;" height="20px" src="./image/cn.png" alt=""> CN</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav> 
        
        <div class="search-box">
            <input type="text" placeholder="Tìm kiếm...">
        </div>
        
        <script>
            function toggleMenu() {
                document.querySelector('.mobile-menu').classList.toggle('active');
                document.querySelector('.search-box').classList.remove('active');
            }
            
            function toggleSearch() {
                document.querySelector('.search-box').classList.toggle('active');
                document.querySelector('.mobile-menu').classList.remove('active');
            }
            
            function toggleSubmenu(event) {
                event.preventDefault();
                let submenu = event.target.closest("li").querySelector(".submenu");
                if (submenu) {
                    submenu.classList.toggle("active");
                }
            }
        </script>
    </header>
</div>
<!-- Audio vẫn hiển thị trong DOM nhưng tàn hình -->
<?php $stop = $_GET["stop"] ?? 0;?>
<?php if($stop != 1) : ?>
    <audio src="./audio/nhacnen.mp3" autoplay loop></audio>
<?php endif ?>



