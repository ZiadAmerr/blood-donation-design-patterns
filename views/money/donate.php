<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MoneyDonationController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MoneyDonationController.php';

session_start();

$user_id = $_SESSION["user"]["person_id"] ?? null;

if (!$user_id) {
    header('Location: /views/user/login/');
    exit;
}

$user = new Donor($user_id);
$nationalId = $user->getNationalId();

$response = ['success' => false, 'message' => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new MoneyDonationController();
    $response = $controller->processDonation(array_merge($_POST, [
        'donor_id' => $user_id,
        'national_id' => $nationalId
    ]));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate Money</title>
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
            background-color: #d9534f;
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
            document.getElementById('ewallet_fields').style.display = method === 'EWallet' ? 'block' : 'none';
            document.getElementById('card_fields').style.display = method === 'BankCard' ? 'block' : 'none';
        }

        // Ensure fields are correctly displayed on page load
        window.onload = togglePaymentFields;
    </script>
</head>
<body>

<div class="container">
    <h2>Donate Money</h2>

    <?php if (!empty($response['message'])): ?>
        <div class="<?php echo $response['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" id="national_id" value="<?php echo htmlspecialchars($nationalId); ?>" readonly>

        <label for="payment_method">Select Payment Method:</label>
        <select name="payment_method" id="payment_method" onchange="togglePaymentFields()" required>
            <option value="">-- Choose Method --</option>
            <option value="Cash">Cash</option>
            <option value="EWallet">E-Wallet</option>
            <option value="BankCard">Bank Card</option>
        </select>

        <!-- Cash Payment Fields -->
        <div id="cash_fields" class="hidden">
            <label for="cash_amount">Cash Amount:</label>
            <input type="number" name="cash_amount" id="cash_amount" step="0.01" min="0" placeholder="Enter amount">
        </div>

        <!-- E-Wallet Payment Fields -->
        <div id="ewallet_fields" class="hidden">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter password">

            <label for="ewallet_amount">Amount:</label>
            <input type="number" name="ewallet_amount" id="ewallet_amount" step="0.01" min="0" placeholder="Enter amount">
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

        <button type="submit">Confirm</button>
    </form>
</div>

</body>
</html>
