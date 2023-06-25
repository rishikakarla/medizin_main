<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.html");
   exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .logo {
            position: absolute;
            top: 30px;
            left: 20px;
            width: 400px;
            height: 200px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
        }

        form {
            width: 300px;
            background-color: grey;
            opacity: 0.8;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: none;
            background-color: #f2f2f2;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            background-color: #e0e0e0;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #34e7ff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        .sign-up {
            margin-top: 20px;
            text-align: center;
        }

        .sign-up a {
            text-decoration: none;
            color: #df31c2;
            font-weight: bold;
        }

        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

       
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.png" alt="Logo" class="logo">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
 
            $errors = array();
            
            if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
             array_push($errors,"All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             array_push($errors, "Email is not valid");
            }
            if (strlen($password)<8) {
             array_push($errors,"Password must be at least 8 characters long");
            }
            if ($password!==$passwordRepeat) {
             array_push($errors,"Password does not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount>0) {
             array_push($errors,"Email already exists!");
            }
            if (count($errors)>0) {
             foreach ($errors as  $error) {
                 echo "<div class='alert alert-danger'>$error</div>";
             }
            } else {
             $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
             $stmt = mysqli_stmt_init($conn);
             $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
             if ($prepareStmt) {
                 mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                 mysqli_stmt_execute($stmt);
                 echo "<div class='alert alert-success'>You are registered successfully.</div>";
             } else {
                 die("Something went wrong");
             }
            }
        }
        ?>
        <form action="registration.php" method="post">
            <h2>Sign Up</h2>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
            <div class="sign-up">
                <p>Already registered? <a href="login.php">Sign In</a></p>
            </div>
        </form>
    </div>

</body>
</html>
