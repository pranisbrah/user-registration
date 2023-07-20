<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>Form</h2>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>
        
        <input type="submit" value="Register">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        $errors = array();

        // Validate required fields
        if (empty($name)) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password should be at least 6 characters long.';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (empty($errors)) {
            // Proceed with registration
            // Steps 4-7 will go here
        }
    }

    if (empty($errors)) {
        // Read existing user data from JSON file
        $usersData = file_get_contents('users.json');
        $users = json_decode($usersData, true);

        // Handle JSON decoding error
        if ($users === null) {
            $errors['general'] = 'Error decoding JSON file.';
        }

        // Proceed with adding the new user
        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create a new user associative array
            $newUser = array(
                'name' => $name,    
                'email' => $email,
                'password' => $hashedPassword
            );

            // Add the new user to the existing array
            $users[] = $newUser;

            // Write the updated array back to the JSON file
            $jsonData = json_encode($users);
            if (file_put_contents('users.json', $jsonData) === false) {
                $errors['general'] = 'Error writing to JSON file.';
            } else {
                // Registration successful
                echo '<div>Registration successful!</div>';
            }
        }
    }
    ?>

    <?php if (isset($errors['general'])): ?>
        <div>Error: <?php echo $errors['general']; ?></div>
    <?php endif; ?>
    <?php if (isset($errors['name'])): ?>
        <div>Error: <?php echo $errors['name']; ?></div>
    <?php endif; ?>
    <?php if (isset($errors['email'])): ?>
        <div>Error: <?php echo $errors['email']; ?></div>
    <?php endif; ?>
    <?php if (isset($errors['password'])): ?>
        <div>Error: <?php echo $errors['password']; ?></div>
    <?php endif; ?>
    <?php if (isset($errors['confirm_password'])): ?>
        <div>Error: <?php echo $errors['confirm_password']; ?></div>
    <?php endif; ?>

</body>
</html>
