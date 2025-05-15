<?php

$host = 'localhost';
$dbname = 'testovoe';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}


$sql = "
    SELECT 
        p.id,
        p.name AS product_name,
        p.description AS product_description,
        p.price,
        c.name AS category_name,
        s.quantity
    FROM 
        products p
    JOIN 
        categories c ON p.category_id = c.id
    JOIN 
        stock s ON p.id = s.product_id
    ORDER BY 
        p.id
";

try {
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка выполнения запроса: " . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Информация о товарах</title>
<style>
       
        @import url('https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400&display=swap');
        
        body {
            font-family: 'PT Serif', Georgia, 'Times New Roman', Times, serif;
            line-height: 1.8;
            color: #3a3a3a;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff9fa;
            background-image: url('https://transparenttextures.com/patterns/cream-paper.png');
        }
        
        h1 {
            color: #8e4162;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 400;
            font-size: 2.5em;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            border-bottom: 2px solid #f8c3cd;
            padding-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 30px 0;
            font-size: 1em;
            box-shadow: 0 4px 15px rgba(222, 165, 190, 0.2);
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }
        
        thead tr {
            background: linear-gradient(135deg, #f8c3cd, #e893b0);
            color: #5a2a40;
            text-align: left;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        th, td {
            padding: 14px 18px;
            border: none;
        }
        
        tbody tr {
            transition: all 0.3s ease;
        }
        
        tbody tr:nth-of-type(even) {
            background-color: #fff0f3;
        }
        
        tbody tr:hover {
            background-color: #fce4e9;
            transform: translateX(5px);
        }
        
        .in-stock {
            color: #b85c8d;
            font-weight: 700;
            font-style: italic;
        }
        
        .out-of-stock {
            color: #d35d6e;
            font-weight: 700;
            font-style: italic;
        }
        
        
        td:first-child {
            border-left: 3px solid #f8c3cd;
        }
        
        td:last-child {
            border-right: 3px solid #f8c3cd;
        }
        
        tbody tr:last-child td {
            border-bottom: 2px solid #f8c3cd;
        }
        
       
        td:nth-child(4) {
            font-family: 'PT Serif', serif;
            font-style: italic;
            color: #8e4162;
        }
    </style>

</head>
<body>
    <h1>Информация о товарах</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Категория</th>
                <th>Наличие</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= htmlspecialchars($product['product_description']) ?></td>
                <td><?= number_format($product['price'], 2, '.', ' ') ?> ₽</td>
                <td><?= htmlspecialchars($product['category_name']) ?></td>
                <td class="<?= $product['quantity'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
                    <?= $product['quantity'] > 0 ? 'В наличии (' . $product['quantity'] . ' шт.)' : 'Нет в наличии' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
