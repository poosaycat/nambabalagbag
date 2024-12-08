<?php
session_start();

// Database connection
$servername = "sql111.infinityfree.com"; // Your MySQL Hostname
$username = "if0_37869275";             // Your MySQL Username
$password = "PUg6VUe96570Q";            // Your MySQL Password
$dbname = "if0_37869275_aidalert";   

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

class Receipt {
    private $name;
    private $amount;
    private $email;
    private $paymentMethod;
    private $paymentNumber;

    public function __construct($name, $amount, $email, $paymentMethod, $paymentNumber) {
        $this->name = $name;
        $this->amount = $amount;
        $this->email = $email;
        $this->paymentMethod = $paymentMethod;
        $this->paymentNumber = $paymentNumber;
    }

    public function generateReceipt() {
        $referenceNo = rand(10000000000, 99999999999);

        $receiptHtml = "
            <h2>Official Receipt</h2>
            <div class='receipt-info'>
                <p><strong><span style='color: #B14749;'>Name:</span> $this->name</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Payment Method:</span> $this->paymentMethod</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Payment Number:</span> $this->paymentNumber</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Amount (PHP):</span> $this->amount</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Email:</span> $this->email</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Reference No.:</span> $referenceNo</strong></p>
            </div>
        ";

        return $receiptHtml;
    }
}

// Get plan from URL and set the amount
$planAmount = isset($_GET['plan']) ? $_GET['plan'] : '';
$username = isset($_GET['name']) ? $_GET['name'] : '';

$first_name = $last_name = $email = $payment_method = $contact_info = '';
$paymentNumber = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $amount = isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : $planAmount;
    $payment_method = isset($_POST['payment_method']) ? htmlspecialchars($_POST['payment_method']) : '';
    
    // Collect payment number for the correct method
    if ($payment_method == "GCash") {
        $paymentNumber = isset($_POST['gcash_number']) ? htmlspecialchars($_POST['gcash_number']) : '';
    } elseif ($payment_method == "PayPal") {
        $paymentNumber = isset($_POST['paypal_number']) ? htmlspecialchars($_POST['paypal_number']) : '';
    } else {
        $paymentNumber = isset($_POST['bank_account_number']) ? htmlspecialchars($_POST['bank_account_number']) : '';
    }

    // Save session info
    $_SESSION['full_name'] = "$first_name $last_name";
    $_SESSION['amount'] = $amount;
    $_SESSION['email'] = $email;
    $_SESSION['paymentMethod'] = $payment_method;
    $_SESSION['paymentNumber'] = $paymentNumber;

    // Generate Receipt
    $receipt = new Receipt($_SESSION['full_name'], $_SESSION['amount'], $_SESSION['email'], $payment_method, $paymentNumber);

    // Insert payment data into database
    $referenceNo = rand(10000000000, 99999999999); // Generate reference number

    $sql = "INSERT INTO payments (first_name, last_name, email, amount, payment_method, payment_number, reference_no) 
            VALUES ('$first_name', '$last_name', '$email', '$amount', '$payment_method', '$paymentNumber', '$referenceNo')";

    if ($conn->query($sql) === TRUE) {
        echo "<h2>Simulating payment for {$payment_method}...</h2>";
        echo "<h2>Payment Successful!</h2>";
        echo $receipt->generateReceipt(); // Display the receipt directly for the simulation
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
   <link rel="stylesheet" href="css/payment.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <h2>Payment Form</h2>
        <form action="" method="post">
            <h4>Account Information</h4>
            <div class="input_group">
                <div class="input_box">
                    <input type="text" name="first_name" placeholder="First Name" required class="name">
                    <i class="fa fa-user icon"></i>
                </div>
                <div class="input_box">
                    <input type="text" name="last_name" placeholder="Last Name" required class="name">
                    <i class="fa fa-user icon"></i>
                </div>
                <div class="input_box">
                    <input type="email" name="email" placeholder="Email Address" required class="name">
                    <i class="fa fa-envelope icon"></i>
                </div>
            </div>

            <div class="input_group">
                <div class="input_box">
                    <h4>Payment Method</h4>
                    <input type="radio" name="payment_method" value="GCash" class="radio" id="gcash" checked onchange="togglePaymentInfo()">
                    <label for="gcash"><span><i class="fa fa-mobile"></i> GCash</span></label>
                    <input type="radio" name="payment_method" value="PayPal" class="radio" id="paypal" onchange="togglePaymentInfo()">
                    <label for="paypal"><span><i class="fa fa-paypal"></i> PayPal</span></label>
                    <input type="radio" name="payment_method" value="Bank Account" class="radio" id="bankAccount" onchange="togglePaymentInfo()">
                    <label for="bankAccount"><span><i class="fa fa-bank"></i> Bank Account</span></label>
                </div>
            </div>

            <div id="paymentInfo">
                <div id="gcashInput" class="input_group" style="display: none;">
                    <div class="input_box">
                        <input type="text" name="gcash_number" id="gcashNumber" placeholder="Enter Your GCash Number" class="name">
                        <i class="fa fa-credit-card icon"></i>
                    </div>
                </div>

                <div id="paypalInput" class="input_group" style="display: none;">
                    <div class="input_box">
                        <input type="text" name="paypal_number" id="paypalNumber" placeholder="Enter Your PayPal Number" class="name">
                        <i class="fa fa-credit-card icon"></i>
                    </div>
                </div>

                <div id="bankAccountInput" class="input_group" style="display: none;">
                    <div class="input_box">
                        <input type="text" name="bank_account_number" id="bankAccountNumber" placeholder="Enter Bank Account Number" class="name">
                        <i class="fa fa-credit-card icon"></i>
                    </div>
                </div>
            </div>

            <div class="input_group">
                <div class="input_box">
                    <button type="submit">PAY NOW</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function togglePaymentInfo() {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            document.getElementById('gcashInput').style.display = paymentMethod === 'GCash' ? 'block' : 'none';
            document.getElementById('paypalInput').style.display = paymentMethod === 'PayPal' ? 'block' : 'none';
            document.getElementById('bankAccountInput').style.display = paymentMethod === 'Bank Account' ? 'block' : 'none';
        }

        // Initialize the form with the correct input fields based on the default selection
        window.onload = togglePaymentInfo;
    </script>
</body>
</html>