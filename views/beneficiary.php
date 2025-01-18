<?php
// Include necessary files
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/BeneficiaryController.php';

// Instantiate the controller
$controller = new BeneficiaryController();

// Handle form submission and stock data
$message = $controller->handleRequest();
$bloodStock = $controller->getBloodStock();
$plasmaStock = $controller->getPlasmaStock();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Stock Summary</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Blood Stock Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Blood Type</th>
                <th>Total Amount</th>
                <th>Blood/Plasma</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($bloodStock as $bloodType => $totalAmount): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bloodType); ?></td>
                    <td><?php echo number_format($totalAmount, 2); ?> L</td>
                    <td>Blood</td>
                </tr>
            <?php endforeach; ?>

            <?php 
            foreach ($plasmaStock as $bloodType => $totalAmount): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bloodType); ?></td>
                    <td><?php echo number_format($totalAmount, 2); ?> mL</td>
                    <td>Plasma</td>
                </tr>
            <?php endforeach; ?>
            
            <?php if (empty($bloodStock) && empty($plasmaStock)): ?>
                <tr>
                    <td colspan="3">No blood stock data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="form-container">
        <h3>Request Blood or Plasma</h3>
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="beneficiary.php" method="POST">
            <label for="blood_type">Blood Type:</label>
            <select name="blood_type" id="blood_type">
                <?php foreach (BloodTypeEnum::getAllValues() as $bloodType) : ?>
                    <option value="<?php echo $bloodType; ?>"><?php echo $bloodType; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="amount">Amount (L or mL):</label>
            <input type="number" name="amount" id="amount" step="0.01" required>

            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="blood">Blood</option>
                <option value="plasma">Plasma</option>
            </select>

            <button type="submit">Request</button>
        </form>
    </div>

</body>
</html>
