<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the search query if it exists
$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT product_name, sales_rate AS price, product_unit, image FROM productentry 
            WHERE product_name LIKE '%$searchQuery%' OR sales_rate LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT product_name, sales_rate AS price, product_unit, image FROM productentry";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-input {
            padding: 8px;
            width: 40%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-button {
            padding: 8px 15px;
            background-color: blueviolet;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .search-button:hover {
            background-color: darkviolet;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: blueviolet;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h1> Available Product List</h1>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="search" class="search-input" placeholder="Search for products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th> Product Name</th>
            <th>Price</th>
            <th>Unit</th> 
            <th>Image</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['product_name']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>{$row['product_unit']}</td>"; 
                echo "<td><img src='uploads/{$row['image']}' alt='Product Image'></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No products found</td></tr>"; 
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
