<?php
/* index.php — Single-file Login + Welcome (PHP + MySQL) */
session_start();

/* ====== DB CONFIG ====== */
$host = 'localhost';
$db   = 'demo_login';   // your DB name
$user = 'root';         // DB user
$pass = '';             // DB password (change if needed)

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass,[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
  ]);
} catch (Exception $e) {
  die("DB connection failed.");
}

/* ====== LOGOUT ====== */
if (isset($_GET['action']) && $_GET['action']==='logout') {
  session_unset(); session_destroy();
  header("Location: index.php"); exit;
}

$error = '';
/* ====== HANDLE LOGIN ====== */
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $uname = trim($_POST['username'] ?? '');
  $passw = $_POST['password'] ?? '';

  // find by username
  $stmt = $pdo->prepare("SELECT id,username,password_hash FROM users WHERE username=?");
  $stmt->execute([$uname]);
  $userRow = $stmt->fetch();

  if ($userRow && password_verify($passw, $userRow['password_hash'])) {
    // (a) username & password match -> login success
    $_SESSION['uid'] = $userRow['id'];
    $_SESSION['uname'] = $userRow['username'];
  } elseif ($userRow && !password_verify($passw, $userRow['password_hash'])) {
    // (b) username match, password wrong
    $error = "Incorrect password.";
  } else {
    // username not found → check if password matches ANY user (to show (c))
    $q = $pdo->query("SELECT password_hash FROM users");
    $passwordMatchesSomeone = false;
    foreach ($q as $r) {
      if (password_verify($passw, $r['password_hash'])) { $passwordMatchesSomeone = true; break; }
    }
    if ($passwordMatchesSomeone) $error = "Incorrect username.";
    else $error = "Incorrect username or password.";
  }
}

/* ====== VIEW ====== */
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login & Welcome (Single file)</title>
<style>
  body{font-family:Arial,Helvetica,sans-serif;max-width:440px;margin:40px auto}
  form{border:1px solid #ccc;padding:16px;border-radius:10px}
  input[type=text],input[type=password]{width:100%;padding:10px;margin:8px 0}
  .msg{padding:10px;border-radius:8px;margin-bottom:12px}
  .error{background:#ffe8e8;border:1px solid #ffb1b1;color:#970000}
  .ok{background:#e8ffef;border:1px solid #b1ffd1;color:#0a7c3b}
  a.btn{display:inline-block;margin-top:8px;padding:8px 12px;border:1px solid #ccc;border-radius:8px;text-decoration:none}
</style>
</head>
<body>

<?php if (isset($_SESSION['uid'])): ?>
  <h2>Welcome Page</h2>
  <div class="msg ok">
    Welcome, <b><?= htmlspecialchars($_SESSION['uname']) ?></b>! You have successfully logged in.
  </div>
  <a class="btn" href="?action=logout">Logout</a>

<?php else: ?>
  <h2>Login Page</h2>

  <?php if ($error): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <input type="submit" value="Login">
  </form>

  <p style="font-size:13px;opacity:.8">
    Note: <i>users</i> table: (username, password_hash). Create a user with<br>
    <code>password_hash = password_hash('yourpass', PASSWORD_DEFAULT)</code> in PHP.
  </p>
<?php endif; ?>

</body>
</html>


CREATE DATABASE demo_login CHARACTER SET utf8mb4;


USE demo_login;


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL
);


<?php
$pdo = new PDO("mysql:host=localhost;dbname=demo_login","root","");
$hash = password_hash("12345", PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users(username, password_hash) VALUES (?, ?)");
$stmt->execute(["student", $hash]);

echo "User added!";
