<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "root";
$database = "my_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$customer_name = $_POST['customer_name'];
$customer_phone = $_POST['customer_phone'];
$product_name = $_POST['product_name'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];
$rate = $_POST['rate'];
$unit = $_POST['unit'];
$date = $_POST['date'];

// Calculate the total price
$total_price = $quantity * $rate;

// Insert data into the database
$sql = "INSERT INTO goodsout (customer_name, customer_phone, product_name, product_id, quantity, rate, unit, total_price, date)
        VALUES ('$customer_name', '$customer_phone', '$product_name', '$product_id', $quantity, $rate, '$unit', $total_price, '$date')";

if ($conn->query($sql) === TRUE) {
    echo "<div class='message'>Record added successfully!</div>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
