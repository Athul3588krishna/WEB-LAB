<?php
require 'config.php'; // database connection file

// Get user input safely
$book_no   = (int)($_POST['book_no'] ?? 0);
$title     = trim($_POST['title'] ?? '');
$edition   = trim($_POST['edition'] ?? '');
$publisher = trim($_POST['publisher'] ?? '');

// Validate input
if ($book_no && $title != '') {
    $stmt = $conn->prepare("INSERT INTO book_details (book_no, title, edition, publisher) VALUES (?,?,?,?)");
    $stmt->bind_param("isss", $book_no, $title, $edition, $publisher);
    
    if ($stmt->execute()) {
        echo "<p>✅ Book added successfully!</p>";
        echo "<a href='list_books.php'>View All Books</a>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color:red;'>Please fill all required fields.</p>";
}
