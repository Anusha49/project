
<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Simple query to get user data
    $sql = "SELECT * FROM user WHERE username = '$inputUsername'";
    $result = $conn->query($sql);

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check password (assumes password is stored in plain text)
        if ($inputPassword == $row['password']) {
            $_SESSION['username'] = $inputUsername;
            header("Location: admin_panel.php"); // Redirect to admin panel
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
}

// Close the connection

?>
