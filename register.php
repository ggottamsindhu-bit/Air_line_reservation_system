<?php
// DEBUGGING MODE (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB CONNECTION
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'airline';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

// Registration logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    // Check for existing email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered. Try another.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Airline Reservation</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #74ebd5, #acb6e5);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      background-color: white;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 25px;
    }
    input, select, button {
      width: 100%;
      padding: 12px;
      margin: 8px 0 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    button {
      background-color: #3498db;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #2980b9;
    }
    .toggle-password {
      float: right;
      margin-top: -45px;
      margin-right: 10px;
      cursor: pointer;
      font-size: 14px;
      color: #3498db;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
    .success {
      color: green;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Register</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>

    <form method="POST" action="">
      <input type="text" name="name" required placeholder="Full Name">
      <input type="email" name="email" required placeholder="Email">

      <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <input type="password" name="password" id="password" required placeholder="Password">
      <span class="toggle-password" onclick="togglePassword()">Show</span>

      <button type="submit">Sign Up</button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      const toggle = document.querySelector('.toggle-password');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        toggle.textContent = 'Hide';
      } else {
        pwd.type = 'password';
        toggle.textContent = 'Show';
      }
    }
  </script>
</body>
</html>
