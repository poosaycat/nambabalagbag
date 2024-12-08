<?php
// Start the session to use session variables if needed
session_start();

// Database connection
$host = "sql111.infinityfree.com"; // Your MySQL Hostname
$username = "if0_37869275";             // Your MySQL Username
$password = "PUg6VUe96570Q";            // Your MySQL Password
$dbname = "if0_37869275_aidalert";   

$conn = new mysqli($host, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is trying to login
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Default admin credentials
    $admin_email = 'admin123@gmail.com';
    $admin_password = 'admin1234';
    $admin_name = 'Admin User';
    $admin_birthdate = '06-28-2004';

    // Check if the login credentials match the admin account
    if ($email === $admin_email && $password === $admin_password) {
        // Store email in session
        $_SESSION['email'] = $admin_email; // Store the email in session
        // Redirect to admin dashboard
        header("Location: adminacc.php");
        exit(); // Make sure to exit after redirect
    }

    // Check if user has registered before in the database
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $user['email']; // Store email in session
        // Redirect to membership page
        header("Location: membership.php?name=" . urlencode($user['name']) . "&birthdate=" . urlencode($user['birthdate']));
        exit(); // Make sure to exit after redirect
    } else {
        echo "<script>alert('Account not detected. Please register first.');</script>";
    }
}

// Register user
if (isset($_POST['register'])) {
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];
    $re_password = $_POST['register_re_password'];
    $name = $_POST['register_name'];
    $birthdate = $_POST['register_birthdate'];

    // Validate name to allow only letters and spaces
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        echo "Registration failed: Name can only contain letters and spaces.";
    } elseif ($password !== $re_password) {
        echo "Registration failed: Passwords do not match.";
    } else {
        // Insert user credentials into the database
        $sql = "INSERT INTO users (email, password, name, birthdate) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $password, $name, $birthdate);

        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Registration failed: " . $stmt->error;
        }
    }
}

// Show login form by default
$show_login = true;

// Toggle between login and register forms
if (isset($_GET['register'])) {
    $show_login = false;
} elseif (isset($_GET['login'])) {
    $show_login = true;
}
?>

<!-- HTML Form -->
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
                    <img src="assets/aid.png" alt="Logo" style="width: 250px; height: 200px;">
                </div>
                <h2>Admin Account</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="login_email">Email:</label>
                    <input type="email" id="login_email" name="login_email" required><br><br>
                    <label for="login_password">Password:</label>
                    <input type="password" id="login_password" name="login_password" required><br><br>
                    <input type="submit" name="login" value="Login">
                </form>
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
    <script>
        const nameInput = document.getElementById('register_name');
        const nameError = document.getElementById('name-error');
        const passwordInput = document.getElementById('register_password');
        const rePasswordInput = document.getElementById('register_re_password');
        const passwordError = document.getElementById('password-error');
        const rePasswordError = document.getElementById('re-password-error');
        const emailInput = document.getElementById('register_email');
        const birthdateInput = document.getElementById('register_birthdate');
        const form = document.querySelector('form');

        // Validate name input
        nameInput.addEventListener('input', (e) => {
            const inputValue = e.target.value;
            const regex = /^[a-zA-Z\s]+$/; // only allow letters and spaces

            if (!regex.test(inputValue)) {
                nameError.textContent = 'No special characters or numbers allowed';
            } else {
                nameError.textContent = '';
            }
        });

        // Validate form on submission
        form.addEventListener('submit', (e) => {
            // Check if all fields are filled
            if (nameInput.value === '' || passwordInput.value === '' || rePasswordInput.value === '' || emailInput.value === '' || birthdateInput.value === '') {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }

            // Check if passwords match
            if (passwordInput.value !== rePasswordInput.value) {
                e.preventDefault();
                alert('Passwords do not match');
            } else {
                passwordError.textContent = '';
                rePasswordError.textContent = '';
            }
        });
    </script>
</body>
</html>
