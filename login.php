<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            background-color: blueviolet;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .form {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #5f3fb0;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .fieldset {
            border: 2px solid #fff;
            padding: 20px;
        }

        .legend {
            color: #fff;
            font-size: 1.5rem;
            text-align: center;
        }

        .adminPic {
            display: block;
            margin: 0 auto 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .username, .password {
            color: #fff;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="password"] {
            width: calc(100% - 10px);
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #fff;
            background-color: #7a54d9;
            color: #fff;
            border-radius: 5px;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #ddd;
        }

        .submit {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #fff;
            color: blueviolet;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }

        .submit:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

    <form class="form" action="insert.php" method="post" alignment="center">
        <fieldset class="fieldset">
            <legend class="legend">
               Admin Login
            </legend>
            <div>
                <img class="adminPic" src="admin.png" alt="Admin">
            </div>
            <div>
                <label class="username"><strong>Username</strong></label><br>
                <input type="text" id="username" name="username">
            </div>
            <div> 
                <label class="password"><strong>Password</strong></label><br>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <button class="submit" name="submit">Login</button>
            </div>
        </fieldset>
    </form>

</body>
</html>
