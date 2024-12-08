<?php
session_start();

// Include database connection
include 'db.php'; // Make sure this path is correct

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    header("Location: accadmin.php"); // Redirect to login page if not admin
    exit();
}

// Fetch user data from the database
$query = "SELECT email, first_name, last_name, birthdate FROM users";
$result = mysqli_query($conn, $query);
$users = array();

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = array(
            'email' => $row['email'],
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'birthdate' => $row['birthdate']
        );
    }
}

// Handle user deletion
if (isset($_GET['email'])) {
    $emailToDelete = htmlspecialchars($_GET['email']);

    // Delete the user from the database
    $query = "DELETE FROM users WHERE email = '$emailToDelete'";
    if (mysqli_query($conn, $query)) {
        // Redirect with success message
        header('Location: ' . $_SERVER['PHP_SELF'] . '?message=Account deleted successfully');
        exit;
    } else {
        echo "<script>alert('Error deleting account: " . mysqli_error($conn) . "');</script>";
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminacc.css"> <!-- Link to separate CSS file -->
    <script>
        function confirmDelete(email) {
            const confirmation = confirm(`Are you sure you want to delete the account with email: ${email}?`);
            if (confirmation) {
                // If confirmed, redirect to the delete script with the user's email
                window.location.href = `<?php echo $_SERVER['PHP_SELF']; ?>?email=` + encodeURIComponent(email);
            }
        }
    </script>
</head>
<body>
    <div class="app-bar">
        <a href="index.html">
            <div><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn new-button" onclick="window.location.href='accadmin.php';">Logout</button>
        </nav>
    </div>

    <div class="container">
        <h1>Welcome, Admin User!</h1>
        <h2>Registered Accounts:</h2>
        <div>
            <!-- Add button -->
            <a href="adduser.php" class="action-btn add-btn">Add User</a>
            
            <?php foreach ($users as $user): ?>
                <div class="accountbutton">
                    <a href="#" onclick="alert('Email: <?php echo htmlspecialchars($user['email']); ?>\nName: <?php echo htmlspecialchars($user['name']); ?>\nBirthdate: <?php echo htmlspecialchars($user['birthdate']); ?>'); return false;" class="accContainer-button">
                        Email: <?php echo htmlspecialchars($user['email']); ?>, 
                        Name: <?php echo htmlspecialchars($user['name']); ?>, 
                        Birthdate: <?php echo htmlspecialchars($user['birthdate']); ?>
                    </a>
                    <button onclick="confirmDelete('<?php echo htmlspecialchars($user['email']); ?>');" class="delete-btn">Delete Account</button>
                    <!-- Edit button -->
                    <a href="edituser.php?email=<?php echo urlencode($user['email']); ?>" class="edit-btn">Edit</a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (isset($_GET['message'])): ?>
            <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
