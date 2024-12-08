<?php
// Database configuration
$host = "sql111.infinityfree.com";
$username = "if0_37869275";
$password = "PUg6VUe96570Q";
$dbname = "if0_37869275_aidalert";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $bankacc = $_POST["bankacc"];
    $amount = $_POST["amount"];
    $email = $_POST["email"];

    if (!preg_match('/^[a-zA-Z ]+$/', $firstName) || !preg_match('/^[a-zA-Z ]+$/', $lastName)) {
        echo 'Error: Name can only contain letters and spaces.';
        exit;
    }

    if (!preg_match('/^[0-9]+$/', $bankacc)) {
        echo 'Error: Bank account number can only contain numbers.';
        exit;
    }

    if (!is_numeric($amount) || $amount <= 0) {
        echo 'Error: Amount must be a positive number.';
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Error: Invalid email format.';
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO donations (first_name, last_name, bank_account, amount, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstName, $lastName, $bankacc, $amount, $email);

    if ($stmt->execute()) {
        $_SESSION["firstName"] = $firstName;
        $_SESSION["lastName"] = $lastName;
        $_SESSION["bankacc"] = $bankacc;
        $_SESSION["amount"] = $amount;
        $_SESSION["email"] = $email;

        header("Location: receipt.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Donations</title>
    <link rel="stylesheet" type="text/css" href="donations.css">
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
                <button class="nav-btnn new-button" onclick="window.location.href='donations.php';">Send Donations</button>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Your donation can make an impact!</h2>
        <p>Your support has the power to transform lives and bring hope to those in need. Every contribution, whether large or small, plays a crucial role in our mission. By donating today, you are not just giving money; you are investing in a brighter future for individuals and communities. Together, we can create lasting change and uplift those who need it most. Your generosity truly makes a difference!</p>

        <div class="form-image-wrapper">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="donation-form">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" required><br><br>
                <span id="firstName-error" style="color: red;"></span>

                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required><br><br>
                <span id="lastName-error" style="color: red;"></span>

                <label for="bankacc">Bank Account Number:</label>
                <input type="text" id="bankacc" name="bankacc" required><br><br>
                <span id="bankacc-error" style="color: red;"></span>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <button type="submit" style="text-decoration: none; font-family: 'UbuntuReg'; font-size: 22px; color: #F5F7F8; border-style: solid; border-width: 2px; border-color: #343A40; width: 90%; height: 60px; margin-right: 60px; background-color: #B14749; border-radius: 10px; transition: background-color .5s ease-in-out;">Donate Now</button>
            </form>

            <div class="image-and-icons-container">
                <div class="image-container">
                    <img src="assets/hungry-children.jpg" alt="Donation Image">
                </div>
                <div class="icon-row">
                    <img src="assets/mastercard.png" alt="Icon 1" class="icon">
                    <img src="assets/paypal.png" alt="Icon 2" class="icon">
                    <img src="assets/apple-pay.png" alt="Icon 3" class="icon">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
