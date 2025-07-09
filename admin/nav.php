<!-- nav.php -->
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
        background-color: #343a40;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand {
        font-size: 1.6rem;
        font-weight: bold;
    }

    .navbar-links {
        display: flex;
        gap: 25px;
    }

    .navbar-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .navbar-links a:hover {
        color:rgb(11, 88, 99);
    }

    .hamburger {
        display: none;
        flex-direction: column;
        cursor: pointer;
        gap: 5px;
    }

    .hamburger span {
        display: block;
        width: 26px;
        height: 3px;
        background-color: white;
        transition: all 0.3s ease-in-out;
    }

    @media (max-width: 768px) {
        .hamburger {
            display: flex;
        }

        .navbar-links {
            position: absolute;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: #343a40;
            flex-direction: column;
            display: none;
            padding: 1rem 2rem;
            gap: 15px;
        }

        .navbar-links.show {
            display: flex;
            animation: slideDown 0.3s ease-in-out forwards;
        }

        @keyframes slideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    .nav-space {
        height: 70px;
    }
</style>

<div class="navbar">
    <div class="navbar-brand">Admin Panel</div>

    <div class="hamburger" onclick="toggleMenu(this)">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="navbar-links" id="navbarLinks">
        <a href="dashboard.php">Dashboard</a>
        <a href="users.php">Users</a>
        <a href="products.php">Products</a>
        <a href="orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="nav-space"></div>

<script>
    function toggleMenu(button) {
        const links = document.getElementById("navbarLinks");
        links.classList.toggle("show");
    }
</script>
