<?php
include './config/db.php'; // Kết nối database

// Lọc danh mục sản phẩm
$categoryFilter = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;
$minPrice = isset($_GET['min-price']) ? (float)$_GET['min-price'] : 0;
$maxPrice = isset($_GET['max-price']) ? (float)$_GET['max-price'] : 1000000000;
$order = isset($_GET['fil']) && $_GET['fil'] == 'desc' ? 'ASC' : 'DESC';
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Lấy danh mục sản phẩm
$categories = $conn->query("SELECT * FROM categories");

// Lấy danh sách sản phẩm theo bộ lọc
$query = "SELECT * FROM products WHERE price BETWEEN $minPrice AND $maxPrice";

if ($categoryFilter) {
    $query .= " AND category_id = $categoryFilter";
}
if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}
$query .= " ORDER BY price $order";
$products = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/reponsive.css">
    <link rel="stylesheet" href="./css/blog.css">
    <link rel="stylesheet" href="./css/detail-blog.css">
    <link rel="stylesheet" href="./css/shop.css">
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
        <div class="padding-main shop-main">
        <aside>
            <p class="title-cate">DANH MỤC SẢN PHẨM</p>
            <ul>
                <?php while ($category = $categories->fetch_assoc()) : ?>
                    <li><a href="?categoryId=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a></li>
                <?php endwhile; ?>
            </ul>
            <form action="" method="GET">
                <p><strong>Lọc theo giá</strong></p>
                <input type="text" name="min-price" placeholder="Nhỏ nhất">
                <input type="text" name="max-price" placeholder="Lớn nhất">
                <button name="filter-price">Lọc</button>
            </form>
        </aside>
        
        <article>
            <div class="search-fillter-des">
                <form action="?" method="post">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <div>
                    <a href="?fil=asc">Giá từ cao đến thấp</a>
                    <a href="?fil=desc">Giá từ thấp đến cao</a>
                </div>
            </div>
            <?php if ($products->num_rows > 0): ?>
                <div class="shop-pro-list">
                <?php while ($product = $products->fetch_assoc()) : ?>
                        <div class="a-pro">
                            <img src="./admin/uploads/<?= $product['image'] ?>" width="100%" alt="<?= htmlspecialchars($product['name']) ?>">
                            <a href="./detail-shop.php?id=<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
                            <strong><?= number_format($product['price']) ?> VNĐ</strong>
                        </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
                <p style="font-weight: bold; font-size:14px; color:red;margin-top:20px;">⚠️ Không tìm thấy sản phẩm!</p>
            <?php endif; ?>
        </article>
    </div>
        <h1 style="text-align: center;margin-top: 50px;color: #3E5B3F;">ĐÁNH GIÁ</h1><br><br>
    </main>
    <?php include './includes/footer.php' ?>
    <script src="./js/index.js"></script>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let index = 0;
    const slides = document.querySelectorAll('.slide');
    const slider = document.querySelector('.slider');
    const indicators = document.querySelector('.indicators');

    function showSlide() {
        slider.style.transform = `translateX(${-index * 100}%)`;
        updateIndicators();
    }

    function nextSlide() {
        index = (index + 1) % slides.length;
        showSlide();
    }

    function updateIndicators() {
        document.querySelectorAll('.indicator').forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    function createIndicators() {
        for (let i = 0; i < slides.length; i++) {
            const indicator = document.createElement('div');
            indicator.classList.add('indicator');
            indicator.addEventListener('click', () => {
                index = i;
                showSlide();
            });
            indicators.appendChild(indicator);
        }
        updateIndicators();
    }

    createIndicators();
    setInterval(nextSlide, 3000);
});

</script>
