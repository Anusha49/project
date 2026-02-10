<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fname'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $workExperience = $_POST['workexperience'];

    // Handling file upload
    $photoPath = NULL; // Default to NULL if no photo is uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photoName = $_FILES['photo']['name'];
        $targetDirectory = "uploads/"; // Directory where photos will be saved
        $targetFile = $targetDirectory . basename($photoName);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photoPath = $targetFile; // Store the file path for database insertion
        } else {
            echo "Failed to upload photo.";
        }
    }

    // Insert data into the database including the photo path
    $sql = "INSERT INTO employeeregistration (FullName, DOB, Password, Gender, Email, PhoneNumber, Address, WorkExperience, Photo) 
            VALUES ('$fullName', '$dob', '$password', '$gender', '$email', '$phoneNumber', '$address', '$workExperience', '$photoPath')";

    if ($conn->query($sql) === TRUE) {
        echo "New employee record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
