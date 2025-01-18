<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MoneyDonationController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Cash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankCard.php';

$response = ['success' => false, 'message' => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new MoneyDonationController();
    
    // Attempt to find existing donor by National ID
    $nationalId = $_POST['national_id'];
    $donor = Donor::findByNationalId($nationalId);

    if ($donor) {
        // Existing donor found, proceed with donation
        $response = $controller->processDonation(array_merge($_POST, ['donor_id' => $donor->getId()]));
    } else {
        // No existing donor, attempt to create a new donor
        $donorData = [
            'name' => $_POST['donor_name'],
            'dob' => $_POST['dob'],
            'national_id' => $nationalId,
            'address' => $_POST['address'],
            'phone' => $_POST['phone']
        ];

        $donor = Donor::create($donorData);
        if ($donor) {
            // Donor created successfully, proceed with donation
            $response = $controller->processDonation(array_merge($_POST, ['donor_id' => $donor->getId()]));
        } else {
            // Failed to create donor
            $response = ['success' => false, 'message' => 'Failed to create donor. Please check your input and try again.'];
        }
    }
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
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 450px;
            max-height: 95vh;
            overflow-y: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        select, input, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .hidden {
            display: none;
        }
        .success { 
            color: green; 
            text-align: center; 
            margin-bottom: 15px;
        }
        .error { 
            color: red; 
            text-align: center; 
            margin-bottom: 15px;
        }
    </style>
    <script>
        function togglePaymentFields() {
            const method = document.getElementById('payment_method').value;
            document.getElementById('cash_fields').style.display = method === 'Cash' ? 'block' : 'none';
            document.getElementById('ewallet_fields').style.display = method === 'EWallet' ? 'block' : 'none';
            document.getElementById('card_fields').style.display = method === 'BankCard' ? 'block' : 'none';
        }

        // Initialize the form based on any pre-selected payment method
        window.onload = function() {
            togglePaymentFields();
        };
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
        <!-- Donor Information Fields -->
        <label for="donor_name">Name:</label>
        <input type="text" name="donor_name" id="donor_name" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" required>

        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" id="national_id" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" required>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" id="phone" required>

        <!-- Payment Method Selection -->
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

        <button type="submit">Confirm Donation</button>
    </form>
</div>

</body>
</html>
