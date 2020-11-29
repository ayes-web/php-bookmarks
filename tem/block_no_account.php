<?php 
    if (isset($_COOKIE['token'])) {
        require_once 'tem/loginDB.php';
        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) {
          die("Failed to connect to database.");
        } 
        $token = $conn->real_escape_string(strip_tags(htmlspecialchars($_COOKIE['token'])));
      
        $query = "SELECT username, token FROM accounts WHERE token='$token'";
        $result = $conn->query($query);
        if (!$result) die("Can't find anything!");
      
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $username = $row['username'];
            }
        } else {
            die("<h1 class='text-center'>You are not logged in, redirecting you to <a href='login.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=login.php'>");
        }
    } else {
        die("<h1 class='text-center'>You are not logged in, redirecting you to <a href='login.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=login.php'>");
    }
?>