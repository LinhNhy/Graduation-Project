<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài viết</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/index-reponsive.css">
    <link rel="stylesheet" href="./css/about.css">
    <link rel="stylesheet" href="./css/reponsive.css">
    <link rel="stylesheet" href="./css/blog.css">
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
        <?php 
        include './config/db.php';
        ?>
        <?php if(isset($_GET["search"]) && isset($_GET["keyword"])) : ?>
            <?php $keyword = "%" . $_GET["keyword"] . "%" ?? "" ?>
            <div class="padding-main">
                <div class="title-new"><h3>KẾT QUẢ TÌM KIẾM</h3></div>
                <div class="list-blog">
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ?");
                    $stmt->bind_param('s', $keyword);
                    $stmt->execute();
                    $blogs = $stmt->get_result();
                    ?>
                    <?php if($blogs->num_rows > 0) : ?>
                    <?php foreach ($blogs as $blog) : ?>
                            <div class="a-blog">
                                <img src="./admin/uploads/<?= $blog['image'] ?>" alt="">
                                <div class="infor-a-blog">
                                    <div class="title-blog"><?= $blog['title'] ?></div>
                                    <div class="des-blog">
                                        <?= $blog['description'] ?>
                                    </div>
                                    <a href="./detail-blog.php?stop=1&id=<?= $blog['id'] ?>">Đọc thêm</a>
                                </div>
                            </div>
                    <?php endforeach ?>
                    <?php else : ?>
                        <p style="font-weight: bold; font-size:14px; color:red;margin-top:20px;">⚠️ Không tìm thấy bài viết!</p>
                    <?php endif ?>    
                </div>
            </div>
        <?php else : ?>
            <div class="padding-main">
                <div class="head-blog">
                    <div class="blog-slider">
                        <section class="slider-container">
                            <div class="slider">
                                <?php 
                                $blogSlides = $conn->query("SELECT * FROM posts LIMIT 3");
                                ?>
                                <?php foreach ($blogSlides as $blogSlide) : ?>
                                <div class="slide">
                                    <img src="./admin/uploads/<?= $blogSlide['image'] ?>" alt="Ảnh bài viết 1">
                                    <div class="content">
                                        <h2><a href="./detail-blog.php?stop=1&id=<?= $blogSlide['id'] ?>" style="color:#3e5b3f;text-decoration:none;"><?= $blogSlide['title'] ?></a></h2>
                                        <p style="color: #3E5B3F;font-weight: bold;"><?= date("d-m-Y", strtotime($blogSlide['created_at'])) ?></p>
                                        <p><?= $blogSlide['description'] ?></p>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>
                        
                        </section>
                        <div class="indicators"></div>
                    </div>
                    <div class="sub-blog">
                        <?php 
                        $subBlogs = $conn->query("SELECT * FROM posts LIMIT 2");
                        ?>
                        <?php foreach ($subBlogs as $subBlog) : ?>
                            <div class="a-sub-blog">
                                <img src="./admin/uploads/<?= $subBlog['image'] ?>" alt="">
                                <div class="text-sub-blog">
                                    <h3><a href="./detail-blog.php?stop=1&id=<?= $subBlog['id'] ?>" style="color: white;text-decoration:white"><?= $subBlog['title'] ?></a></h3>
                                    <p><?= date("d-m-Y", strtotime($subBlog['created_at'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="padding-main">
                <div class="title-new"><h3>TIN MỚI NHẤT</h3></div>
                <div class="list-blog">
                    <?php 
                    $blogs = $conn->query("SELECT * FROM posts");
                    ?>
                    <?php foreach ($blogs as $blog) : ?>
                        <div class="a-blog">
                            <img src="./admin/uploads/<?= $blog['image'] ?>" alt="">
                            <div class="infor-a-blog">
                                <div class="title-blog"><?= $blog['title'] ?></div>
                                <div class="des-blog">
                                    <?= $blog['description'] ?>
                                </div>
                                <a href="./detail-blog.php?stop=1&id=<?= $blog['id'] ?>">Đọc thêm</a>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
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
