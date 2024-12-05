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

// Fetch books and authors from the database
$sql = "SELECT books.BookID, books.Title, books.Price, books.ISBN, books.PublicationYear, genres.GenreName, publishers.PublisherName, 
               GROUP_CONCAT(authors.FirstName, ' ', authors.LastName ORDER BY authors.AuthorID) AS AuthorNames
        FROM books 
        JOIN genres ON books.GenreID = genres.GenreID
        JOIN publishers ON books.PublisherID = publishers.PublisherID
        JOIN book_authors ON books.BookID = book_authors.BookID
        JOIN authors ON book_authors.AuthorID = authors.AuthorID
        GROUP BY books.BookID";
$result = $conn->query($sql);

// Function to generate a random pastel color
function generateRandomPastelColor() {
  // Adjust the range for moderate pastel colors (150 to 230)
  $r = rand(150, 230);
  $g = rand(150, 230);
  $b = rand(150, 230);
  return "rgb($r, $g, $b)";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/styles.css">  <!-- Existing Home Page CSS -->
  <link rel="stylesheet" href="css/books.css">  <!-- New CSS for books.php -->
  <title>Books Collection - Tuffy's Books</title>
</head>
<body>
  <!-- Header Section -->
  <header>
    <img src="https://thumbs.dreamstime.com/b/open-book-blooming-wild-flower-sand-leaves-d-objects-cartoon-power-reader-imagination-isolated-line-vector-elements-white-328901816.jpg" alt="Tuffy" class="tuffy"/>
    <h1>Tuffy's Books</h1>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="books.php">Books</a></li>
        <li><a href="about.html">About Us</a></li>
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
              // Generate a random pastel background color
              $backgroundColor = generateRandomPastelColor();
              echo '<section class="book-item" style="background-color: ' . $backgroundColor . ';">';
              echo '<div class="img-container">';
              echo '<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9d69NhT4XEGnRaXCWXsnjtOEeff_28zQtOQ&s" alt="Book Cover" class="book-image">';
              echo '</div>';
              echo '<div class="book-description">';
              echo '<h2 class="book-title">' . $row['Title'] . '</h2>';
              echo '<h3 class="book-author">Author: ' . $row['AuthorNames'] . '</h3>';
              echo '<h3 class="book-publisher">Publisher: ' . $row['PublisherName'] . '</h3>';
              echo '<h3 class="book-genre">Genre: ' . $row['GenreName'] . '</h3>';
              echo '<p class="book-info">ISBN: ' . $row['ISBN'] . '<br>Published: ' . $row['PublicationYear'] . '</p>';
              echo '<p class="book-price">Price: $' . number_format($row['Price'], 2) . '</p>';
              echo '<a href="buy.php?bookid=' . $row['BookID'] . '"><button class="buy-button">Buy Now</button></a>';
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
