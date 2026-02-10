<?php
//connecting to database
$servername="localhost";
$username="root";
$password="root";
$dbname="my_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection failed:".$connect_error);
}
 
if($_SERVER['REQUEST_METHOD']=='POST'){
$productCode = $_POST['productcode'];
$productName= $_POST['productname'];
$productType= $_POST['producttype'];
$productUnit= $_POST['productunit'];
$salesRate= $_POST['salesrate'];
$photo = $_FILES['uploadphoto'];
}
  // Handle file upload
  if (isset($_FILES['uploadphoto']) && $_FILES['uploadphoto']['error'] == 0) {
    $targetDir = "uploads/";
    $photo = basename($_FILES["uploadphoto"]["name"]);
    $targetFilePath = $targetDir . $photo;
    move_uploaded_file($_FILES["uploadphoto"]["tmp_name"], $targetFilePath);
} else {
    $photo = NULL;  
}

// Inserting data into the database
$sql = "INSERT INTO productentry (product_code, product_name, product_type, product_unit, sales_rate,image)
        VALUES ('$productCode', '$productName', '$productType', '$productUnit', '$salesRate', '$photo')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


include "fetchdata.php";

?>


