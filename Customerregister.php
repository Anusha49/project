<?php
$servername = "localhost";
$username = "root";
$password = "root";  // Default password in Laragon is empty
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Insert into the database
    $sql = "INSERT INTO customerInfo(first_name, last_name, email, password, phone, address) VALUES ('$first_name', '$last_name', '$email', '$password', '$phone', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: ");  // Redirect to login or product search page
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>