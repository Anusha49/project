<?php
$hostname = "localhost";
$username = "root";
$password = "root";
$dbname   = "my_database";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ✅ CHECK REQUIRED FIELDS */
    if (
        isset(
            $_POST['customer_name'],
            $_POST['customer_no'],
            $_POST['product_name'],
            $_POST['product_code'],
            $_POST['product_quantity'],
            $_POST['product_unit'],
            $_POST['rate'],
            $_POST['date']
        )
    ) {

        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $phone_number  = mysqli_real_escape_string($conn, $_POST['customer_no']);
        $product_name  = mysqli_real_escape_string($conn, $_POST['product_name']);
        $product_code  = mysqli_real_escape_string($conn, $_POST['product_code']);
        $out_qty       = (int) $_POST['product_quantity'];
        $unit          = mysqli_real_escape_string($conn, $_POST['product_unit']);
        $rate          = (float) $_POST['rate'];
        $date          = mysqli_real_escape_string($conn, $_POST['date']);

        /* ❌ INVALID QUANTITY */
        if ($out_qty <= 0) {
            echo "Invalid quantity!";
            exit;
        }

        /* 1️⃣ CHECK AVAILABLE STOCK - Using product_unit column which stores quantity */
        $stockQuery = "SELECT product_unit FROM productentry WHERE product_code = '$product_code'";
        $stockResult = $conn->query($stockQuery);

        if ($stockResult->num_rows == 0) {
            echo "Product not found!";
            exit;
        }

        $row = $stockResult->fetch_assoc();
        $available_qty = (int) $row['product_unit'];

        if ($available_qty < $out_qty) {
            echo "Not enough stock available! Only {$available_qty} units left.";
            exit;
        }

        /* 2️⃣ START TRANSACTION */
        $conn->begin_transaction();

        try {

            /* 3️⃣ INSERT INTO GOODS OUT with sales_rate for ML prediction */
            $insertSql = "INSERT INTO goodsout
                (customer_name, phone_number, product_name, product_id, quantity, unit, sales_rate, date)
                VALUES
                ('$customer_name', '$phone_number', '$product_name', '$product_code', '$out_qty', '$unit', '$rate', '$date')";

            if (!$conn->query($insertSql)) {
                throw new Exception("Insert failed: " . $conn->error);
            }

            /* 4️⃣ UPDATE STOCK - Decrease product_unit */
            $updateSql = "UPDATE productentry
                          SET product_unit = product_unit - $out_qty
                          WHERE product_code = '$product_code'";

            if (!$conn->query($updateSql)) {
                throw new Exception("Update failed: " . $conn->error);
            }

            /* 5️⃣ COMMIT */
            $conn->commit();

            $remaining = $available_qty - $out_qty;
            echo "✅ Success! Transaction completed.<br>";
            echo "Product: {$product_name}<br>";
            echo "Sold: {$out_qty} units @ {$rate} per unit<br>";
            echo "Remaining stock: {$remaining} units";
            
            // Redirect back to form after 3 seconds
            header("refresh:3;url=goodsOutForm.html");

        } catch (Exception $e) {
            $conn->rollback();
            echo "❌ Transaction failed! Error: " . $e->getMessage();
        }

    } else {
        echo "Please fill all required fields!";
    }
}

$conn->close();
?>