<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $password = $_POST['password'];

    $employee_id = mysqli_real_escape_string($conn, $employee_id);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM employeeregistration WHERE EmployeeID = '$employee_id' AND Password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['username'] = $row['FullName'];
        $_SESSION['employee_id'] = $row['EmployeeID'];

        header("Location: employee_Panel.php");
        exit();
    } else {
        echo "<script>alert('Invalid Employee ID or Password'); window.location.href='index.html';</script>";
    }
}
?>
