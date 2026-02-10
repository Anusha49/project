<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .searchbar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .searchbtn {
            padding: 10px 15px;
            background-color:blueviolet;
            color: white;
            border: none;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .searchbtn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background:blueviolet;
            color: white;
            tex
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .updatebtn, .deletebtn {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .updatebtn {
            background-color: #28a745;
            color: #fff;
        }
        .deletebtn {
            background-color: #dc3545;
            color: #fff;
        }
        .updatebtn:hover {
            background-color: #218838;
        }
        .deletebtn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    
    <!-- Search Bar -->
    <div class="search-container">
        <form method="GET" action="">
            <input 
                class="searchbar" 
                type="text" 
                name="search" 
                placeholder="Search by name or type..." 
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            >
            <button class="searchbtn" type="submit">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Product Type</th>
            <th>Product Unit</th>
            <th>Sales Rate</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php
        
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "my_database";
        
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

       
        $sql = "SELECT product_code, product_name, product_type, product_unit, sales_rate, image 
                FROM productentry 
                WHERE product_name LIKE '%$search%' 
                   OR product_type LIKE '%$search%'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['product_code']}</td>";
                echo "<td>{$row['product_name']}</td>";
                echo "<td>{$row['product_type']}</td>";
                echo "<td>{$row['product_unit']}</td>";
                echo "<td>{$row['sales_rate']}</td>";
                echo "<td><img src='uploads/{$row['image']}' alt='Product Image'></td>";
                echo "<td class='action-buttons'>
                        <a href='updateProductEntry.html?product_code={$row['product_code']}' class='updatebtn'>Update</a>
                        <a href='delete.php?product_code={$row['product_code']}' class='deletebtn' onclick=\"return confirm('Are you sure?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No products found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
