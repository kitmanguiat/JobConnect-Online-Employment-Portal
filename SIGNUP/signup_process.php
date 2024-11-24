<?php
     require_once '../DATABASE/dbConnection.php';
     require_once '../SIGNUP/crudUsers.php';

     if($_SERVER["REQUEST_METHOD"] == "POST"){

        $database = new Database();
        $db = $database->getConnect();

        $user = new User($db);
        $user->username = htmlspecialchars(trim($_POST['username']));
        $user->email = htmlspecialchars(trim($_POST['email']));
        $user->password = htmlspecialchars(trim($_POST['password']));
        $user->role = htmlspecialchars(trim($_POST['role']));
        $user->created_at = date("Y-m-d");

    
     }

     if ($user->create()){
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
            text: 'Data was Succesfully inserted!',
            icon: 'info'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = '../LOGIN/login.php';
            }
        });
        </script>
            
        </body>
        </html> ";
     }

     else {
        echo "error";
     }


?>