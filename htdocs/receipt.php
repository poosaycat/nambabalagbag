<?php
session_start();

// Check if the necessary session variables are set
if (!isset($_SESSION["firstName"]) || !isset($_SESSION["lastName"]) || !isset($_SESSION["bankacc"]) || !isset($_SESSION["amount"]) || !isset($_SESSION["email"])) {
    // Redirect to the donations page if any session variable is missing
    header("Location: donations.php");
    exit();
}

class Receipt {
    private $firstName;
    private $lastName;
    private $bankacc;
    private $amount;
    private $email;

    public function __construct($firstName, $lastName, $bankacc, $amount, $email) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->bankacc = $bankacc;
        $this->amount = $amount;
        $this->email = $email;
    }

    public function generateReceipt() {
        // Generate a random 11-digit reference number
        $referenceNo = rand(10000000000, 99999999999);

        // Generate the receipt HTML
        $receiptHtml = "
            <h2>Official Receipt</h2>
            <div class='receipt-info'>
                <p><strong><span style='color: #B14749;'>First Name:</span> $this->firstName</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Last Name:</span> $this->lastName</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Bank Account Number:</span> $this->bankacc</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Amount:</span> $this->amount</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Email:</span> $this->email</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Reference No.:</span> $referenceNo</strong></p>
                <div class='image-container'>
                    <img src='assets/aid.png' alt='Receipt Image' style='width: 150px; height: 75px; margin: 10px auto; display: block;' />
                </div>
            </div>
        ";

        // Output the receipt HTML
        echo $receiptHtml;
    }
}

// Retrieve data from session variables
$firstName = $_SESSION["firstName"];
$lastName = $_SESSION["lastName"];
$bankacc = $_SESSION["bankacc"];
$amount = $_SESSION["amount"];
$email = $_SESSION["email"];

$receipt = new Receipt($firstName, $lastName, $bankacc, $amount, $email);
?>

<html>
<head>
    <title>Receipt</title>
    <link rel="stylesheet" type="text/css" href="receipt.css">
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
                <button class="nav-btn new-button" onclick="window.location.href='donations.php';">Send Donations</button>
            </nav>
        </div>
    </header>
    <div class="receipt-container">
        <?php $receipt->generateReceipt(); ?>
    </div>
</body>
</html>
