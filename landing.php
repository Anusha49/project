<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Shop - Home Page</title>
    <link rel="stylesheet" href="landingStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <style>
        .marquee {
            color: white;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color:blueviolet;
            min-width: 120px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: left;
            z-index: 10;
        }
        .dropdown-content a {
            display: block;
            padding: 8px;
            text-decoration: none;
            color: black;
        }
        .dropdown-content a:hover {
            background-color: blueviolet;
            color:black;
        }
        .dropdown:hover .dropdown-content {
            display: block;
            color:blueviolet;
        }
        .availableProducts {
            color: blueviolet;
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
        }
        footer {
            background-color: blueviolet;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        .store-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .store-image {
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .product-frame {
            max-height: 400px;
            overflow: auto;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 20px auto;
            width: 80%;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: blueviolet;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-input {
            padding: 6px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-button {
            padding: 6px 10px;
            background-color: white;
            color: blueviolet;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
        }
        .search-button:hover {
            background-color: #ddd;
        }
    </style>
</head>

<body>
  <div class="div">
    <marquee class="marquee">Welcome to Smart Store</marquee>
    <p class="location">Kapan, Kathmandu</p>
  </div>

  <div id="navbar" class="navbar">
    <div>
      <a class="home" href="">Home</a>
      <a class="news" href="news.php">News</a>
      <a class="contact" href="">Contact</a>

      <div class="dropdown">
        <a href="#" class="login">Login</a>
        <div class="dropdown-content">
          <a href="login.php" target="_blank">Admin</a>
          <a href="employeeLogin.html" target="_blank">Employee</a>
        </div>
      </div>

      <a class="register" href="customerRegistration.html" target="_blank">Register</a>

    </div>

    <div class="search-container">
      <form method="GET" action="">
          <input type="text" name="search" class="search-input" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
          <button type="submit" class="search-button">Search</button>
      </form>
    </div>
  </div>
  <h2 class="availableProducts">Available Products</h2>

<div class="product-frame">
  <?php include "productForLanding.php"; ?>
</div>

  <div class="store-image-container">
    <img src="store_pic.jpeg" alt="Smart Shop" class="store-image">
  </div>

 

  <footer>
    <p>&copy; 2025 Smart Shop. All rights reserved.</p>
  </footer>

</body>
</html>
