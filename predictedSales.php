<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Illegal access";
    exit();
}

// Full path to python.exe
$python = "C:\\Users\\Hp\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";

// Full path to app.py
$script = "C:\\laragon\\www\\project\\html\\app.py";

// Run Python script and capture output + errors
$output = shell_exec("\"$python\" \"$script\" 2>&1");

// Handle output safely
$prediction = $output ? trim($output) : "Prediction failed. Check Python setup.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Predicted Sales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            min-width: 350px;
        }
        h1 {
            color: blueviolet;
            margin-bottom: 20px;
        }
        .result {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        a {
            text-decoration: none;
            background: blueviolet;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }
        a:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Predicted Sales</h1>

    
    <!-- 👇 THIS IS WHERE THE VALUE SHOWS IN UI -->
    <div class="result">
        <?php echo $prediction; ?>
    </div>

    <a href="admin_panel.php">Back to Admin Panel</a>
</div>

</body>
</html>
