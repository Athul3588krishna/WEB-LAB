<?php
/* employee.php — Single-file: add employee, list, delete */
session_start();

/* ===== DB CONFIG ===== */
$host = 'localhost';
$db   = 'demo_company';
$user = 'root';
$pass = ''; // XAMPP default; change if needed

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (Exception $e) {
  die("DB connection failed");
}

/* ===== DELETE (optional) ===== */
$msg = '';
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $pdo->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
  $msg = "Employee deleted.";
}

/* ===== INSERT ===== */
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $empid  = trim($_POST['emp_id'] ?? '');
  $name   = trim($_POST['name'] ?? '');
  $dept   = trim($_POST['dept'] ?? '');
  $salary = trim($_POST['salary'] ?? '');

  if ($empid==='' || $name==='' || $dept==='' || $salary==='') {
    $msg = "All fields are required.";
  } elseif (!is_numeric($salary)) {
    $msg = "Salary must be a number.";
  } else {
    try {
      $stmt = $pdo->prepare("INSERT INTO employees(emp_id,name,dept,salary) VALUES (?,?,?,?)");
      $stmt->execute([$empid, $name, $dept, $salary]);
      $msg = "Employee saved successfully.";
    } catch (PDOException $e) {
      if ($e->getCode()==='23000') $msg = "Duplicate EmpID. Use a different EmpID.";
      else $msg = "Insert failed.";
    }
  }
}

/* ===== FETCH ALL ===== */
$list = $pdo->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Employee – Add & Display (Single file)</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;max-width:900px;margin:30px auto}
  .wrap{display:grid;grid-template-columns:1fr 2fr;gap:20px}
  form, table{border:1px solid #ccc;border-radius:10px}
  form{padding:16px}
  input,select{width:100%;padding:9px;margin:8px 0}
  table{width:100%;border-collapse:collapse;overflow:hidden}
  th,td{padding:10px;border-top:1px solid #eee}
  th{background:#f7f7f7;text-align:left}
  .msg{margin-bottom:12px;padding:10px;border-radius:8px}
  .ok{background:#e8ffef;border:1px solid #b1ffd1;color:#0a7c3b}
  .bad{background:#ffe8e8;border:1px solid #ffb1b1;color:#970000}
  a.btn{padding:6px 10px;border:1px solid #ccc;border-radius:8px;text-decoration:none}
</style>
</head>
<body>

<h2>Employee – Add & Display</h2>

<?php if ($msg): ?>
  <div class="msg <?= (strpos($msg,'success')!==false)?'ok':'bad' ?>">
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<div class="wrap">
  <!-- LEFT: ADD FORM -->
  <div>
    <h3>Add Employee</h3>
    <form method="post" autocomplete="off">
      <label>EmpID</label>
      <input type="text" name="emp_id" required>

      <label>Name</label>
      <input type="text" name="name" required>

      <label>Department</label>
      <input type="text" name="dept" placeholder="e.g., HR, Sales, IT" required>

      <label>Salary</label>
      <input type="number" step="0.01" name="salary" required>

      <input type="submit" value="Save Employee">
    </form>
  </div>

  <!-- RIGHT: DISPLAY TABLE -->
  <div>
    <h3>All Employees</h3>
    <table>
      <tr>
        <th>#</th>
        <th>EmpID</th>
        <th>Name</th>
        <th>Dept</th>
        <th>Salary</th>
        <th>Action</th>
      </tr>
      <?php if (!$list): ?>
        <tr><td colspan="6">No employees yet.</td></tr>
      <?php else: ?>
        <?php foreach ($list as $row): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['emp_id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['dept']) ?></td>
            <td><?= number_format($row['salary'],2) ?></td>
            <td><a class="btn" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this employee?')">Delete</a></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </table>
  </div>
</div>

<p style="margin-top:14px;font-size:13px;opacity:.8">
Tip: Use the SQL shown above to create the database and table before opening this page.
</p>

</body>
</html>


CREATE DATABASE demo_company CHARACTER SET utf8mb4;
USE demo_company;

CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  emp_id VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  dept VARCHAR(60) NOT NULL,
  salary DECIMAL(10,2) NOT NULL
);
