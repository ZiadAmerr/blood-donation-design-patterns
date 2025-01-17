<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/registration_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/address_service.php';

$response = ['success' => false, 'message' => ''];
$addresses = AddressService::getAllAddresses();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = RegistrationService::registerDonor($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Donor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #d9534f;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #d9534f;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #c9302c;
        }
        .error, .success {
            text-align: center;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Register as Donor</h2>
    <?php if (!empty($response['message'])): ?>
        <div class="<?php echo $response['success'] ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($response['message']); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth" id="date_of_birth" required>

        <label for="national_id">National ID:</label>
        <input type="text" name="national_id" id="national_id" required>

        <label for="selected_address">Choose Existing Address:</label>
        <select name="selected_address" id="selected_address">
            <option value="">-- Select Address --</option>
            <?php foreach ($addresses as $address): ?>
                <option value="<?php echo $address['id']; ?>">
                    <?php echo htmlspecialchars($address['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <hr>
        <label for="new_address_name">OR Create New Address:</label>
        <input type="text" name="new_address_name" id="new_address_name" placeholder="New Address Name">

        <label for="parent_address_id">Under Parent Address (Optional):</label>
        <select name="parent_address_id" id="parent_address_id">
            <option value="">-- Select Parent Address --</option>
            <?php foreach ($addresses as $address): ?>
                <option value="<?php echo $address['id']; ?>">
                    <?php echo htmlspecialchars($address['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Register</button>
        <a href="finances.php">
            <button type="button" style="background-color: #5bc0de;">Finances</button>
        </a>
    </form>
</div>
</body>
</html>

