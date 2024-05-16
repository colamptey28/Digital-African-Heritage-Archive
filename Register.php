<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration functionality
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email_query = "SELECT * FROM users WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);

    if ($check_email_result->num_rows > 0) {
        $error = "Email already exists. Please choose a different email.";
    } else {
        // Insert user data into database
        $sql_insert = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$password')";
        if ($conn->query($sql_insert) === TRUE) {
            $_SESSION['registration_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Digital African Repository</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('Web-images/dasboard.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .btn-black {
            background-color: black;
            color: white;
        }

        .form-container {
            max-width: 400px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        /* Styles for the password toggle icon */
        .password-toggle-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-gray-900 text-white py-4">
        <div class="container mx-auto">
            <h1 class="text-center text-3xl">Digital African Heritage Archive</h1>
        </div>
    </header>
    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card form-container">
            <div class="card-body">
                <h2 class="card-title text-3xl font-bold">Registration</h2>
                <?php if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) : ?>
                    <div id="success-message" class="alert alert-success" role="alert">
                        Account successfully created! Please proceed to login.
                    </div>
                    <script>
                        // Hide success message after 8 seconds with fade-out effect
                        setTimeout(function() {
                            $("#success-message").fadeOut(1000);
                        }, 8000);
                    </script>
                <?php 
                    unset($_SESSION['registration_success']);
                endif; ?>
                <?php if (isset($error)) : ?>
                    <div id="error-message" class="alert alert-danger"><?php echo $error; ?></div>
                    <script>
                        // Hide error message after 10 seconds with fade-out effect
                        setTimeout(function() {
                            $("#error-message").fadeOut(1000);
                        }, 10000);
                    </script>
                <?php endif; ?>
                <form id="registrationForm" action="register.php" method="post" onsubmit="return validatePassword()">
                    <div class="form-group relative">
                        <input type="text" placeholder="Enter Full Name" name="fullname" class="form-control" required>
                    </div>
                    <div class="form-group relative">
                        <input type="email" placeholder="Enter Email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group relative">
                        <input type="password" id="password" placeholder="Enter Password" name="password" class="form-control" required>
                        <i class="fas fa-eye-slash password-toggle-icon" onclick="togglePassword()"></i> 
                    </div>
                    <div class="form-group relative">
                        <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" class="form-control" required>
                    </div>
                    <div class="form-btn">
                        <button type="submit" class="btn btn-black">Register</button>
                    </div>
                </form>
                <div class="mt-3">
                    <p>Already have an account? <a href="login.php">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Digital African Heritage Archive. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var uppercaseRegex = /[A-Z]/;
            var numberRegex = /[0-9]/;

            if (password.length < 8 || !uppercaseRegex.test(password) || !numberRegex.test(password)) {
                alert("Password must be at least 8 characters long and contain at least one uppercase letter and one number.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }

        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var icon = document.querySelector(".password-toggle-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
