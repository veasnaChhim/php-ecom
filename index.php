<?php
include("includes/db.php");
include("nav.php");
?>
<?php
// Dummy product list (simulate database)
$products = [
    ['id' => 1, 'name' => 'Cotton T-Shirt', 'price' => 19.99, 'image' => 'img2.jpg'],
    ['id' => 2, 'name' => 'Classic Sneakers', 'price' => 49.99, 'image' => 'img10.jpg'],
    ['id' => 3, 'name' => 'Stylish Hoodie', 'price' => 39.99, 'image' => 'img4.jpg'],
    ['id' => 4, 'name' => 'Denim T-Shirt', 'price' => 59.99, 'image' => 'img3.jpg']
];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Home - My Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>

<body>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            transition: box-shadow 0.3s;
            background: #fff;
        }

        .product-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .product-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
        }

        h3 {
            margin: 10px 0;
            color: #333;
        }

        p {
            color: rgb(41, 39, 40);
            font-size: 16px;
        }

        .btn-buy {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #0066cc;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-buy:hover {
            background: #004d99;
        }
    </style>
    <div class="container">
        <div class="slider">
            <div class="slides">
                <img src="images/slide1.png" alt="Slide 1">
                <img src="images/slide2.jpg" alt="Slide 2">
                <img src="images/slide3.jpg" alt="Slide 3">
                <img src="images/slide4.jpg" alt="Slide 4">
            </div>
        </div>
        <!-- Product Section -->
        <h2 class="mt-5">Best Products</h2>
        <div class="product-grid">
            <?php
            $query = "SELECT * FROM products LIMIT 4";
            $result = mysqli_query($conn, $query);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $image = $row['image'] ?? 'img1.jpg';
                    $name = $row['name'] ?? 'Unnamed Product';
                    $price = $row['price'] ?? '0.00';
                    $id = $row['id'] ?? '#';

                    echo '
            <div class="product-card">
                <img src="images/' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '" class="product-img">
                <h3>' . htmlspecialchars($name) . '</h3>
                <p>$' . htmlspecialchars($price) . '</p>
                <a href="checkout.php?id=' . htmlspecialchars($id) . '" class="btn btn-buy">Buy Now</a>
            </div>';
                }
            } else {
                echo "Error fetching products: " . mysqli_error($conn);
            }
            ?>
        </div>
    </div>
</body>

</html>