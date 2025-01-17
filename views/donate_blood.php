<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/BloodDonationController.php';

$response = ['success' => false, 'message' => ''];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new BloodDonationController();
    $response = $controller->processDonation($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donate Blood</title>
    <style>
        /* Add some basic styling */
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
        .hidden {
            display: none;
        }
        .success { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<div class="container">
    <h2>Donate Blood</h2>

    <?php if (!empty($response['message'])): ?>
        <div class="<?php echo $response['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
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

        <label for="blood_type">Select Blood Type:</label>
        <select name="blood_type" id="blood_type" required>
            <option value="">-- Choose Type --</option>
            <option value="A+">A+</option>
            <option value="B+">B+</option>
            <option value="AB+">AB+</option>
            <option value="O+">O+</option>
            <option value="A-">A-</option>
            <option value="B-">B-</option>
            <option value="AB-">AB-</option>
            <option value="O-">O-</option>
        </select>

        <label for="number_of_liters">Number of Liters:</label>
        <input type="number" name="number_of_liters" id="number_of_liters" step="0.1" min="0" required>

        <button type="submit">Confirm Donation</button>
    </form>
</div>

</body>
</html>
