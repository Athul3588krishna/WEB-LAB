<html>
<head>
    <title>Odd or Even Check</title>
</head>
<body>

<h2>Check whether a number is Odd or Even</h2>

<form method="get">
    Enter a number: <input type="number" name="num">
    <input type="submit" value="Check">
</form>

<?php

$num = $_GET['num'];

if ($num % 2 == 0) {
    echo "<h3>$num is an Even number.</h3>";
} else {
    echo "<h3>$num is an Odd number.</h3>";
}
?>
</body>
</html>
