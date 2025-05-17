<?php
include '../config/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản lý Đánh giá Chatbot</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset CSS cơ bản */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f0f2f5, #dfe9f3);
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        header {
            background: #4CAF50;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            color: #fff;
            font-size: 2em;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.05em;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
        .action-icons a {
            margin-right: 10px;
            text-decoration: none;
            color: #555;
            font-size: 1.2em;
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .action-icons a:hover {
            color: #000;
            transform: scale(1.1);
        }
        /* Responsive design */
        @media (max-width: 768px) {
            th, td {
                padding: 10px;
            }
            header h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Danh sách Đánh giá Chatbot</h1>
        </header>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Câu trả lời</th>
                        <th>Đánh giá</th>
                        <th>Thời gian</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sắp xếp theo ID tăng dần (1-n)
                    $result = $conn->query("SELECT * FROM ratings ORDER BY id ASC");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['answer']) . "</td>
                                    <td>" . htmlspecialchars($row['rating']) . "</td>
                                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                                    <td class='action-icons'>
                                        <a href='edit.php?id=" . htmlspecialchars($row['id']) . "' title='Sửa'><i class='fas fa-edit'></i></a>
                                        <a href='delete.php?id=" . htmlspecialchars($row['id']) . "' title='Xóa' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?');\"><i class='fas fa-trash-alt'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Chưa có dữ liệu đánh giá</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
