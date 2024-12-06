<?php
require_once '../DATABASE/dbConnection.php';
require_once '../SIGNUP/crudUsers.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $user = new User($db);
    $user->username = htmlspecialchars(trim($_POST['username']));
    $user->email = htmlspecialchars(trim($_POST['email']));
    $user->password = htmlspecialchars(trim($_POST['password']));
    $user->role = htmlspecialchars(trim($_POST['role']));
    $user->created_at = date("Y-m-d");

    // Password length validation
    if (strlen($user->password) < 8) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Validation</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Password must be at least 8 characters long.',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
        </script>
        </body>
        </html>";
        exit; // Stop further execution
    }

    // If password is valid, proceed to hash it and create the user
    $user->password = password_hash($user->password, PASSWORD_DEFAULT);

    if ($user->create()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Success!',
            text: 'Account successfully created!',
            icon: 'success'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../LOGIN/login.php';
            }
        });
        </script>
        </body>
        </html>";
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to create account. Please try again.',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
        </script>
        </body>
        </html>";
    }
}
?>
