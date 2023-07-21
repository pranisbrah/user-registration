<?php
// Function to validate email address format
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password criteria
function validatePassword($password)
{
    // Validate password criteria (e.g., minimum length, special characters)
    // You can customize the validation rules based on your requirements
    if (strlen($password) < 6) {
        return false;
    }

    return true;
}

// Function to display error messages
function displayError($field, $message)
{
    echo "<p>Error in $field: $message</p>";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Perform validation
    $errors = [];

    if (empty($name)) {
        displayError('Name', 'Name is required');
    }

    if (empty($email)) {
        displayError('Email', 'Email address is required');
    } elseif (!validateEmail($email)) {
        displayError('Email', 'Invalid email address');
    }

    if (empty($password)) {
        displayError('Password', 'Password is required');
    } elseif (!validatePassword($password)) {
        displayError('Password', 'Password should be at least 6 characters long');
    }

    if ($password !== $confirm_password) {
        displayError('Confirm Password', 'Passwords do not match');
    }

    // If there are no validation errors, proceed with registration
    if (empty($errors)) {
        // Read users.json file and decode contents into an array
        $users = json_decode(file_get_contents('users.json'), true);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create an associative array with user information
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ];

        // Add the new user to the existing array
        $users[] = $newUser;

        // Encode the array back to JSON
        $json = json_encode($users);

        // Save the JSON data back to the users.json file
        if (file_put_contents('users.json', $json)) {
            echo "<div>Registration successful!</div>";
        } else {
            echo "<div>Error: Unable to register user. Please try again later.</div>";
        }
    }
}
?>