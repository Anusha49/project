<?php
$servername="localhost";
$username="root";
$password="root";
$dbname="my_database";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}
if($_SERVER['REQUEST_METHOD']=='POST'){
$productCode = $_POST['productcode'];
$productName= $_POST['productname'];
$productType= $_POST['producttype'];
$productUnit= $_POST['productunit'];
$salesRate= $_POST['salesrate'];


    if (isset($_FILES['uploadphoto']) && $_FILES['uploadphoto']['error'] == 0) {
        $targetDir = "uploads/";
        $photo = basename($_FILES["uploadphoto"]["name"]);
        $targetFilePath = $targetDir . $photo;
        move_uploaded_file($_FILES["uploadphoto"]["tmp_name"], $targetFilePath);
    } else {
        $photo = NULL;
    }

   
    $checkSql = "SELECT * FROM productentry WHERE product_code = '$productCode'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
    
        $updateSql = "UPDATE productentry 
                      SET product_name = '$productName', 
                          product_type = '$productType', 
                          product_unit = '$productUnit', 
                          sales_rate = '$salesRate'";
        
       
        if ($photo) {
            $updateSql .= ", image = '$photo'";
        }

        $updateSql .= " WHERE product_code = '$productCode'";

        if ($conn->query($updateSql) === TRUE) {
            echo "Record updated successfully!";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // If product_code does not exist, insert a new record
        $insertSql = "INSERT INTO productentry (product_code, product_name, product_type, product_unit, sales_rate, image)
                      VALUES ('$productCode', '$productName', '$productType', '$productUnit', '$salesRate', '$photo')";

        if ($conn->query($insertSql) === TRUE) {
            echo "New record created successfully!";
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }
    }
}
header("Location:ViewProduct.php");
?>
    
