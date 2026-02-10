<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM customers WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission to update user info
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update user info in the database
    $update_sql = "UPDATE customers SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', address='$address' WHERE id = $user_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Account updated successfully.";
    } else {
        echo "Error updating account: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!-- Account Edit Form (HTML + CSS) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="account-form">
        <h2>Edit Your Account Information</h2>
        <form method="POST" action="account_edit.php">
            <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required><br><br>
            <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required><br><br>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>"><br><br>
            <textarea name="address" required><?php echo $user['address']; ?></textarea><br><br>
            <button type="submit">Update Account</button>
        </form>
    </div>
</body>
</html>
