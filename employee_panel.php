<?php 
session_start();
if (!isset($_SESSION['username'])) {
    echo "Illegal access";
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$sql = "SELECT product_name, product_unit, sales_rate FROM productentry";
$result = mysqli_query($conn, $sql);

$total_stock = $total_stock_value = 0;
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $stock_value = $row["product_unit"] * $row["sales_rate"];
    $products[] = [
        "name" => $row["product_name"],
        "stock" => $row["product_unit"],
        "price" => $row["sales_rate"],
        "value" => $stock_value
    ];
    $total_stock += $row["product_unit"];
    $total_stock_value += $stock_value;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background-color: #6a0dad;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #ffdd00;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 20px;
            flex-grow: 1;
        }
        .box {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
            transition: transform 0.3s ease;
            text-decoration: none;
        }
        .box:hover {
            transform: scale(1.05);
            text-decoration: none;
        }
        .box img {
            width: 150px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .footer {
            background-color: #333;
            color: #ddd;
            padding: 10px;
            text-align: center;
            font-size: 0.9rem;
        }
        .footer a {
            color: #ffdd00;
            text-decoration: none;
            font-weight: bold;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: blueviolet; color: white; }
        tr:hover { background: #f1f1f1; }
    </style>
</head>
<body>

    <div class="navbar">
        <h4><marquee>Welcome to Employee Panel</marquee></h4>
        <a class="logout" href="logout.php">Log Out</a>
    </div>

    <div class="container">
        <div class="box">
            <img src="bg.jpg" alt="New Product">
            <h1>New Product</h1>
            <p><a href="productEntry.html">Entry Product?</a></p>
            <p><a href="viewProduct.php">View Product</a></p>
            <p><a href="goodsOut.html">Goods Out?</a></p>

        </div>
        <div class="box">
            <img src="employee_registration.jpg" alt="Register Employee">
            <h1>Employee Registration</h1>
            <p><a href="ViewEmployee.php">View Employee</a></p>
        </div>
        <div class="box">
            <img src="customer.jpg" alt="Customer Registration">
            <h1>Customer Registration</h1>
            <p><a href="customerRegistration.html">Register Customer?</a></p>
            <p><a href="fetchCustomerInfo.php">View Customer</a></p>
        </div>
    </div>

    <div class="container">
        <h2>Stock Overview</h2>
        <div class="summary"><strong>Total Stock Value:</strong> Rs. <?php echo number_format($total_stock_value, 2); ?></div>
        <table>
            <tr><th>Item</th><th>Remaining Stock</th><th>Purchase Price</th><th>Stock Value</th></tr>
            <?php foreach ($products as $p) { ?>
                <tr>
                    <td><?php echo $p["name"]; ?></td>
                    <td><?php echo $p["stock"]; ?> piece</td>
                    <td>Rs. <?php echo number_format($p["price"], 2); ?></td>
                    <td>Rs. <?php echo number_format($p["value"], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="footer">
        <p>&copy; 2024 Employee Panel | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
    </div>

</body>
</html>
