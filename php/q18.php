<html>
<head>
    <title>Student Details Form</title>
</head>
<body>

<h2 align="center">Student Details Form</h2>

<form method="post">
<table border="1" align="center" cellpadding="5" cellspacing="0">

<tr>
    <td>Name:</td>
    <td><input type="text" name="name" required></td>
</tr>

<tr>
    <td>Email:</td>
    <td><input type="email" name="email" required></td>
</tr>

<tr>
    <td>Address:</td>
    <td><textarea name="address" rows="3" cols="20" required></textarea></td>
</tr>

<tr>
    <td>Gender:</td>
    <td>
        <input type="radio" name="gender" value="Male"> Male
        <input type="radio" name="gender" value="Female"> Female
    </td>
</tr>

<tr>
    <td>Date of Birth:</td>
    <td><input type="date" name="dob" required></td>
</tr>

<tr>
    <td colspan="2" align="center">
        <input type="submit" value="Submit">
    </td>
</tr>

</table>
</form>

<?php
if ($_REQUEST) {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $address = $_REQUEST['address'];
    $gender = $_REQUEST['gender'];
    $dob = $_REQUEST['dob'];

    echo "<h3 align='center'>Student Details Submitted:</h3>";
    echo "<table border='1' align='center' cellpadding='5'>";
    echo "<tr><td>Name</td><td>$name</td></tr>";
    echo "<tr><td>Email</td><td>$email</td></tr>";
    echo "<tr><td>Address</td><td>$address</td></tr>";
    echo "<tr><td>Gender</td><td>$gender</td></tr>";
    echo "<tr><td>Date of Birth</td><td>$dob</td></tr>";
    echo "</table>";
}
?>

</body>
</html>
