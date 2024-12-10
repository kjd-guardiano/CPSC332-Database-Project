<?php
// Include database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username is 'root'
$password = "";      // Default XAMPP password is an empty string
$dbname = "tuffys_tomes";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle stock update request
if (isset($_GET['bookid']) && isset($_GET['stock'])) {
    $bookID = $_GET['bookid'];
    $currentStock = $_GET['stock'];

    // Check if stock is available
    if ($currentStock > 0) {
        // Decrease stock in the database
        $newStock = $currentStock - 1;
        $updateStockSql = "UPDATE books SET stock = ? WHERE BookID = ?";
        $stmt = $conn->prepare($updateStockSql);
        $stmt->bind_param("ii", $newStock, $bookID);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh the page and reflect changes
        header("Location: books.php");
        exit();
    } else {
       
    }
}

// Fetch books and authors from the database
$sql = "SELECT books.BookID, books.Title, books.Price, books.ISBN, books.PublicationYear, genres.GenreName, publishers.PublisherName, 
               GROUP_CONCAT(authors.FirstName, ' ', authors.LastName ORDER BY authors.AuthorID) AS AuthorNames,
               books.image AS BookImage, books.stock
        FROM books 
        JOIN genres ON books.GenreID = genres.GenreID
        JOIN publishers ON books.PublisherID = publishers.PublisherID
        JOIN book_authors ON books.BookID = book_authors.BookID
        JOIN authors ON book_authors.AuthorID = authors.AuthorID
        GROUP BY books.BookID";
$result = $conn->query($sql);

// Fixed pastel colors for book items
$pastelColors = [
    "rgb(255, 182, 193)",  // Light Pink
    "rgb(173, 216, 230)",  // Light Blue
    "rgb(255, 240, 245)",  // Lavender
    "rgb(255, 228, 196)",  // Moccasin
    "rgb(240, 230, 140)",  // Khaki
    "rgb(216, 191, 216)",  // Thistle
    "rgb(152, 251, 152)",  // Light Green (added)
];

$colorIndex = 0; // To loop through the pastel colors
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css">  <!-- Existing Home Page CSS -->
  <link rel="stylesheet" href="css/books.css">  <!-- New CSS for books.php -->
  <title>Books Collection - Tuffy's Books</title>
  <script>
    // JavaScript function to confirm purchase
    function confirmPurchase(bookID, stock) {
        // Show the custom confirmation modal next to the button
        var confirmationBox = document.getElementById("confirmation-box-" + bookID);
        confirmationBox.style.display = "inline-block";
        
        document.getElementById("confirm-btn-" + bookID).onclick = function() {
            window.location.href = "books.php?bookid=" + bookID + "&stock=" + stock;
        };
        
        document.getElementById("cancel-btn-" + bookID).onclick = function() {
            confirmationBox.style.display = "none";
        };
    }
  </script>
 
</head>
<body>
  <!-- Header Section -->
  <header>
    <img src="https://thumbs.dreamstime.com/b/open-book-blooming-wild-flower-sand-leaves-d-objects-cartoon-power-reader-imagination-isolated-line-vector-elements-white-328901816.jpg" alt="Tuffy" class="tuffy"/>
    <h1>Tuffy's Books</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="books.php">Books</a></li>
        <li><a href="">About Us</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main Content Section -->
  <main>
    <h2 class="intro">Our Book Collection</h2>

    <div class="book-list">
      <?php
      // Check if there are any books in the database
      if ($result->num_rows > 0) {
          // Output each book
          while($row = $result->fetch_assoc()) {
              // Assign a pastel color from the array
              $backgroundColor = $pastelColors[$colorIndex];
              $colorIndex = ($colorIndex + 1) % count($pastelColors);  // Loop through colors

              echo '<section class="book-item" style="background-color: ' . $backgroundColor . ';">';
              echo '<div class="img-container">';
              // Use the image URL from the database for each book
              echo '<img src="' . $row['BookImage'] . '" alt="Book Cover" class="book-image">';
              echo '</div>';
              echo '<div class="book-description">';
              echo '<h2 class="book-title" id="book-title-' . $row['BookID'] . '">' . $row['Title'] . '</h2>';
              echo '<h3 class="book-author">Author: ' . $row['AuthorNames'] . '</h3>';
              echo '<h3 class="book-publisher">Publisher: ' . $row['PublisherName'] . '</h3>';
              echo '<h3 class="book-genre">Genre: ' . $row['GenreName'] . '</h3>';
              echo '<p class="book-info">ISBN: ' . $row['ISBN'] . '<br>Published: ' . $row['PublicationYear'] . '</p>';
              echo '<p class="book-price">Price: $' . number_format($row['Price'], 2) . '</p>';
              echo '<p id="stock-' . $row['BookID'] . '" class="book-stock">Stock: ' . $row['stock'] . ' available</p>';

              // Confirmation box next to the button
              echo '<div id="confirmation-box-' . $row['BookID'] . '" class="confirmation-box">';
              echo '<p>Confirm purchase?</p>';
              echo '<button id="confirm-btn-' . $row['BookID'] . '" class="confirm-btn">Yes</button>';
              echo '<button id="cancel-btn-' . $row['BookID'] . '" class="cancel-btn">No</button>';
              echo '</div>';

              // "Buy Now" button
              echo '<button class="buy-button" onclick="confirmPurchase(' . $row['BookID'] . ',' . $row['stock'] . ')">Buy Now</button>';
              echo '</div>';
              echo '</section>';
          }
      } else {
          echo "No books available at the moment.";
      }

      // Close connection
      $conn->close();
      ?>
    </div>
  </main>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2024 Tuffy's Books. All rights reserved.</p>
  </footer>
</body>
</html>
