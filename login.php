<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.html");
   exit;
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = "yes";
            header("Location: index.html");
            exit;
        } else {
            $errorMessage = "Password does not match";
        }
    } else {
        $errorMessage = "Email does not match";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            background-image: url("images/banner1.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
/* ... Existing CSS styles ... */

.logo {
    position: absolute;
    top: -30px;
    left: 20px;
    width: 400px;
    height: 350px;
    background-image: url("images/logo.png");
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
}

        h2 {
            text-align: center;
            margin-top: 50px;
        }
        img {
            width: 400px;
        }
        form {
            width: 300px;
            background-color: grey;
            opacity: 0.8;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }
        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: none;
            background-color: #f2f2f2;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #70ebfc;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
        select {
            font-size: 1.0em;
            padding: 0.5em;
        }
        .sign-up {
            margin-top: 20px;
            text-align: center;
        }
        .sign-up a {
            text-decoration: none;
            color: #d82493;
        }
    </style>
</head>
<body>
    <div class="logo"></div>
    <form action="login.php" method="post">
        <h2>Sign In</h2>
        <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <?php if (isset($errorMessage)) : ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <input type="submit" value="Login" name="login" class="btn btn-primary">
        <div class="sign-up">
            <p>Not registered yet <a href="registration.php">Sign Up</a></p>
        </div>
    </form>
</body>
</html>
