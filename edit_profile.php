<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file is uploaded successfully
    if (isset($_FILES["profile-picture"]) && $_FILES["profile-picture"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "profile_pictures/"; // Directory where profile pictures will be stored
        $target_file = $target_dir . basename($_FILES["profile-picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile-picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            echo "File is not an image.";
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
            echo "Sorry, file already exists.";
        }

        // Check file size
        if ($_FILES["profile-picture"]["size"] > 500000) {
            $uploadOk = 0;
            echo "Sorry, your file is too large.";
        }

        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $uploadOk = 0;
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // If everything is ok, try to upload file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $target_file)) {
                // Update profile picture path in database
                $email = $_SESSION['email'];
                $profile_picture_path = $target_file;
                $sql = "UPDATE users SET profile_picture = '$profile_picture_path' WHERE email = '$email'";
                if ($conn->query($sql) === TRUE) {
                    $_SESSION['profile_updated'] = true;
                } else {
                    echo "Error updating record: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('Web-images/dasboard.jpeg');
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <button onclick="goBack()" class="text-white focus:outline-none">
                <svg class="h-6 w-6 fill-current" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.707 10.293a1 1 0 0 1 0-1.414L9.586 4.7a1 1 0 1 1 1.414 1.414L7.414 10l3.586 3.586a1 1 0 1 1-1.414 1.414L4.707 11.707a1 1 0 0 1 0-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
            <h1 class="text-2xl font-bold">Edit Profile</h1>
            <div></div>
        </div>
    </header>
    <!-- Main Content -->
    <div class="container mx-auto mt-8">
        <div class="max-w-md mx-auto bg-white rounded-md shadow-md overflow-hidden">
            <div class="p-6">
                <?php if(isset($_SESSION['profile_updated']) && $_SESSION['profile_updated']): ?>
                <div class="alert alert-success mb-4" id="success-message" role="alert">
                    Profile updated successfully.
                </div>
                <script>
                    setTimeout(function () {
                        document.getElementById('success-message').style.display = 'none';
                    }, 8000);
                </script>
                <?php 
                    unset($_SESSION['profile_updated']);
                    endif;
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile-picture" class="block text-sm font-medium text-gray-700">Profile Picture:</label>
                        <input type="file" id="profile-picture" name="profile-picture" class="mt-1 py-1 px-3 block w-full border border-gray-300 shadow-sm focus:outline-none focus:border-blue-500 rounded-md">
                    </div>
                    <button type="submit" class="block w-full py-3 px-6 bg-black text-white rounded-md mt-6 hover:bg-gray-800">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 mt-auto">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Digital African Heritage Archive. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Function to go back to the previous page
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
