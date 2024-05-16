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

// Pagination
$limit = 10; // Number of images per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch images from the database with pagination
$sql = "SELECT * FROM images LIMIT $start, $limit";
$result = $conn->query($sql);

// Fetch total number of images for pagination
$sql_count = "SELECT COUNT(id) AS total FROM images";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $limit);

$images = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
}

// Fetch images from the database
// Assume $images is an array of images fetched from the database

// Pagination settings
$imagesPerPage = 12; // Number of images per page
$totalImages = count($images); // Total number of images
$totalPages = ceil($totalImages / $imagesPerPage); // Total number of pages

// Current page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = max(1, min($totalPages, intval($page))); // Ensure page is within valid range

// Calculate starting index for pagination
$startIndex = ($page - 1) * $imagesPerPage;
$paginatedImages = array_slice($images, $startIndex, $imagesPerPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Collection - Digital African Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure body takes up at least the height of the viewport */
            background-image: url('Web-images/dasboard.jpeg'); 
        }

        .card {
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column; /* Ensure that image and accordion content stack vertically */
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            flex: 1; 
            width: 100%;
            height: auto;
        }

        .accordion {
            transition: max-height 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }

        .accordion-content {
            padding: 0.5rem 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            margin: 10% auto;
            width: 80%;
            max-width: 800px;
            position: relative;
        }

        .modal-img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            margin-bottom: 10px;
        }

    </style>
</head>

<body>
      <!-- Header -->
      <div class="bg-gray-900 text-white py-4 flex items-center justify-between">
        <a href="images_main_section.php" class="text-lg font-bold">&#9664; Back</a> <!-- Normal anchor tag -->
        <h1 class="text-3xl font-bold">Colonial Gold Coast</h1>
        <div class="ml-4"></div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">

    <!-- Search Bar -->
<div class="search py-4 px-4">
    <form id="searchForm" class="flex items-center justify-center">
        <input type="text" id="searchInput" name="search" placeholder="Search..." class="px-6 py-4 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 w-full max-w-md" oninput="searchImages()"> <!-- Added oninput attribute -->
        <button type="button" onclick="searchImages()" class="btn btn-primary ml-4 px-6 py-4 rounded-md text-white font-bold">Search</button>
    </form>
</div>

  
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Display uploaded images dynamically -->
            <?php foreach ($paginatedImages as $image): ?>
            <div class="card relative overflow-hidden bg-white rounded-lg shadow-md cursor-pointer"
                data-title="<?php echo $image['title']; ?>" data-image="<?php echo $image['filepath']; ?>">
                <img src="<?php echo $image['filepath']; ?>" class="w-full h-auto" alt="Image">
                <div class="accordion bg-gray-100 hidden">
                    <div class="accordion-content">
                        <p class="text-sm text-gray-600"><strong>Title:</strong> <?php echo $image['title']; ?></p>
                        <p class="text-sm text-gray-600"><strong>Description:</strong> <?php echo $image['description']; ?>
                        </p>
                        <p class="text-sm text-gray-600"><strong>Uploaded By:</strong> <?php echo $image['uploaded_by']; ?>
                        </p>
                        <p class="text-sm text-gray-600"><strong>Upload Date:</strong> <?php echo $image['upload_date']; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center"> <!-- Adjusted margin for pagination -->
            <ul class="pagination flex space-x-2">
                <!-- Previous page link -->
                <?php if ($page > 1) : ?>
                <li>
                    <a href="?page=<?php echo $page - 1; ?>"
                        class="px-4 py-2 rounded-md text-white font-bold bg-gray-700">&laquo; Previous</a>
                </li>
                <?php endif; ?>

                <!-- Numbered page links -->
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li>
                    <a href="?page=<?php echo $i; ?>"
                        class="px-4 py-2 rounded-md text-white font-bold <?php echo $i === $page ? 'bg-blue-500' : 'bg-gray-700'; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>

                <!-- Next page link -->
                <?php if ($page < $totalPages) : ?>
                <li>
                    <a href="?page=<?php echo $page + 1; ?>"
                        class="px-4 py-2 rounded-md text-white font-bold bg-gray-700">Next &raquo;</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </main>

    <!-- Modal -->
    <div id="myModal" class="modal" onclick="closeModal()">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <img id="modalImg" class="modal-img">
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
        <div class="container mx-auto">
            <p>&copy; 2024 Digital African Heritage Archive. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.card').on('click', function () {
                var $accordion = $(this).find('.accordion');
                var expanded = $accordion.hasClass('expanded');

                if (!expanded) {
                    $(this).find('.accordion').toggleClass('hidden');
                    var accordionContentHeight = $(this).find('.accordion-content').outerHeight();
                    $(this).find('.accordion').css('max-height', accordionContentHeight + 'px');
                } else {
                    var imagePath = $(this).attr('data-image');
                    openModal(imagePath);
                }

                $(this).find('.accordion').toggleClass('expanded');
            });
        });

        function searchImages() {
            var input, filter, cards, title, i;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            cards = document.getElementsByClassName('card');

            for (i = 0; i < cards.length; i++) {
                title = cards[i].getAttribute('data-title');
                if (title.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = '';
                } else {
                    cards[i].style.display = 'none';
                }
            }
        }

        function openModal(imagePath) {
            var modal = document.getElementById('myModal');
            var modalImg = document.getElementById('modalImg');
            modal.style.display = "block";
            modalImg.src = imagePath;
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }
    </script>
</body>

</html>
