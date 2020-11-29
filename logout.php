<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("tem/deps.html") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <?php 
    include("tem/topbar.php");
    if (isset($_COOKIE['token'])) {
        unset($_COOKIE['token']); 
        setcookie('token', null, -1, '/'); 
        echo "<h1 class='text-center'>You have been logged out, have a nice day!</h1>";
    } else {
        echo "<h1 class='text-center'>You aren't logged in</h1>";
    }
    ?>

</body>
</html>