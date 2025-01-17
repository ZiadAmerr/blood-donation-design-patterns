<!-- File: /views/volunteers/index.php -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Volunteers List</title>
</head>

<body>
    <h1>Volunteers</h1>
    <p><a href="?action=create">Create New Volunteer</a></p>

    <?php if (empty($volunteers)): ?>
        <p>No volunteers found.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Available</th>
                    <th>Skills</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($volunteers as $vol): ?>
                    <tr>
                        <td><?php echo $vol->person_id; ?></td>
                        <td><?php echo htmlspecialchars($vol->getName()); ?></td>
                        <td><?php echo htmlspecialchars($vol->getPhoneNumber()); ?></td>
                        <td><?php echo $vol->isAvailable ? 'Yes' : 'No'; ?></td>
                        <td>
                            <?php echo implode(', ', $vol->skills); ?>
                        </td>
                        <td>
                            <a href="?action=edit&volunteer_id=<?php echo $vol->person_id; ?>">Edit</a> |
                            <a href="?action=delete&volunteer_id=<?php echo $vol->person_id; ?>"
                                onclick="return confirm('Are you sure you want to delete this volunteer?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>