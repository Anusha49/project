<?php
$conn = new mysqli("localhost", "root", "root", "my_database");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM customerInfo");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer List</title>
</head>
<body>
    <h2>Customer Information</h2>
    <table border="1">
        <tr>
            <th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Address</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row["first_name"]) ?></td>
            <td><?= htmlspecialchars($row["last_name"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["phone"]) ?></td>
            <td><?= htmlspecialchars($row["address"]) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
