<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Cash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankCard.php';

$response = ['success' => false, 'message' => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentMethod = $_POST['payment_method'];

    if ($paymentMethod === 'Cash') {
        $amount = floatval($_POST['cash_amount']);
        $cash = new Cash();

        if ($cash->donate($amount)) {
            $response = ['success' => true, 'message' => "Cash donation of $amount was successful!"];
        } else {
            $response = ['success' => false, 'message' => "Invalid cash amount."];
        }

    } elseif ($paymentMethod === 'BankCard') {
        $amount = floatval($_POST['card_amount']);
        $cardNumber = $_POST['card_number'];
        $cvv = $_POST['cvv'];
        $expiryDate = $_POST['expiry_date'];

        $bankCard = new BankCard($cardNumber, $cvv, $expiryDate);

        if ($bankCard->processPayment($amount)) {
            $response = ['success' => true, 'message' => "Bank card payment of $amount was successful!"];
        } else {
            $response = ['success' => false, 'message' => "Bank card payment failed."];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Finances</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        select, input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .hidden {
            display: none;
        }
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
    <script>
        function togglePaymentFields() {
            const method = document.getElementById('payment_method').value;
            document.getElementById('cash_fields').style.display = method === 'Cash' ? 'block' : 'none';
            document.getElementById('card_fields').style.display = method === 'BankCard' ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Finance Management</h2>

    <?php if (!empty($response['message'])): ?>
        <div class="<?php echo $response['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" id="payment_method" onchange="togglePaymentFields()" required>
            <option value="">-- Choose Method --</option>
            <option value="Cash">Cash</option>
            <option value="BankCard">Bank Card</option>
        </select>

        <!-- Cash Payment Fields -->
        <div id="cash_fields" class="hidden">
            <label for="cash_amount">Cash Amount:</label>
            <input type="number" name="cash_amount" id="cash_amount" step="0.01" min="0" placeholder="Enter amount">
        </div>

        <!-- Bank Card Payment Fields -->
        <div id="card_fields" class="hidden">
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" id="card_number" placeholder="Enter card number">

            <label for="cvv">CVV:</label>
            <input type="text" name="cvv" id="cvv" placeholder="Enter CVV">

            <label for="expiry_date">Expiry Date:</label>
            <input type="month" name="expiry_date" id="expiry_date">

            <label for="card_amount">Amount:</label>
            <input type="number" name="card_amount" id="card_amount" step="0.01" min="0" placeholder="Enter amount">
        </div>

        <button type="submit">Submit Payment</button>
    </form>
</div>

</body>
</html>
