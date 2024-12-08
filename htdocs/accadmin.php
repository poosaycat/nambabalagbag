<?php
session_start();

// Include database connection
include 'db.php'; // Make sure this path is correct

// Redirect if user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: membership.php");
    exit();
}

/// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);

    // Write the query
    $query = "SELECT password, first_name, last_name FROM users WHERE email = '$email'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Fetch password and name from the result
        $hashed_password = $row['password'];
        $stored_first_name = $row['first_name'];
        $stored_last_name = $row['last_name'];

        // Combine first and last name if needed
        $stored_name = $stored_first_name . ' ' . $stored_last_name;

        // Verify password (assuming you're storing the password as hashed using md5)
        if (md5($password) === $hashed_password) {
            // Password is correct, login the user
            $_SESSION['logged_in'] = true;
            $_SESSION['name'] = $stored_name; // Store name in session
            header("Location: membership.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Account not detected. Please register first.');</script>";
    }
}

// Registration logic
if (isset($_POST['register'])) {
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];
    $re_password = $_POST['register_re_password'];
    $first_name = $_POST['register_first_name'];
    $last_name = $_POST['register_last_name'];
    $birthdate = $_POST['register_birthdate'];

    // Validation for name fields
    if (!preg_match("/^[a-zA-Z\s]+$/", $first_name) || !preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        echo "<script>alert('Registration failed: Name can only contain letters and spaces.');</script>";
    } elseif ($password !== $re_password) {
        echo "<script>alert('Registration failed: Passwords do not match.');</script>";
    } else {
        // Hash the password using md5 (not secure, just for demonstration)
        $hashed_password = md5($password);

        // Sanitize input to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $email);
        $first_name = mysqli_real_escape_string($conn, $first_name);
        $last_name = mysqli_real_escape_string($conn, $last_name);
        $birthdate = mysqli_real_escape_string($conn, $birthdate);

        // Write the query to insert user into the database
        $query = "INSERT INTO users (email, password, first_name, last_name, birthdate) 
                  VALUES ('$email', '$hashed_password', '$first_name', '$last_name', '$birthdate')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('User registered successfully!');</script>";
        } else {
            echo "<script>alert('Registration failed: " . mysqli_error($conn) . "');</script>";
        }
    }
}

$show_login = true;

if (isset($_GET['register'])) {
    $show_login = false;
} elseif (isset($_GET['login'])) {
    $show_login = true;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <link rel="stylesheet" href="accadmin.css">
</head>
<body>
    <div class="container">
        <?php if ($show_login) { ?>
            <div class="login-form">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="assets/aid.png" alt="Logo" style="width: 350px; height: 150px;">
                </div>
                <h2>Login</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="login_email">Email:</label>
                    <input type="email" id="login_email" name="login_email" required><br><br>
                    <label for="login_password">Password:</label>
                    <input type="password" id="login_password" name="login_password" required><br><br>
                    <input type="submit" name="login" value="Login">
                </form>
                <p><a href="forgot_password.php">Forgot Password?</a></p>
                <p>Don't have an account? <a href="<?php echo $_SERVER['PHP_SELF']; ?>?register">Register</a></p>
                <p>Are you an admin? <a href="accadmin2.php">Login here</a></p>


            </div>
        <?php } else { ?>
            <div class="register-form">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="assets/aid.png" alt="Logo" style="width: 250px; height: 200px;">
                </div>
                <h2>Register</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="register_email">Email:</label>
    <input type="email" id="register_email" name="register_email" required><br><br>
    
    <label for="register_password">Password:</label>
    <input type="password" id="register_password" name="register_password" required><br><br>
    
    <span id="password-error" style="color: red;"></span>
    
    <label for="register_re_password">Re-enter Password:</label>
    <input type="password" id="register_re_password" name="register_re_password" required><br><br>
    
    <span id="re-password-error" style="color: red;"></span>
    
    <!-- Separate First Name and Last Name Inputs -->
    <label for="register_first_name">First Name:</label>
    <input type="text" id="register_first_name" name="register_first_name" required><br><br>
    
    <label for="register_last_name">Last Name:</label>
    <input type="text" id="register_last_name" name="register_last_name" required><br><br>
    
    <label for="register_birthdate">Birthdate:</label>
    <input type="date" id="register_birthdate" name="register_birthdate" required><br><br>
    
    <input type="submit" name="register" value="Register" required><br><br>
</form>

                <p>Already have an account? <a href="<?php echo $_SERVER['PHP_SELF']; ?>?login">Login</a></p>
            </div>
        <?php } ?>
    </div>
    <script src="validation.js"></script>
</body>
</html>
