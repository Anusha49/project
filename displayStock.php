<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$sql = "SELECT product_name,product_unit, sales_rate FROM productentry";
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
    <title>Stock Overview</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .summary { display: flex; justify-content: space-between; padding: 10px; background: #f8f8f8; margin-bottom: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: blueviolet; color: white; }
        tr:hover { background: #f1f1f1; }
    </style>
</head>
<body>

<div class="container">
    <h2>Stock Overview</h2>

    <div class="summary"><strong>Total Stock Value:</strong> Rs. <?php echo number_format($total_stock_value, 2); ?></div>

    <table>
        <tr><th>Item</th><th>Remaining Stock</th><th>Purchase Price</th><th>Stock Value</th></tr>
        <?php foreach ($products as $p) { ?>
            <tr>
                <td><?php echo $p["name"]; ?></td>
                <td><?php echo $p["stock"]; ?>piece</td>
                <td>Rs. <?php echo number_format($p["price"], 2); ?></td>
                <td>Rs. <?php echo number_format($p["value"], 2); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
