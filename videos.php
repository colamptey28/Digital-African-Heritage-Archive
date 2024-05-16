<?php
session_start();


// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}


// Function to sanitize output to prevent XSS attacks
function sanitize_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Function to truncate string
function truncate_string($string, $length) {
    if (strlen($string) > $length) {
        $string = substr($string, 0, $length) . '...';
    }
    return $string;
}

// Check if user is logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$results_per_page = 10; // Number of results per page

// Fetch total number of videos
$sql_total = "SELECT COUNT(*) AS total FROM videos";
$result_total = $conn->query($sql_total);

// Check for errors in database operation
if (!$result_total) {
    die("Error retrieving total number of videos: " . $conn->error);
}

// Fetch total number of videos
$row_total = $result_total->fetch_assoc();
$total_results = $row_total['total'];
$total_pages = ceil($total_results / $results_per_page); // Total number of pages

// Determine current page number
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Validate current page number
if ($page < 1 || $page > $total_pages) {
    $page = 1; // Set page to 1 if it's out of range
}

// Calculate SQL LIMIT starting number for the results on the displaying page
$this_page_first_result = ($page - 1) * $results_per_page;

// Fetch videos for the current page
if(isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT id, title, description, filepath FROM videos WHERE title LIKE '%$search%' LIMIT $this_page_first_result, $results_per_page";
} else {
    $sql = "SELECT id, title, description, filepath FROM videos LIMIT $this_page_first_result, $results_per_page";
}
$result = $conn->query($sql);

// Check for errors in database operation
if (!$result) {
    die("Error retrieving videos: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Collection - Digital African Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('Web-images/dasboard.jpeg');
        }

        .search {
            margin: 1rem auto;
            text-align: center;
        }

        .pagination {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .video-wrapper {
            max-width: 100%;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-playlist {
            max-height: 600px;
        }

        .video-item {
            cursor: pointer;
        }

        .video-item:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <div class="bg-gray-900 text-white py-4 flex items-center justify-between">
            <a href="video_main_section.php" class="text-lg font-bold">&#9664; Back</a>
            <h1 class="text-3xl font-bold">African Leaders Collection</h1>
            <div class="ml-4"></div>
        </div>

        <!-- Search Bar -->
        <div class="search py-4 px-4">
            <form id="searchForm" class="flex items-center justify-center">
                <input type="text" id="searchInput" name="search" placeholder="Search..." class="px-6 py-4 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 w-full max-w-md">
                <button type="submit" class="btn btn-primary ml-4 px-6 py-4 rounded-md text-white font-bold">Search</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="content container mx-auto py-8" id="videoPlaylist">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="video-wrapper">
                    <video id="video" src="#" controls class="w-full h-72 object-cover rounded-lg shadow-lg"></video>
                </div>
                <div class="video-playlist overflow-y-auto">
                    <!-- Playlist Cards -->
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <div class="flex items-center border rounded-lg bg-white cursor-pointer mb-4 p-4 transition duration-200 hover:bg-gray-100 video-item" onclick="playVideo('<?php echo sanitize_output($row['filepath']); ?>')">
                            <div class="video-img w-24 h-16 rounded-lg overflow-hidden mr-4">
                                <video src="<?php echo sanitize_output($row['filepath']); ?>" class="w-full h-full object-cover" type="video/mp4"></video>
                            </div>
                            <div class="video-details">
                                <h4 class="text-lg font-semibold mb-1 text-gray-900 video-title">Title: <?php echo sanitize_output($row['title']); ?></h4>
                                <p class="text-gray-600">Description: <?php echo truncate_string(sanitize_output($row['description']), 100); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            <ul class="pagination flex space-x-2">
                <!-- Pagination links -->
            </ul>
        </div>

        <!-- Footer -->
        <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
            <div class="container mx-auto">
                <p>&copy; Digital African Heritage Archive. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script>
        const video = document.getElementById("video");

        function playVideo(videoSource) {
            video.src = videoSource;
            video.play();
        }

        const searchForm = document.getElementById("searchForm");
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(searchForm);
            const searchQuery = formData.get("search");

            window.location.href = `<?php echo $_SERVER['PHP_SELF']; ?>?search=${encodeURIComponent(searchQuery)}`;
        });
    </script>
</body>

</html>
