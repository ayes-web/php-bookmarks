<!doctype html>
<html lang="en">

<head>
  <?php include("tem/deps.html") ?>
  <link href="tem/signin.css" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sign in">
  <title>Sign in</title>
</head>

<body class="text-center">
  <div class="container">
    <form action="account.php" method='POST' class="form-signin">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

      <label for="username" class="sr-only">Username</label>
      <input name="username" type="username" id="username" class="form-control" placeholder="Username" required autofocus>

      <label for="inputPassword" class="sr-only">Password</label>
      <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>

      <input type="text" name="mode" value="login" hidden required>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
    <div>Don't have account?<a href="register.php"> Register here.</a></div>
  </div>
</body>

</html>