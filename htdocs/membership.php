<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: accadmin.php"); // Redirect to login page if not logged in
    exit();
}

// Display the user's name in the upper right
$username = $_SESSION['name'];

// Initialize variables
$message = '';

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_birthdate = $_POST['birthdate'];
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Read users from the file
    $users = array();
    $file = 'users.txt';

    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            list($file_username, $file_password, $file_birthdate) = explode(',', $line);
            $users[$file_username] = array(
                'password' => $file_password,
                'birthdate' => $file_birthdate
            );
        }
    }

    // Validate password change
    if (isset($users[$username]) && password_verify($current_password, $users[$username]['password'])) {
        if ($new_password === $confirm_password) {
            // Update user information
            $users[$username]['birthdate'] = $new_birthdate;
            $users[$username]['password'] = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

            // Save updated information back to the file
            $file_content = '';
            foreach ($users as $user => $info) {
                $file_content .= $user . ',' . $info['password'] . ',' . $info['birthdate'] . "\n";
            }
            file_put_contents($file, $file_content);

            $_SESSION['message'] = "Information updated successfully, including password!";
        } else {
            $_SESSION['message'] = "New password and confirmation do not match.";
        }
    } else {
        $_SESSION['message'] = "Current password is incorrect.";
    }

    // Redirect to avoid form resubmission
    header("Location: membership.php");
    exit();
}

// Check if there is a session message to display
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Page</title>
    <link rel="stylesheet" href="membership.css">
</head>
<body>
<header>
    <div class="app-bar">
        <a href="index.html">
            <div><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn emergency-planning" onclick="window.location.href='emergency planning.html';">Emergency Planning</button>
            <button class="nav-btn disaster-management" onclick="window.location.href='disaster management.html';">Disaster Management</button>
            <button class="nav-btn resources" onclick="window.location.href='resources.html';">Resources</button>
            <button class="nav-btn charity-programs" onclick="window.location.href='charity.html';">Charity Programs</button>
            <button class="nav-btn donations" onclick="window.location.href='donations.php';")">Send Donations</button>

            <div class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($username); ?></span>
                <div class="dropdown">
                    <div class="dropdown-toggle" onclick="toggleDropdown()">&#9776;</div>
                    <div class="dropdown-content">
                        <button class="modal-button" onclick="openModal('edit-info')">My Account</button>
                        
                        <a href="logout.php" class="logout-button">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="container firstPage-container">
    <div class="sub-section">
        <h2>SUBSCRIPTION PLANS</h2>
        <div class="sub-container">
            <div class="plans-container">
                <div class="plan-box">
                    <h3>₱250</h3>
                    <p>Support our programs with a monthly contribution of ₱250.</p>
                    <button class="join-button" id="plan-250" onclick="subscribe(250)">JOIN</button>
                </div>
                <div class="plan-box">
                    <h3>₱500</h3>
                    <p>Provide greater support with a monthly contribution of ₱500.</p>
                    <button class="join-button" id="plan-500" onclick="subscribe(500)">JOIN</button>
                </div>
                <div class="plan-box">
                    <h3>₱1000</h3>
                    <p>Make an impactful contribution with ₱1000 a month.</p>
                    <button class="join-button" id="plan-1000" onclick="subscribe(1000)">JOIN</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing information -->
    <div id="edit-info" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('edit-info')">&times;</span>
            <h2>Edit Your Information</h2>

            <form method="POST">
                <?php if ($message): ?>
                    <p><?php echo $message; ?></p>
                <?php endif; ?>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" required>

                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" required>

                <h3>Change Password</h3>
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current-password" required>

                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required>

                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit" class="nav-btn new-button">Update Information</button>
            </form>
        </div>
    </div>

<script>
function subscribe(planAmount) {
    // Change the button text to "SUBSCRIBED"
    const planButton = document.getElementById(`plan-${planAmount}`);
    planButton.innerText = 'SUBSCRIBED';
    planButton.disabled = true;

    // Redirect to payment page
    window.location.href = `payment.php?plan=${planAmount}&name=<?php echo urlencode($username); ?>`;
}

function toggleDropdown() {
    const dropdown = document.querySelector('.dropdown-content');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
function subscribe(planAmount) {
    // Change the button text to "SUBSCRIBED"
    const planButton = document.getElementById(`plan-${planAmount}`);
    planButton.innerText = 'SUBSCRIBED';
    planButton.disabled = true;

    // Redirect to payment page with plan amount
    window.location.href = `payment.php?plan=${planAmount}&name=<?php echo urlencode($username); ?>`;
}

window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target == modal) {
            closeModal(modal.id);
        }
    });
}
</script>
<div class="contact-section">
    <!-- Contact Section -->
    <div class="contact-section">
      <h2>Contact Us</h2>
      <div class="contact-container">
          <div class="contact-info">
              <p><strong>Phone:</strong> +123 456 7890</p>
              <p><strong>Email:</strong> contact@charity.org</p>
              <p><strong>Address:</strong> 123 Charity Lane, Goodville, GV 12345</p>
          </div>
          <div class="contact-social">
            <p><strong>Follow Us:</strong></p>
            <div class="social-links">
              <a href="https://www.facebook.com/charity" class="contact-link">Facebook</a> |
              <a href="https://twitter.com/charity" class="contact-link">Twitter</a> |
              <a href="https://www.instagram.com/charity" class="contact-link">Instagram</a>
            </div>
    
        
          
    
  

</body>
</html>
