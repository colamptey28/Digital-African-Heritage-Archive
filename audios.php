<?php

session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch uploaded audio files from the database
$sql = "SELECT id, title, filename, filepath, upload_date, description FROM audios";
$result = $conn->query($sql);
$audioFiles = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $audioFiles[] = $row;
    }
}

$conn->close();

// Pagination
$perPage = 21;
$totalPages = ceil(count($audioFiles) / $perPage);
$page = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1;
$start = ($page - 1) * $perPage;
$paginatedFiles = array_slice($audioFiles, $start, $perPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Collection - Digital African Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure body takes up at least the height of the viewport */
            background-image: url('Web-images/dasboard.jpeg');
        }
        footer {
            margin-top: auto; /* Push the footer to the bottom */
            background-color: #343a40; /* Changed footer background color */
            color: white;
            text-align: center;
            padding: 1rem 0;
            width: 100%;
        }
        .pagination {
            margin-top: 10px; /* Adjusted margin for pagination */
            margin-bottom: 10px;
        }
        .audio-item {
            min-height: 100px; /* Reduced card size */
            padding: 10px; /* Reduced padding for the cards */
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="bg-gray-900 text-white py-4 flex items-center justify-between">
        <a href="audios_main_section.php" class="text-lg font-bold">&#9664; Back</a> <!-- Moved to the far left -->
        <h1 class="text-3xl font-bold mx-auto">Audio Collection</h1> <!-- Centered -->
        <div class="ml-4">
            <!-- Links to other collections -->
            <a class="text-lg font-bold mr-4" href="dashboard.php">Home</a>
            <a class="text-lg font-bold mr-4" href="documents.php">Documents</a>
            <a class="text-lg font-bold mr-4" href="audios_main_section.php">Audios</a>
            <a class="text-lg font-bold mr-4" href="images_main_section.php">Images</a>
            <a class="text-lg font-bold mr-4" href="video_main_section.php">Videos</a>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="container mx-auto px-4 flex-grow">

        <!-- Search Bar -->
        <div class="search py-4 px-4">
            <form id="searchForm" class="flex items-center justify-center">
                <input type="text" id="searchInput" name="search" placeholder="Search..." class="px-6 py-4 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 w-full max-w-md"> <!-- Adjusted width and padding -->
                <button type="submit" class="btn btn-primary ml-4 px-6 py-4 rounded-md text-white font-bold">Search</button> <!-- Adjusted margin and padding -->
            </form>
        </div>

        <!-- Display uploaded audio files -->
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($paginatedFiles as $audio) : ?>
                <li class="audio-item bg-white shadow-md rounded-md flex flex-col justify-between">
                    <div class="audio-title font-bold text-lg mb-2"><?php echo $audio['filename']; ?></div>
                    <audio controls class="w-full">
                        <source src="<?php echo $audio['filepath']; ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    <button class="btn btn-primary mt-2 px-4 py-2 rounded-md text-white font-bold" onclick="downloadAudio('<?php echo $audio['filepath']; ?>')">Download</button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center"> <!-- Adjusted margin for pagination -->
            <ul class="pagination flex space-x-2">
                <!-- Previous page link -->
                <?php if ($page > 1) : ?>
                    <li>
                        <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 rounded-md text-white font-bold bg-gray-700">&laquo; Previous</a>
                    </li>
                <?php endif; ?>

                <!-- Numbered page links -->
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li>
                        <a href="?page=<?php echo $i; ?>" class="px-4 py-2 rounded-md text-white font-bold <?php echo $i === $page ? 'bg-blue-500' : 'bg-gray-700'; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Next page link -->
                <?php if ($page < $totalPages) : ?>
                    <li>
                        <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 rounded-md text-white font-bold bg-gray-700">Next &raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
        <div class="container mx-auto">
            <p>&copy; 2024 Digital African Repository. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Function to filter audio items based on search term
        function searchAudios() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const audioItems = document.querySelectorAll('.audio-item');

            audioItems.forEach(item => {
                const audioTitle = item.querySelector('.audio-title').textContent.toLowerCase();
                if (audioTitle.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Add event listener to search form submission
        const searchForm = document.getElementById("searchForm");
        searchForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            searchAudios(); // Call the search function
        });

        // Function to handle audio download
        function downloadAudio(filePath) {
            window.location.href = filePath;
        }
    </script>
</body>

</html>
