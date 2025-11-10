<?php
require 'config.php';
$result = $conn->query("SELECT * FROM book_details");
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Book List</title></head>
<body>
  <h2>All Books</h2>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr><th>Book No</th><th>Title</th><th>Edition</th><th>Publisher</th></tr>
    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['book_no']); ?></td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['edition']); ?></td>
        <td><?= htmlspecialchars($row['publisher']); ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <br><a href="book_form.html">Add Another Book</a>
</body>
</html>
