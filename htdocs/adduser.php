<?php
session_start();

// Include database connection
include 'db.php'; // Make sure this path is correct

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    header("Location: accadmin.php"); // Redirect to login page if not admin
    exit();
}

// Handle form submission for adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);

    // Insert new user into the database
    $query = "INSERT INTO users (first_name, last_name, email, birthdate) VALUES ('$firstName', '$lastName', '$email', '$birthdate')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?message=User added successfully');
        exit;
    } else {
        echo "<script>alert('Error adding user: " . mysqli_error($conn) . "');</script>";
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="adminacc.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="app-bar">
        <a href="mainpage.html">
            <div><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn new-button" onclick="window.location.href='accadmin.php';">Logout</button>
        </nav>
    </div>

    <div class="container">
        <h1>Add New User</h1>
        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <button type="submit" class="new-button">Add User</button>
        </form>
    </div>
</body>
</html>
