<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Fetch user information from the session
$user_email = $_SESSION["email"];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user's full name and profile picture path from the database
$sql = "SELECT fullname, profile_picture FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_fullname = $row["fullname"];
    $profile_picture_path = $row["profile_picture"];
} else {
    $user_fullname = "Unknown"; // Default full name if not found in the database
    $profile_picture_path = "dash.jpeg"; // Default profile picture path
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Digital Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            background-image: url('Web-images/dasboard.jpeg'); 
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Set minimum height to ensure footer stays at bottom */
        }

        .btn-black {
        background-color: black;
        color: white; 
    }

        .card-link {
            position: relative;
        }

        .card-link .bg-cover {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .card-link .content {
            position: relative;
            z-index: 1;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); 
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #343a40; 
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
            margin-top: auto; 
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white py-4 flex items-center justify-between">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a class="text-white text-2xl font-bold" href="dashboard.php">Digital African Heritage Archive</a>
            <div class="hidden md:block">
            <a class="text-lg font-bold mr-4" href="dashboard.php">Home</a>
            <a class="text-lg font-bold mr-4" href="documents.php">Documents</a>
            <a class="text-lg font-bold mr-4" href="audios_main_section.php">Audios</a>
            <a class="text-lg font-bold mr-4" href="images_main_section.php">Images</a>
            <a class="text-lg font-bold mr-4" href="video_main_section.php">Videos</a>
            <a class="text-lg font-bold mr-4" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-8 flex-grow"> <!-- Use flex-grow to fill remaining space -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Profile and Upload Collection Card -->
<div class="md:col-span-1">
    <div class="bg-white rounded-lg shadow-md p-10 relative"> <!-- Increased padding and added relative positioning -->
        <!-- Profile Section -->
        <div class="flex flex-col items-center justify-center space-y-8"> <!-- Increased spacing -->
            <!-- User Feedback Icon -->
            <a href="user_complaints.php" class="absolute top-0 right-0 mt-2 mr-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700 cursor-pointer" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 12c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2v-1l-4-4H4a2 2 0 0 0-2 2zm14-7h-4l-2-2H6a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zm-7 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
                <span class="text-gray-500 ml-2 text-sm font-semibold hover:text-gray-700 cursor-pointer">User Complaints</span>
            </a>
            <!-- Profile Picture -->
            <div class="w-48 h-48 bg-cover bg-center bg-no-repeat rounded-full border-4 border-blue-500 overflow-hidden mx-auto"> <!-- Increased size of profile picture -->
                <img src="<?php echo $profile_picture_path; ?>" alt="Profile Picture">
            </div>
            <!-- User Name and Email -->
            <div class="text-center">
                <h5 class="text-xl font-semibold mb-4 text-gray-800"><?php echo $user_fullname; ?></h5> <!-- Increased margin bottom -->
                <p class="text-gray-600 text-sm"><?php echo $user_email; ?></p>
                <a href="edit_profile.php" class="block w-full py-3 px-6 bg-black text-white rounded-md mt-6 hover:bg-gray-800">Edit Profile</a>
            </div>
        </div>
    </div>
</div>



           <!-- Other sections of the dashboard -->
    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8 pl-8 pr-8 mb-8">
    <!-- Documents -->
    <a href="documents.php" class="card-link">
        <div class="bg-white rounded-lg shadow-md relative overflow-hidden">
            <img class="w-full h-48 object-cover" src="Web-images/Document_Pic.jpg" alt="Documents"> <!-- Adjusted height -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 bg-black bg-opacity-50 transition duration-300 opacity-0 hover:opacity-100">
                <h5 class="text-xl font-semibold mb-2 text-white">Documents</h5>
                <p class="text-gray-200 text-sm">Access and upload documents related to African history,
                    culture, and more.</p>
            </div>
        </div>
    </a>
    <!-- Audios -->
    <a href="audios_main_section.php" class="card-link">
        <div class="bg-white rounded-lg shadow-md relative overflow-hidden">
            <img class="w-full h-48 object-cover" src="Web-images/Audio_Pic.jpg" alt="Audios"> <!-- Adjusted height -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 bg-black bg-opacity-50 transition duration-300 opacity-0 hover:opacity-100">
                <h5 class="text-xl font-semibold mb-2 text-white">Audios</h5>
                <p class="text-gray-200 text-sm">Listen to audio files, including music, speeches, and
                    interviews.</p>
            </div>
        </div>
    </a>
    <!-- Images -->
    <a href="images_main_section.php" class="card-link">
        <div class="bg-white rounded-lg shadow-md relative overflow-hidden">
            <img class="w-full h-48 object-cover" src="Web-images/Img_Pic.jpg" alt="Images"> <!-- Adjusted height -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 bg-black bg-opacity-50 transition duration-300 opacity-0 hover:opacity-100">
                <h5 class="text-xl font-semibold mb-2 text-white">Images</h5>
                <p class="text-gray-200 text-sm">Explore a collection of images showcasing African heritage.</p>
            </div>
        </div>
    </a>
    <!-- Videos -->
    <a href="video_main_section.php" class="card-link">
        <div class="bg-white rounded-lg shadow-md relative overflow-hidden">
            <img class="w-full h-48 object-cover" src="Web-images/video.gif" alt="Videos"> <!-- Adjusted height -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 bg-black bg-opacity-50 transition duration-300 opacity-0 hover:opacity-100">
                <h5 class="text-xl font-semibold mb-2 text-white">Videos</h5>
                <p class="text-gray-200 text-sm">Watch videos covering various aspects of African history,
                    culture, and more.</p>
            </div>
        </div>
    </a>
    <!-- Map -->
    <a href="Map.php" class="card-link">
        <div class="bg-white rounded-lg shadow-md relative overflow-hidden">
            <img class="w-full h-48 object-cover" src="Web-images/map_image.jpg" alt="Map"> <!-- Adjusted height -->
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 bg-black bg-opacity-50 transition duration-300 opacity-0 hover:opacity-100">
                <h5 class="text-xl font-semibold mb-2 text-white">Map</h5>
                <p class="text-gray-200 text-sm">Interact with our map here and more.</p>
            </div>
        </div>
    </a>
</div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
        <div class="container mx-auto">
            <p>&copy; Digital African Heritage Archive. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-jhcdyObA1ZRiy+fq6c6Zz1y1qEc4fV4fJW/casTjZx5g1V+Byn8J7AZka7+3HrcI"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em"
        crossorigin="anonymous"></script>
</body>

</html>
