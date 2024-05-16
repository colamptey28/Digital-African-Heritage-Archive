<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Fetch user email from the session
$user_email = $_SESSION["email"];

//Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "digital_heritage_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Alert message
$alert_message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $complaint = $conn->real_escape_string($_POST['complaint']);

    // Prepare insert statement
    $sql = "INSERT INTO user_complaints (username, email, complaint) VALUES ('$fullname', '$user_email', '$complaint')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $alert_message = "Complaint successfully submitted!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Alert styles */
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            z-index: 9999;
            display: none; /* Hidden by default */
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('Web-images/dasboard.jpeg');
        }

        /* Flexbox styles for main content */
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main {
            flex-grow: 1;
        }

    </style>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
        <a href="dashboard.php" class="text-lg font-bold">&#9664; Back</a>
            <h1 class="text-2xl font-semibold">Complaint Form</h1>
            <div></div> <!-- Placeholder for alignment -->
        </div>
    </header>

    <div class="wrapper">
        <div class="container mx-auto mt-8 main">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-md shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">User Complaints Form</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="mb-4">
                            <label for="fullname" class="block text-gray-700 font-bold mb-2">Full Name:</label>
                            <input type="text" id="fullname" name="fullname" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $user_email; ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="complaint" class="block text-gray-700 font-bold mb-2">Complaint:</label>
                            <textarea id="complaint" name="complaint" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                        </div>
                        <button type="submit" class="block w-full py-3 px-6 bg-black text-white rounded-md mt-6 hover:bg-gray-800">Submit Complaint</button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
            <div class="container mx-auto">
                <p>&copy; Digital African Heritage Archive. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Alert message -->
    <div id="alert" class="alert"><?php echo $alert_message; ?></div>

    <script>
        // Display alert message for 6 seconds and then fade away
        var alertBox = document.getElementById('alert');
        if (alertBox.innerHTML.trim() !== '') {
            alertBox.style.display = 'block';
            setTimeout(function () {
                alertBox.style.display = 'none';
            }, 6000);
        }
    </script>
</body>

</html>
