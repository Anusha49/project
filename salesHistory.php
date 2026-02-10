<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #ffffff;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #682ae9;
            color: #ffffff;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<h2>Products Table</h2>

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT  customer_name, phone_number,product_id,  product_name, quantity, date,sales_rate FROM goodsout";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Sales Rate</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
            
                <td>" . $row["customer_name"] . "</td>
                <td>" . $row["phone_number"] . "</td>
                <td>" . $row["product_id"] . "</td>
                 <td>" . $row["product_name"] . "</td>
                 <td>" . $row["sales_rate"] . "</td>
                <td>" . $row["quantity"] . "</td>
                <td>" . $row["date"] . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "0 results";
}

$conn->close();
?>

</body>
</html>