<!-- File: /views/volunteers/create.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Create Volunteer</title>
</head>

<body>
    <h1>Create Volunteer</h1>
    <form method="POST" action="?action=store">
        <p>
            <label>Name:<br>
                <input type="text" name="name" required>
            </label>
        </p>
        <p>
            <label>Date of Birth (YYYY-MM-DD):<br>
                <input type="date" name="dob" required>
            </label>
        </p>
        <p>
            <label>National ID:<br>
                <input type="text" name="national_id" required>
            </label>
        </p>
        <p>
            <label>Address ID:<br>
                <input type="number" name="address_id" placeholder="1" required>
            </label>
        </p>
        <p>
            <label>Phone Number:<br>
                <input type="text" name="phone_number" required>
            </label>
        </p>
        <button type="submit">Create Volunteer</button>
        <p><a href="?action=list">Back to List</a></p>
    </form>
</body>

</html>