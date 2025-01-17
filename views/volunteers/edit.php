<!-- views/volunteers/edit.php -->
<h1>Edit Volunteer #<?php echo $volunteer->person_id; ?></h1>

<form method="POST" action="?action=update">
    <input type="hidden" name="volunteer_id" value="<?php echo $volunteer->person_id; ?>">

    <p>
        <label>Name:<br>
            <input type="text" name="name"
                value="<?php echo htmlspecialchars($volunteer->getName()); ?>">
        </label>
    </p>

    <p>
        <label>Phone:<br>
            <input type="text" name="phone_number"
                value="<?php echo htmlspecialchars($volunteer->getPhoneNumber()); ?>">
        </label>
    </p>

    <p>
        <label>Available:
            <input type="checkbox" name="isAvailable" <?php echo $volunteer->isAvailable ? 'checked' : ''; ?>>
        </label>
    </p>

    <button type="submit">Save Changes</button>
</form>

<hr>

<!-- SKILLS Management -->
<h2>Skills</h2>
<ul>
    <?php foreach ($volunteer->skills as $skill): ?>
        <li>
            <?php echo htmlspecialchars($skill); ?>
            <a href="?action=remove_skill&volunteer_id=<?php echo $volunteer->person_id; ?>&skill_name=<?php echo urlencode($skill); ?>">
                [Remove]
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<form method="POST" action="?action=add_skill&volunteer_id=<?php echo $volunteer->person_id; ?>">
    <p>
        <input type="text" name="skill_name" placeholder="New skill">
        <button type="submit">Add Skill</button>
    </p>
</form>

<hr>
<p><a href="?action=list">Back to List</a></p>