<?php
require_once 'tem/loginDB.php';

function get_cookie($conn, $var) {
  return $conn->real_escape_string(strip_tags(htmlspecialchars($_COOKIE[$var])));
}
function get_post($conn, $var) {
  return $conn->real_escape_string(strip_tags(htmlspecialchars($_POST[$var])));
}
function startsWith ($string, $startString) { 
  $len = strlen($startString); 
  return (substr($string, 0, $len) === $startString); 
}
function endsWith($string, $endString) { 
    $len = strlen($endString); 
    if ($len == 0) { 
        return true; 
    } 
    return (substr($string, -$len) === $endString); 
} 

if (isset($_POST['message_post']) && isset($_COOKIE['token'])) { //If user makes bookmark
  require_once 'tem/loginDB.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) {
    die("Failed to connect to database.");
  } 
  $token = get_cookie($conn, 'token');

  $query = "SELECT username, token FROM accounts WHERE token='$token'";
  $result = $conn->query($query);
  if (!$result) die("Can't find anything!");

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $username = $row['username'];
      }
  } else {
      echo "<h1 class='text-center'>You are not logged in, redirecting you to <a href='signin.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=signin.php'>";
  }

  $message_post = get_post($conn, 'message_post');
  $query = "INSERT INTO sites (domain, owned_by) 
  VALUES ('$message_post', '$username')";

  $result = $conn->query($query);
  if (!$result) {
    die("Can't find anything!");
  }
  $conn->close();

  header("Location: ".$_SERVER['PHP_SELF'].$get_info);
  exit();
} else if (isset($_POST['delete_id']) && isset($_COOKIE['token'])) { //If user deletes bookmark
  require_once 'tem/loginDB.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) {
    die("Failed to connect to database.");
  } 
  $token = get_cookie($conn, 'token');

  $query = "SELECT username, token FROM accounts WHERE token='$token'";
  $result = $conn->query($query);
  if (!$result) die("Can't find anything!");

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $username = $row['username'];
      }
  } else {
      echo "<h1 class='text-center'>You are not logged in, redirecting you to <a href='signin.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=signin.php'>";
  }

  $delete = get_post($conn, 'delete_id');
  $query = "DELETE FROM sites WHERE id='$delete' AND owned_by='$username'";
  $result = $conn->query($query);
  if (!$result) {
      die("Can't find anything!");
  }
  header("Location: ".$_SERVER['PHP_SELF'].$get_info);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("tem/deps.html") ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The default page">
    <title>Home</title>
</head>
<body>
  <?php include("tem/topbar.php") ?>
  <div class="container">

    <?php
    if (isset($_COOKIE['token'])) {
      require_once 'tem/block_no_account.php';

      $query = "SELECT id, domain, owned_by FROM sites WHERE owned_by='$username'";
      $result = $conn->query($query);
      if (!$result) die("Fatal Error");

      //Displays all bookmarks for the user
      while($row = $result->fetch_assoc()) {
        $full = strip_tags(htmlspecialchars($row['domain']));
        if (startsWith($full, "https://")) {
          //Cleans display url
          $clean = str_replace("https://","",$full);
        } elseif (startsWith($clean, "http://")) {
          //Cleans display url
          $clean = str_replace("http://","",$full);
        } else {
          //If provided naked domain adds https to make the link valid
          $clean = $full;
          $full = "https://" . $full;
        }

        //Cleans "/" from the end of clean string
        if (endsWith($clean, "/")) {
          $clean = rtrim($clean, "/");
        }


        echo "
        <div class='bookmark'>
          <form action='home.php' method='POST'>
          <img width=32 height=32 src='https://i.olsh.me/icon?size=32&url=". $full . "'>
            <a target='_blank' href='". $full . "'><b>" . $clean . "</b></a>
            <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
            <button aria-label='delete site' class='clean-button' type='submit'><i class='delete-icon fa fa-trash'></i></button>
          </form>
        </div>";
      }
      $result->close();
      $conn->close();
    } else {
      die("<h1 class='text-center'>You are not logged in, redirecting you to <a href='signin.php'>this page</a></h1><meta http-equiv='refresh' content='1; url=signin.php'>");
    }
    ?>
    <form action='home.php' method='POST'>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <button type='submit' class='btn btn-primary'>Submit</button>
        </div>
        <input required='on' autocomplete='false' type='text' name='message_post' class='form-control' placeholder='Bookmark'>
      </div>
    </form>
  </div>
</body>
</html>