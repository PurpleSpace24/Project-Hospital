<?php
/**
 * @var $link
 */
session_start();
require_once "configs/config.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: " . $_SESSION['go_to']);
    exit;
}


$username = $password = $hashed_password = $id = $user_egn = "";
$username_err = $password_err = $login_err = $logedas_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (isset($_POST['_login_as'])) {
        $_loged_as = $_POST['_login_as'];
    } else {
        $logedas_err = "Please choose login as.";
    }

    if (empty($username_err) && empty($password_err) && empty($logedas_err)) {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $stmt = $link->query($sql);

        $sql_access = "SELECT ua.permission_id 
                        FROM users u 
                        JOIN user_access ua 
                        ON u.id = ua.user_id 
                        WHERE u.username='$username'";

        $stmt_1 = $link->query($sql_access);
        $access_id = 0;

        if ($stmt_1->num_rows > 0) {
            while ($row = $stmt_1->fetch_assoc()) {
                $access_id = $row['permission_id'];
            }
        }


        $go_to = "";

        $englined = true;
        if ($stmt->num_rows > 0) {
            if ($_loged_as == '_doctor' && $access_id == 1) {
                $go_to = "doctor_page.php";
                $_SESSION['go_to'] = "doctor_page.php";
            } else if ($_loged_as == '_director' && $access_id == 2) {
                $go_to = "director_page.php";
                //****************/
                $_SESSION['go_to'] = "director_page.php";

            } else if ($_loged_as == '_patient' && $access_id == 3) {
                $go_to = "results.php";
                $_SESSION['go_to'] = "results.php";
            } else if ($_loged_as == '_admin' && $access_id == 4) {
                $go_to = "admin_page.php";
                $_SESSION['go_to'] = "admin_page.php";

            } else {
                $englined = false;
                $login_err = "Not allowed to log in.";
                //you access is =?(get with query?) You cant log int
            }
        } else {
            $login_err = "Ooops something went wrong.";
        }

        if ($englined) {
            $param_username = $username;

            if ($stmt->num_rows > 0) {
                while ($row_01 = $stmt->fetch_assoc()) {
                    //echo print_r($row_01);

                    $id = $row_01['id'];
                    $username = $row_01['username'];
                    $hashed_password = $row_01['password'];
                    $user_egn = $row_01['egn'];

                    if (password_verify($password, $hashed_password)) {

                        session_start();

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["egn"] = $user_egn;


                        header("Location: " . $go_to);
                        exit;
                    } else {
                        $login_err = "Invalid password.";
                    }
                }//end while
            } else {
                $login_err = "Invalid username.";
            }
            //session_destroy();   
        }
    }

    /*if (match_found_in_database()) {
        $_SESSION['loggedin'] = true;
        $_SESSION['egn'] = $user_egn; // $username coming from the form, such as $_POST['username']
        // something like this is optional, of course
    }
*/
  
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Hospital VY</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Srisakdi" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"/>
    <link rel="stylesheet" href="assets/typography.css"/>
    <link rel="stylesheet" href="assets/responsive.css"/>

</head>

<body>
<?php
include("header/header.php");
?>
<section class="hr-line">
    <hr>
</section>

<section class="form-login-signup">
    <h1 class="title-login-signup">Login</h1>
    <p>Please fill in your credentials to log in.</p>
    <br>

    <?php
    if (!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">

            <label>Username</label>
            <input type="text" name="username"
                   class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                   value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password"
                   class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>


        <label>Login as:</label>
        <div class="form-group">
            <select class="options" name="_login_as">
                <option value="none" selected disabled hidden>Log in as:</option>
                <option value="_admin">Admin</option>
                <option value="_doctor">Doctor</option>
                <option value="_director">Director</option>
                <option value="_patient">Patient</option>
            </select>
            <span class="invalid-feedback"><?php echo $logedas_err; ?></span>
        </div>


        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Log in">
        </div>
    </form>
</section>


<hr>
<?php
include("footer/footer.php");
?>
<script src="script.js"></script>
</body>
</html>