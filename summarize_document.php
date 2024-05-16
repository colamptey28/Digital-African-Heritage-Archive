<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "digital_heritage_project"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if filepath query parameter is provided
if (isset($_GET['filepath'])) {
    // Fetch file path from the query parameter
    $filepath = $_GET['filepath'];

    // Check if file path is not empty
    if (!empty($filepath)) {
        // Execute Python script with file path as argument
        $pythonScript = "python3 summarize.py";
        $command = $pythonScript . " " . escapeshellarg($filepath);
        $summary = exec($command);
        echo $summary; // Output the summary
    } else {
        echo "File path is empty";
    }
} else {
    echo "Filepath query parameter is not provided";
}

$conn->close();
?>
