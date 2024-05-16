<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Collection - Digital African Repository</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('Web-images/dasboard.jpeg');
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 10px 20px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #4CAF50;
        }

        .content {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }

        .filter-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .filter-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .filter-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .filter-dropdown {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .filter-button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
        }

        .filter-button:hover {
            background-color: #333;
        }

        .menu-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .menu-card {
            flex: 0 1 calc(33.33% - 20px);
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .menu-card:hover {
            transform: scale(1.05);
        }

        .menu-card-image {
            height: 200px;
            overflow: hidden;
        }

        .menu-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-card-content {
            padding: 20px;
        }

        .menu-card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .menu-card-description {
            font-size: 1rem;
            color: #666;
        }

        .sidebar {
            flex: 0 1 300px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .archive-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .archive-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .archive-dropdown {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .archive-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .archive-item {
            margin-bottom: 0.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .archive-item:hover {
            color: #4CAF50;
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            width: 100%;
            display: none; /* initially hidden */
        }

        .footer p {
            margin: 0;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .page-link {
            padding: 8px 16px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .page-link:hover {
            background-color: #333;
        }

        .page-link.current {
            background-color: #333;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="bg-gray-900 text-white py-4 flex items-center justify-between">
        <a href="dashboard.php" class="text-lg font-bold">&#9664; Back</a>
        <h1 class="text-3xl font-bold">Audios Collection</h1>
        <div class="ml-4">
            <!-- Links to other collections -->
            <a class="text-lg font-bold mr-4" href="dashboard.php">Home</a>
            <a class="text-lg font-bold mr-4" href="documents.php">Documents</a>
            <a class="text-lg font-bold mr-4" href="audios_main_section.php">Audios</a>
            <a class="text-lg font-bold mr-4" href="images_main_section.php">Images</a>
            <a class="text-lg font-bold mr-4" href="video_main_section.php">Videos</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Content Section -->
        <div class="content">
            <!-- Filter Section -->
            <div class="filter-section">
                <h2>Filters</h2>
                <input type="text" id="searchInput" name="search" placeholder="Search..." class="filter-input">
                <select id="categoryDropdown" class="filter-dropdown">
                    <option value="">Select Category</option>
                    <option value="music">Music</option>
                    <option value="podcast">Podcasts</option>
                    <!-- Add more options as needed -->
                </select>
                <button type="button" class="filter-button" onclick="applyFilters()">Apply Filter</button>
            </div>

            <!-- Menu Cards -->
            <div class="menu-cards" id="menuCards">
               
            </div>

            <!-- No Item Found Message -->
            <div class="no-item-found" style="display: none;">
                <p>No item found.</p>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination">
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-content">
                <!-- Archive Section -->
                <div class="archive-section">
                    <h2 class="archive-title">Archive</h2>
                    <select id="archiveDropdown" class="archive-dropdown">
                        <option value="">Select Year</option>
                        <option value="2023">2024</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-gray-900 text-white py-4 mt-auto text-center">
        <div class="container mx-auto">
            <p>&copy; Digital African Heritage Archive. All rights reserved.</p>
        </div>
    </footer>

    <script>
    var menuData = [
        {title: "Speeches", description: "Listen to speeches by great African Leaders.", category: "speeches", url: "audios.php", imageSrc: "Web-images/Microphone.jpeg" },
        { title: "Traditional Music", description: "Explore traditional African music.", category: "music", url: "music.php", imageSrc: "Web-images/Instruments.jpeg" },
        { title: "African Podcasts", description: "Listen to insightful podcasts about African culture.", category: "podcast", url: "podcasts.php", imageSrc: "Web-images/images.jpeg" },
    ];

    // Number of items per page
    var itemsPerPage = 6;

    // Current page
    var currentPage = 1;

    // Total number of pages
    var totalPages = Math.ceil(menuData.length / itemsPerPage);

    // Function to generate menu cards
    function generateMenuCards(data) {
        var startIndex = (currentPage - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;
        var paginatedMenuData = data.slice(startIndex, endIndex);

        var menuCardsHtml = "";

        paginatedMenuData.forEach(function(item) {
            menuCardsHtml += `
            <a href="${item.url}" class="menu-card">
                <div class="menu-card-image">
                    <img src="${item.imageSrc}" alt="${item.title}">
                </div>
                <div class="menu-card-content">
                    <h2 class="menu-card-title">${item.title}</h2>
                    <p class="menu-card-description">${item.description}</p>
                </div>
            </a>
            `;
        });

        document.getElementById("menuCards").innerHTML = menuCardsHtml;
    }

    // Function to generate pagination controls
    function generatePagination() {
        var paginationHtml = "";

        for (var i = 1; i <= totalPages; i++) {
            var isActive = i === currentPage ? "current" : "";
            paginationHtml += `<button class="page-link ${isActive}" onclick="changePage(${i})">${i}</button>`;
        }

        document.getElementById("pagination").innerHTML = paginationHtml;
    }

    // Function to change page
    function changePage(pageNumber) {
        currentPage = pageNumber;
        applyFilters();
    }

    // Apply filters
    function applyFilters() {
        var searchValue = document.getElementById("searchInput").value.toLowerCase();
        var categoryValue = document.getElementById("categoryDropdown").value;

        var filteredData = menuData.filter(function(item) {
            return (
                (item.title.toLowerCase().includes(searchValue) || item.description.toLowerCase().includes(searchValue)) &&
                (categoryValue === "" || item.category === categoryValue)
            );
        });

        totalPages = Math.ceil(filteredData.length / itemsPerPage);
        currentPage = 1;

        generateMenuCards(filteredData);
        generatePagination();

        if (filteredData.length === 0) {
            document.querySelector('.no-item-found').style.display = "block";
        } else {
            document.querySelector('.no-item-found').style.display = "none";
        }
    }

    // Reset filters and show all items
    function resetFilters() {
        document.getElementById("searchInput").value = "";
        document.getElementById("categoryDropdown").value = "";
        applyFilters();
    }

    // Event listener for search input
    document.getElementById("searchInput").addEventListener("input", function() {
        applyFilters();
    });

    // Event listener for category dropdown
    document.getElementById("categoryDropdown").addEventListener("change", function() {
        applyFilters();
    });

    // Initial generation of menu cards and pagination controls
    generateMenuCards(menuData);
    generatePagination();
</script>

</body>

</html>
