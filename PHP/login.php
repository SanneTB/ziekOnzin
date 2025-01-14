<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../CSS/admin.css">
    <link rel="website icon" href="../Assets/Pictures/logonew.PNG">
    <title>Admin page</title>
</head>

<body>
<?php
session_start();

include("connections.php");
include("functions.php");

// Initialize message variables
$message_success = $message_info = $message_login = '';

// Logout logic
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Create account logic
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['create_account'])) {
    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $user_id = random_num(20);
        $query = "INSERT INTO users (user_id, user_name, password) VALUES ('$user_id', '$user_name', '$password')";
        mysqli_query($con, $query);
        $message_success = "Account created successfully!";
    } else {
        $message_info = "Please enter a username and password!";
    }
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
    $username_login = trim($_POST['username_login']);
    $password_login = trim($_POST['password_login']);

    if (!empty($username_login) && !empty($password_login)) {
        $query = "SELECT * FROM users WHERE user_name = '$username_login' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($password_login, $user_data['password'])) {
                $_SESSION['user_id'] = $user_data['user_id'];
                // Redirect to appropriate page based on user role
                if ($user_data['admin']) {
                    header("Location: admincontrol.php");
                    exit();
                } else {
                    header("Location: employeecontrol.php");
                    exit();
                }
            } else {
                $message_login = "Wrong username or password!";
            }
        } else {
            $message_login = "Wrong username or password!";
        }
    } else {
        $message_info = "Please enter both a username and password!";
    }
}

// Check if the user is logged in
$user_data = check_login($con);
?>

<!-- JavaScript to display alerts only if there is a message -->
<script type="text/javascript">
    window.onload = function() {
        <?php if (!empty($message_success)) echo "alert('$message_success');" ?>
        <?php if (!empty($message_info)) echo "alert('$message_info');" ?>
        <?php if (!empty($message_login)) echo "alert('$message_login');" ?>
    };
</script>



<div class="welcome">
    <?php
     if ($user_data) {
echo "Welcome back, " . $user_data['user_name'] . ".";
     }
?>
</div>

<div class="container" id="container">
    <div class="form-container sign-up">
        <form method="post">
            <h1>Create an account</h1>
            <input id="text" type="text" placeholder="Username" name="user_name">
            <input id="text" type="password" placeholder="Password" name="password">
            <button type="submit" name="create_account">Create account</button>
        </form>
    </div>
    <div class="form-container sign-in">
        <form method="post">
            <h1>Sign In as Admin or Employee</h1>
            <input id="text" type="text" placeholder="Username" name="username_login">
            <input id="text" type="password" placeholder="Password" name="password_login">
            <a target="_blank" href="https://youtu.be/itJ_DJVKAW0?si=DZLxoX2XeIlO8u6L&t=0">Forgot Your Password?</a>
            <button type="submit" name="login">Sign In</button>
            <?php
            if ($user_data) {
            echo '<form method="post"><button type="submit" name="logout">Logout</button></form>';
            }
            ?>
        </form>
    </div>
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Admin or Employee</h1>
                <p>Trying to log in as an Admin or Employee?</p>
                <button class="hidden" id="login">Sign in</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome back,</h1>
                <p>Trying to make an account?</p>
                <button class="hidden" id="register">Create an account</button>
            </div>
        </div>
    </div>
</div>

<script src="../Javascript/restart.js"></script>
</body>

</html>
