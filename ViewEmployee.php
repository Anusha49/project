<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM employeeregistration"; // Query to fetch all data from the employee table
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
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
            background: #007bff;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .no-results {
            text-align: center;
            color: #999;
            margin: 20px;
        }
        .dropdown-content a{
            color:black;
            display: block;
            text-decoration: none;
        }
        .dropdown-content a:hover{
            background-color:#ddd;
        }
        .dropdownbtn{
            border:none; /*remove border of button*/
            background: none; /*remove background color of button*/
            cursor:pointer;
    

        }
        .dropdown:hover .dropdown-content {display : block;}
        .dropdown:hover{
            
            color:blueviolet;
            }
            .dropdown-content{
                display:none;
                padding:12px;
            }
    </style>
</head>
<body>
    <h1>Employee List</h1>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Full Name</th>
                <th>DOB</th>
                <th>Password</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Work Experience</th>
                <th>Photo</th>
                <th>Registration Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                
                    <td><?php echo htmlspecialchars($row["EmployeeID"]); ?></td>
                    <td><?php echo htmlspecialchars($row["FullName"]); ?></td>
                    <td><?php echo htmlspecialchars($row["DOB"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Password"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Gender"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["PhoneNumber"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Address"]); ?></td>
                    <td><?php echo htmlspecialchars($row["WorkExperience"]); ?></td>
                    <td>
                        <?php if (!empty($row["Photo"])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['Photo']); ?>" alt="Employee Photo">
                        <?php else: ?>
                            No Photo
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["RegistrationDate"]); ?></td>
                    <td> <div class="dropdown">
                            <button class="dropdownbtn">⚙️ </button>
                            <div class="dropdown-content">
                                <a href="updateEmployee.php">Update</a>
                                <a href="deleteEmployee.php">Delete</a>
                            </div>

                    </div>
                </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-results">No employees found.</p>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
</html>
