<!-- views/volunteers/index.php -->
<h1>Volunteers</h1>

<p><a href="?action=create">Create New Volunteer</a></p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Available</th>
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
            <?php foreach ($volunteers as $vol): ?>
                <tr>
                    <td><?php echo htmlspecialchars($vol->getName()); ?></td>
                    <td><?php echo htmlspecialchars($vol->getPhoneNumber()); ?></td>
                    <td><?php echo $vol->isAvailable ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php echo implode(', ', $vol->skills); ?>
                    </td>
                    <td>
                        <a href="?action=edit&volunteer_id=<?php echo $vol->person_id; ?>">Edit</a>
                        |
                        <a href="?action=delete&volunteer_id=<?php echo $vol->person_id; ?>"
                            onclick="return confirm('Are you sure you want to delete this volunteer?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>