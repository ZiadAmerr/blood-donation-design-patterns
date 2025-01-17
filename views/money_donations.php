<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/MoneyDonationController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyStock.php';

$controller = new MoneyDonationController();
$donations = $controller->getDonations();
$moneyStock = MoneyStock::getInstance();
$totalCash = $moneyStock->getTotalCash();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Money Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #d9534f;
        }
        .search-container {
            margin: 20px 0;
        }
        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('donationsTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[1];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function checkVault() {
            alert("Total Cash in Vault: <?php echo number_format($totalCash, 2); ?>");
        }
    </script>
</head>
<body>

<div class="container">
    <button onclick="window.location.href='index.php'">Home</button>
    <h2>Money Donations</h2>
    <button onclick="window.location.href='donate_money.php'">Donate Money</button>
    <button onclick="checkVault()">Check Vault</button>

    <div class="search-container">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by National ID">
    </div>

    <h3>Past Donations</h3>
    <table id="donationsTable">
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>National ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($donations)): ?>
                <tr>
                    <td colspan="5">No donations found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['national_id']); ?></td>
                        <td><?php echo number_format($donation['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($donation['date']); ?></td>
                        <td><?php echo htmlspecialchars($donation['type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>