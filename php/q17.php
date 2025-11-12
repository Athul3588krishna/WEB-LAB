<html>
<head>
    <title>Factorial of a Number</title>
</head>
<body>

<h2>Factorial of a Number</h2>

<form method="post">
    Enter a number: <input type="text" name="num">
    <input type="submit" value="Find Factorial">
</form>

<?php
$num = $_POST['num'];
$fact = 1;

for ($i = 1; $i <= $num; $i++) {
    $fact = $fact * $i;
}

echo "Factorial of $num is: $fact";
?>

</body>
</html>
