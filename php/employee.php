<?php
// Database connection settings
$servername = "localhost";
$username = "root";     // default username
$password = "";         // default password
$dbname = "company";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert employee details if form is submitted
if (isset($_POST['submit'])) {
    $emp_id = $_POST['emp_id'];
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $salary = $_POST['salary'];

    // Check if emp_id already exists
    $check = $conn->query("SELECT * FROM employees WHERE emp_id = '$emp_id'");
    if ($check->num_rows > 0) {
        echo "<p style='color:red;'>Employee ID already exists! Please use a different ID.</p>";
    } else {
        $sql = "INSERT INTO employees (emp_id, name, designation, salary)
                VALUES ('$emp_id', '$name', '$designation', '$salary')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>New employee added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
</head>
<body>
    <h2>Add Employee</h2>
    <form method="POST" action="">
        <label>Employee ID:</label><br>
        <input type="number" name="emp_id" required><br><br>

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Designation:</label><br>
        <input type="text" name="designation" required><br><br>

        <label>Salary:</label><br>
        <input type="number" step="0.01" name="salary" required><br><br>

        <input type="submit" name="submit" value="Add Employee">
    </form>

    <hr>

    <h2>Employee Details</h2>
    <?php
    $result = $conn->query("SELECT * FROM employees");

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='8'>
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Salary</th>
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['emp_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['designation']}</td>
                    <td>{$row['salary']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No employee records found.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
