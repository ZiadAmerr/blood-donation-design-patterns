<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /views/user/login');
    exit();
}

$user = new Donor($_SESSION['user']['person_id']);

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/PlasmaDonationController.php';

$response = ['success' => false, 'message' => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new PlasmaDonationController();
    $response = $controller->processDonation($_POST, $user);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate Plasma</title>
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
        input, select, button {
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
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
        input[readonly], select:disabled {
            background-color: #f0f0f0;
            color: #888;
            border: 1px solid #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Donate Plasma</h2>

    <?php if (!empty($response['message'])): ?>
        <div class="<?php echo $response['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="donor_name">Name:</label>
        <input type="text" name="donor_name" id="donor_name" 
            value="<?= htmlspecialchars($user->getName() ?? '') ?>" readonly>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" 
            value="<?= htmlspecialchars($user->getDateOfBirth() ?? '') ?>" readonly>

        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" id="national_id" 
            value="<?= htmlspecialchars($user->getNationalId() ?? '') ?>" readonly>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" 
            value="<?= htmlspecialchars($user->getAddress() ?? '') ?>" readonly>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" id="phone" 
            value="<?= htmlspecialchars($user->getPhoneNumber() ?? '') ?>" readonly>

        <label for="blood_type">Select Blood Type:</label>
        <select name="blood_type" id="blood_type" disabled>
            <option value="<?= htmlspecialchars($user->getBloodTypeString() ?? '') ?>" selected>
                <?= htmlspecialchars($user->getBloodTypeString() ?? '-- Choose Type --') ?>
            </option>
        </select>

        <label for="volume_of_plasma">Volume of Plasma (in millilitres):</label>
        <input type="number" name="volume_of_plasma" id="volume_of_plasma" step="1" min="1" required>

        <button type="submit">Confirm Plasma Donation</button>
    </form>

</div>

</body>
</html>
