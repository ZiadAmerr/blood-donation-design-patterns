<!-- File: /views/volunteers/edit.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Volunteer</title>
</head>

<body>
    <h1>Edit Volunteer #<?php echo $volunteer->person_id; ?></h1>

    <!-- Basic Edit Form -->
    <form method="POST" action="?action=update">
        <input type="hidden" name="volunteer_id" value="<?php echo $volunteer->person_id; ?>">

        <p>
            <label>Name:<br>
                <input type="text" name="name"
                    value="<?php echo htmlspecialchars($volunteer->getName()); ?>"
                    required>
            </label>
        </p>

        <p>
            <label>Phone:<br>
                <input type="text" name="phone_number"
                    value="<?php echo htmlspecialchars($volunteer->getPhoneNumber()); ?>"
                    required>
            </label>
        </p>

        <p>
            <label>Is Available?
                <input type="checkbox" name="isAvailable"
                    <?php echo $volunteer->isAvailable ? 'checked' : ''; ?>>
            </label>
        </p>

        <button type="submit">Save Changes</button>
        <p><a href="?action=list">Back to List</a></p>
    </form>

    <hr>

    <!-- Skill Management -->
    <h2>Skills</h2>
    <ul>
        <?php foreach ($volunteer->skills as $skill): ?>
            <li>
                <?php echo htmlspecialchars($skill); ?>
                <a href="?action=remove_skill&volunteer_id=<?php echo $volunteer->person_id; ?>&skill_name=<?php echo urlencode($skill); ?>"
                    onclick="return confirm('Remove skill <?php echo htmlspecialchars($skill); ?>?');">
                    [Remove]
                </a>
            </li>
        <?php endforeach; ?>
        <?php if (empty($volunteer->skills)): ?>
            <li>No skills yet.</li>
        <?php endif; ?>
    </ul>

    <form method="POST" action="?action=add_skill&volunteer_id=<?php echo $volunteer->person_id; ?>">
        <p>
            <label>Add Skill:<br>
                <input type="text" name="skill_name" placeholder="e.g. Nursing or Driving" required>
            </label>
        </p>
        <button type="submit">Add Skill</button>
    </form>
</body>

</html>