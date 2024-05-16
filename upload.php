<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
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

    // Process file upload
    $targetDir = "";
    $fileName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $collection = $_POST["collection"];
    $title = pathinfo($fileName, PATHINFO_FILENAME); // Extract document name from file properties
    $description = $_POST["description"]; // Added to retrieve description from form
    $uploadedBy = $_SESSION['email']; // Assuming fullname is stored in session

    // Determine the target directory based on the collection and file type
    switch ($collection) {
        case "documents":
            $targetDir = "uploads/documents/";
            break;
        case "audios":
            $targetDir = "uploads/audios/";
            break;
        case "images":
            $targetDir = "uploads/images/";
            break;
        case "videos":
            $targetDir = "uploads/videos/";
            break;
        default:
            // Invalid collection
            break;
    }

    if (!empty($targetDir)) {
        // Upload file to server
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            // Insert file details into database
            $table = $collection;
            $sql = "INSERT INTO $table (filename, title, description, uploaded_by, filepath, upload_date) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $fileName, $title, $description, $uploadedBy, $targetFilePath);

            if ($stmt->execute() === TRUE) {
                $message = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative alert-message" role="alert"><strong class="font-bold">Success!</strong> File uploaded successfully.</div>';
            } else {
                $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert-message" role="alert"><strong class="font-bold">Error!</strong> ' . $stmt->error . '</div>';
            }
        } else {
            $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert-message" role="alert"><strong class="font-bold">Error!</strong> Error uploading file.</div>';
        }
    } else {
        $message = '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert-message" role="alert"><strong class="font-bold">Error!</strong> Invalid collection.</div>';
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Upload Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-image: url('Web-images/dasboard.jpeg');
        }

        .footer {
            margin-top: auto;
        }

        .alert-message {
            animation: fadeOut 3s forwards; /* Apply fadeOut animation */
        }

        @keyframes fadeOut {
            from {
                opacity: 1; /* Start with full opacity */
            }
            to {
                opacity: 0; /* Fade out to transparent */
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
   <!-- Header -->
   <div class="header bg-gray-900 text-white py-4">
            <div class="container mx-auto flex justify-center items-center px-4">
                <a href="dashboard.php" class="text-lg font-bold">&#9664; Back</a>
                <h1 class="text-3xl font-bold mx-auto">Upload</h1>
            </div>
        </div>
    <!-- Main Content -->
    <div class="container mx-auto mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-center">Upload Form</h2>
            <?php if (isset($message)) echo $message; ?> <!-- Display message here -->
            <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <div class="mb-4">
                    <label for="file" class="block text-gray-700">Select File:</label>
                    <input type="file" id="file" name="file" required accept=".mp4, .avi, .mov" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                </div>
                <div class="mb-4">
                    <label for="collection" class="block text-gray-700">Select Collection:</label>
                    <select id="collection" name="collection" required onchange="updateFileTypes()" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                        <option value="" selected disabled>Select Collection</option>
                        <option value="documents">Documents</option>
                        <option value="audios">Audios</option>
                        <option value="images">Images</option>
                        <option value="videos">Videos</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description:</label>
                    <textarea id="description" name="description" class="form-textarea mt-1 p-2 border border-gray-300 rounded-md w-full"></textarea>
                </div>
                <input type="hidden" id="uploadedBy" name="uploadedBy" value="<?php echo $_SESSION["email"]; ?>">
                <div class="mb-4">
                    <label for="uploadedBy" class="block text-gray-700">Uploaded By:</label>
                    <input type="text" id="uploadedBy" name="uploadedBy" value="<?php echo $_SESSION["email"]; ?>" readonly class="mt-1 p-2 border border-gray-300 rounded-md w-full bg-gray-100 cursor-not-allowed">
                </div>
                <button type="submit" class="block w-full py-3 px-6 bg-black text-white rounded-md mt-6 hover:bg-gray-800">Upload</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Digital Repository. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function updateFileTypes() {
            var collection = document.getElementById("collection").value;
            var fileInput = document.getElementById("file");
            var descriptionField = document.getElementById("descriptionField");

            switch (collection) {
                case "documents":
                    fileInput.setAttribute("accept", ".doc, .docx, .pdf");
                    descriptionField.style.display = "none"; // Hide description field
                    break;
                case "audios":
                    fileInput.setAttribute("accept", ".mp3, .wav");
                    descriptionField.style.display = "none"; // Hide description field
                    break;
                case "images":
                    fileInput.setAttribute("accept", ".jpg, .jpeg, .png");
                    descriptionField.style.display = "block"; // Show description field
                    break;
                case "videos":
                    fileInput.setAttribute("accept", ".mp4, .avi, .mov");
                    descriptionField.style.display = "none"; // Hide description field
                    break;
                default:
                    fileInput.setAttribute("accept", "");
                    descriptionField.style.display = "none"; // Hide description field
                    break;
            }
        }

        // Call the function on page load to set initial file types
        updateFileTypes();

        // Function to remove the alert after 6 seconds
        setTimeout(function() {
            var alertMessage = document.querySelector('.alert-message');
            if (alertMessage) {
                alertMessage.style.opacity = '0'; // Start the fade-out animation
                setTimeout(function() {
                    alertMessage.style.display = 'none'; // After the animation, hide the alert message
                }, 3000); // Wait for the animation duration
            }
        }, 6000);
    </script>

</body>

</html>

