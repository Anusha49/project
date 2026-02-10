<?php
$servername="localhost";
$username="root";
$password="root";
$dbname="my_database";
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    echo"connection failed".$conn->connect_error;
}
if(isset($_GET['product_code'])){
    $product_code=$_GET['product_code'];
}
 $sql="DELETE FROM productEntry WHERE product_code=?";

 $stmt = $conn->prepare($sql);
 $stmt->bind_param("s", $product_code);

 if ($stmt->execute()) {
     echo "Record deleted successfully.";
 } else {
     echo "Error deleting record: " . $conn->error;
 }

 $stmt->close();
$conn->close();
header("Location: viewProduct.php");
exit();

?>
