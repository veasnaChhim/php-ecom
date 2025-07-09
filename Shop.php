<?php
session_start();
include("includes/db.php");
include("nav.php");

// Fetch products from database
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(255, 255, 255);
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        h1, h2, h3 {
            color:rgb(26, 25, 25);
        }

        a {
            color:rgb(245, 237, 241);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            background-color:rgb(255, 255, 255);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color:rgb(255, 255, 255);
        }

        /* Product Grid */
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .product {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .product h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 10px 0 5px;
        }

        .product p {
            font-size: 14px;
            color: #666;
            margin: 5px 0 15px;
        }

        .add-btn {
    background-color: #0066cc;
    color: #ffffff;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.add-btn:hover {
    background-color: #004d99;
    transform: scale(1.03);
}


        /* Popup */
        #cartPopup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color:rgb(38, 87, 248);
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-size: 18px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Our Products</h2>

    <!-- Product Grid -->
    <div class="product-container">
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="product">
                <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>$<?= number_format($product['price'], 2) ?></p>
                <button class="add-btn" data-id="<?= $product['id'] ?>">Add to Cart</button>
            </div>
        <?php endwhile; ?>
        <?php
      // Dummy product list (simulate database)
      $products = [
        ['id' => 1, 'name' => 'Cotton T-Shirt', 'price' => 19.99, 'image' => 'img2.jpg'],
        ['id' => 2, 'name' => 'Classic Sneakers', 'price' => 49.99, 'image' => 'img10.jpg'],
        ['id' => 3, 'name' => 'Stylish Hoodie', 'price' => 39.99, 'image' => 'img4.jpg'],
        ['id' => 4, 'name' => 'Denim T-Shirt', 'price' => 59.99, 'image' => 'img3.jpg']
      ];

      foreach ($products as $product): ?>
        <div class="product">
          <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <h3><?= htmlspecialchars($product['name']) ?></h3>
          <p>$<?= number_format($product['price'], 2) ?></p>
          <button class="add-btn" data-id="<?= $product['id'] ?>">Add to Cart</button>
        </div>
      <?php endforeach; ?>
    </div>
</div>

<!-- ✅ Popup Message -->
<div id="cartPopup"> Item added to cart!</div>

<!-- ✅ JavaScript for AJAX Add to Cart + Popup -->
<script>
document.querySelectorAll('.add-btn').forEach(button => {
    button.addEventListener('click', function () {
        const productId = this.dataset.id;

        fetch(`cart.php?add=${productId}`)
            .then(res => res.text())
            .then(data => {
                const popup = document.getElementById('cartPopup');
                popup.style.display = 'block';
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 2000);
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>

</body>
</html>
