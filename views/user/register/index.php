<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/registration_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/address_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/disease_service.php';

$response = ['success' => false, 'message' => ''];
$addresses = AddressService::getAllAddresses();

$saved_diseases = DiseaseService::getAllDiseases();

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
            width: 600px;
        }
        h2 {
            text-align: center;
            color: #d9534f;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .form-group div {
            width: 48%;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 5px 0;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
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
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
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
        
        <!-- Personal Information -->
        <div class="form-group">
            <div>
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" name="date_of_birth" id="date_of_birth" required>
            </div>
        </div>

        <div class="form-group">
            <div>
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" required>
            </div>
            <div>
                <label for="national_id">National ID:</label>
                <input type="text" name="national_id" id="national_id" required>
            </div>
        </div>

        <hr>

        <!-- Address Selection -->
        <label for="selected_address">Choose Existing Address:</label>
        <select name="selected_address" id="selected_address">
            <option value="">-- Select Address --</option>
            <?php foreach ($addresses as $address): ?>
                <option value="<?php echo $address['id']; ?>">
                    <?php echo htmlspecialchars($address['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-group">
            <div>
                <label for="new_address_name">OR Create New Address:</label>
                <input type="text" name="new_address_name" id="new_address_name" placeholder="New Address Name">
            </div>
            <div>
                <label for="parent_address_id">Under Parent Address (Optional):</label>
                <select name="parent_address_id" id="parent_address_id">
                    <option value="">-- Select Parent Address --</option>
                    <?php foreach ($addresses as $address): ?>
                        <option value="<?php echo $address['id']; ?>">
                            <?php echo htmlspecialchars($address['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <hr>

        <!-- Account Information -->
        <div class="form-group">
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
        </div>

        <hr>

        <!-- Medical Information -->
        <div class="form-group">
            <div>
                <label for="blood_type">Blood Type:</label>
                <select name="blood_type" id="blood_type" required>
                    <option value="">-- Select Blood Type --</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            <div>
                <label for="weight">Weight (kg):</label>
                <input type="number" name="weight" id="weight" step="0.1" min="0" required>
            </div>
        </div>

        <label for="diseases">Select Diseases:</label>
        <select name="diseases[]" id="diseases" multiple>
            <?php foreach ($saved_diseases as $disease): ?>
                <option value="<?php echo $disease['id']; ?>">
                    <?php echo htmlspecialchars($disease['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small>Hold Ctrl (Windows) or Command (Mac) to select multiple diseases.</small>

        <hr>

        <!-- Buttons -->
        <div class="button-group">
            <button type="submit">Register</button>
            <a href="../login/">
                <button type="button" style="background-color: #5bc0de;">Login</button>
            </a>
        </div>

    </form>
</div>
</body>
</html>


