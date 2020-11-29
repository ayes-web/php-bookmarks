<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("tem/deps.html") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
</head>

<body>
<?php
    include("tem/topbar.php");

    function get_post($conn, $var) {
        return $conn->real_escape_string(strip_tags(htmlspecialchars($_POST[$var])));
    }
    function get_cookie($conn, $var) {
        return $conn->real_escape_string(strip_tags(htmlspecialchars($_COOKIE[$var])));
    }

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['mode'])) { //login & register
        require_once 'tem/loginDB.php';
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) {
            die("Fatal Error");
        }

        $mode = get_post($conn, 'mode');
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');
        $token = md5(uniqid());

        if ($mode == 'login') { //login & update token
            $query = "UPDATE accounts SET token='$token'
            WHERE username='$username' AND password='$password'";

            if ($conn->query($query) === TRUE) {
                $cookie_name = "token";
                setcookie($cookie_name, $token, time() + (86400 * 30), '/');
            }
            $conn->close(); 
        } else if ($mode == 'register') { //register account & create token
            $query = "INSERT INTO accounts (username, password, token) 
            VALUES ('$username', '$password', '$token')";
        
            if ($conn->query($query) === TRUE) {
                echo "Account created!";
                $cookie_name = "token";
                setcookie($cookie_name, $token, time() + (86400 * 30), '/');
            } else {
                echo "Sorry, this username is taken.";
            }
            $conn->close();
        }

        header("Location: ".$_SERVER['PHP_SELF'].$get_info);
        exit();
    } else if (isset($_COOKIE['token'])) { //Shows current user
        require_once 'tem/loginDB.php';
        $conn = new mysqli($hn, $un, $pw, $db);

        $token = get_cookie($conn, 'token');

        $query = "SELECT username, token FROM accounts WHERE token='$token'";
        $result = $conn->query($query);
        if (!$result) die("Fatal Error");

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<h1 class='text-center'> Logged in as: " . $row['username'] . "</h1>";
            }
        } else {
            echo "<h1 class='text-center'>You are not logged in, redirecting you to <a href='signin.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=signin.php'>";
        }

        $conn->close(); 
    } else {
        echo "<h1 class='text-center'>You are not logged in, redirecting you to <a href='signin.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=signin.php'>";
    }
    ?>
</body>

</html>