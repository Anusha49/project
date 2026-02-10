<?php
$hostname = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed");
}

if (isset($_POST['product_code'])) {
    $product_code = mysqli_real_escape_string($conn, $_POST['product_code']);
    
    $query = "SELECT product_name FROM productentry WHERE product_code = '$product_code'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['product_name'];
    } else {
        echo ""; // Return empty if not found
    }
}

$conn->close();
?>