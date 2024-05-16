<?php
session_start();

// Function to read document content from file
function readDocumentContent($filepath) {
    // Check if file exists
    if (file_exists($filepath)) {
        // Read file contents
        $content = file_get_contents($filepath);
        return $content;
    } else {
        return "File not found.";
    }
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$limit = 10; // Number of documents per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch documents from the database with pagination
$sql = "SELECT * FROM documents LIMIT $start, $limit";
$result = $conn->query($sql);

// Fetch total number of documents for pagination
$sql_count = "SELECT COUNT(id) AS total FROM documents";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $limit);

$documents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming the file path is stored in the "filepath" column
        $content = readDocumentContent($row['filepath']); // Read document content from file
        if ($content !== false) {
            $row['content'] = $content; // Add document content to the document data
        } else {
            $row['content'] = "Error reading file.";
        }
        $documents[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
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
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
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
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-gray-900 text-white py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a class="btn btn-light inline-block px-4 py-2 rounded-md text-sm ml-4" href="dashboard.php">&#9664; Back</a>
            <h1 class="text-3xl font-bold">Documents Collection</h1>
            <div class="ml-4"></div> <!-- Spacer for center alignment -->
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Documents Collection -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="documentsGrid">
            <?php foreach ($documents as $document) : ?>
                <div class="bg-white p-6 rounded-lg shadow-md cursor-pointer document">
                    <h2 class="text-xl font-bold mb-2 document-title"><?php echo $document['title']; ?></h2>
                    <p class="text-gray-600">Description: <?php echo $document['description']; ?></p>
                    <p class="text-gray-600">Uploaded by: <?php echo $document['uploaded_by']; ?></p>
                    <p class="text-gray-600">Uploaded on: <?php echo $document['upload_date']; ?></p>
                    <button class="px-4 py-2 bg-gray-900 text-white rounded-md mt-4" onclick="showContent('<?php echo htmlspecialchars($document['content']); ?>')">Preview</button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="?page=<?php echo $i; ?>" class="px-4 py-2 bg-gray-900 text-white rounded-md mr-2"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </main>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalContent"></p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
        <div class="container mx-auto">
            <p>&copy; 2024 Digital African Repository. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript for Modal and Card Click Event -->
    <script>
        function showContent(content) {
            var modalContent = document.getElementById("modalContent");
            modalContent.textContent = content;
            var modal = document.getElementById("myModal");
            modal.style.display = "block";
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>

</body>

</html>

