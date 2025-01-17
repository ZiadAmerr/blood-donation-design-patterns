<?php
// File: volunteers.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/VolunteerController.php';

// 1. Instantiate controller & fetch all volunteers
$controller = new VolunteerController();
$volunteers = $controller->getVolunteers();
// Make sure the getVolunteers() method returns an array of data
// e.g. each item: ['name' => 'John', 'phone' => '123456', 'address' => 'Somewhere', 'skills' => 'Nursing,Driving']

?>
<?php
// File: /views/volunteers/index.php
// We assume your VolunteerController or code passes in $volunteers = array of Volunteer objects.

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Volunteers</title>
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

        h2 {
            margin-top: 0;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #4cae4c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        nav {
            background-color: #d9534f;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #c9302c;
        }

        header {
            background-color: #d9534f;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333;
            color: white;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <header>
        <h1>Volunteers Management</h1>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="money_donations.php">Money Donations</a>
        <a href="blood_donations.php">Blood Donations</a>
        <a href="beneficiary.php">Beneficiary</a>
        <a href="events.php">Events</a>
        <a href="volunteers.php?action=list" style="background-color: #c9302c;">Volunteers</a>
        <a href="other.php">Other</a>
    </nav>

    <div class="container">
        <h2>Volunteers</h2>
        <button onclick="window.location.href='volunteers.php?action=create'">Add Volunteer</button>

        <h3>List of Volunteers</h3>
        <table>
            <thead>
                <tr>
                    <th>Person ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Skills</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($volunteers)): ?>
                    <tr>
                        <td colspan="5">No volunteers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($volunteers as $volunteer): ?>
                        <tr>
                            <td><?php echo $volunteer->person_id; ?></td>
                            <td><?php echo htmlspecialchars($volunteer->getName()); ?></td>
                            <td><?php echo htmlspecialchars($volunteer->getPhoneNumber()); ?></td>
                            <td>
                                <?php
                                // $volunteer->skills is an array of skill strings
                                echo implode(', ', $volunteer->skills);
                                ?>
                            </td>
                            <td>
                                <button onclick="window.location.href='volunteers.php?action=edit&volunteer_id=<?php echo $volunteer->person_id; ?>'">
                                    Edit
                                </button>
                                <button onclick="if(confirm('Are you sure to delete this volunteer?')) 
                                    window.location.href='volunteers.php?action=delete&volunteer_id=<?php echo $volunteer->person_id; ?>'">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Blood Donation Management System. All rights reserved.
    </footer>

</body>

</html>