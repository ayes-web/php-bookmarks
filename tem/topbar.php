<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Bookmarks</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if($_SERVER['REQUEST_URI']=="/home.php"){echo"active";}?>">
            <a class="nav-link" href="/home.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <li class="nav-item <?php if($_SERVER['REQUEST_URI']=="/settings.php"){echo"active";}?>">
                <a class="nav-link" href="/settings.php"><i class="fas fa-cog"></i> Settings</a>
            </li>
            <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i> <?php
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
                        echo $row['username'];
                    }
                } else {
                    echo "Account";
                    $login = "not";
                }
            } else {
                echo "Account";
                $login = "not";
            }
            ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php
            if ($login == "not") {
                echo "<a class='dropdown-item' href='/signin.php'>Sign in</a>
                <a class='dropdown-item' href='/register.php'>Register</a>";
            } else {
                echo "<a class='dropdown-item' href='/account.php'><i class='fas fa-user'></i> Account</a><div class='dropdown-divider'></div>
                <a class='dropdown-item' href='/logout.php'><i class='fas fa-sign-out-alt'></i> Logout</a>";
            }
            ?>
        </div>
      </li>
        </ul>
    </div>
</nav>
