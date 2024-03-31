<?php
require(__DIR__ . "/../../partials/nav.php");
reset_session();
?>
<form onsubmit="return validate(this)" method="POST">
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" required />
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" required maxlength="30" />                           //UCID: LM457
    </div>                                                                                      //Date: 3/31/2024
    <div>
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" required minlength="8" />
    </div>
    <div>
        <label for="confirm">Confirm</label>
        <input type="password" name="confirm" required minlength="8" />
    </div>
    <input type="submit" value="Register" />
</form>
<script>
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success
        var email = form.email.value;
        var username = form.username.value;
        var password = form.password.value;
        var confirm = form.confirm.value;
        
        var hasError = false;

        function is_valid_email(email) {
            const emailRegEx = /^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6})*$/;
            return emailRegEx.test(email);
        }

        function is_valid_username(username) {
            const usernameRegEx = /^[a-z0-9_-]{3,16}$/;                                                      //UCID: LM457
            return usernameRegEx.test(username);                                                             //Date: 3/31/2024
        }

        if (email === "") {
            flash("[JS] Email must not be empty", "danger");
            hasError = true;
        } else if (!is_valid_email(email)) {
            flash("[JS] Invalid email address", "danger");
            hasError = true;
        }

        if (username === "") {
            flash("[JS] Username must not be empty", "danger");
            hasError = true;
        } else if (!is_valid_username(username)) {
            flash("[JS] Username must only contain 3-16 characters a-z, 0-9, _, or -", "danger");
            hasError = true;
        }

        if (password === "") {
            flash("[JS] Password must not be empty", "danger");
            hasError = true;
        } else if (password.length < 8) {
            flash("[JS] Password too short", "danger");
            hasError = true;
        }

        if (password !== confirm) {
            flash("[JS] Passwords must match", "danger");
            hasError = true;
        }

        return !hasError;
    }
</script>

<?php
//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"]) && isset($_POST["username"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $confirm = se($_POST, "confirm", "", false);
    $username = se($_POST, "username", "", false);
    //TODO 3
    $hasError = false;
    if (empty($email)) {
        flash("Email must not be empty", "danger");
        $hasError = true;
    }
    //sanitize
    $email = sanitize_email($email);
    //validate
    if (!is_valid_email($email)) {
        flash("Invalid email address", "danger");
        $hasError = true;
    }
    if (!is_valid_username($username)) {
        flash("Username must only contain 3-16 characters a-z, 0-9, _, or -", "danger");        //UCID: LM457
        $hasError = true;                                                                       //Date: 3/31/2024
    }
    if (empty($password)) {
        flash("password must not be empty", "danger");
        $hasError = true;
    }
    if (empty($confirm)) {
        flash("Confirm password must not be empty", "danger");
        $hasError = true;
    }
    if (!is_valid_password($password)) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (
        strlen($password) > 0 && $password !== $confirm
    ) {
        flash("Passwords must match", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        //TODO 4
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (email, password, username) VALUES(:email, :password, :username)");
        try {
            $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
            flash("Successfully registered!", "success");
        } catch (PDOException $e) {
            users_check_duplicate($e->errorInfo);
        } catch (Exception $e) {
            flash("An unexpected error occurred, please try again", "danger");
        }
    }
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>