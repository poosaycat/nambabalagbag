<?php
session_start();

// Include database connection
include 'db.php'; // Make sure this path is correct

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    header("Location: accadmin.php"); // Redirect to login page if not admin
    exit();
}

// Get the user’s email from the URL
if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    // Fetch the user data to edit
    $query = "SELECT first_name, last_name, email, birthdate FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('User not found.');</script>";
        exit;
    }
}

// Handle form submission for editing the user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);

    // Update the user information in the database
    $query = "UPDATE users SET first_name = '$firstName', last_name = '$lastName', birthdate = '$birthdate' WHERE email = '$email'";

    if (mysqli_query($conn, $query)) {
        header('Location: accadmin.php?message=User updated successfully');
        exit;
    } else {
        echo "<script>alert('Error updating user: " . mysqli_error($conn) . "');</script>";
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
    <title>Edit User</title>
    <link rel="stylesheet" href="adminacc.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="app-bar">
        <a href="index.html">
            <div><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn new-button" onclick="window.location.href='../PrelimWEBSITE-main/accadmin.php';">Logout</button>
        </nav>
    </div>

    <div class="container">
        <h1>Edit User</h1>
        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required>

            <button type="submit" class="new-button">Update User</button>
        </form>
    </div>
</body>
</html>
