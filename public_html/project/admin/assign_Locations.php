<?php
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    redirect("home.php");
}

if (isset($_POST["users"]) && isset($_POST["places"])) {
    $user_ids = $_POST["users"];
    $places_ids = $_POST["places"];
    if (empty($user_ids) || empty($places_ids)) {
        flash("Both users and places need to be selected", "warning");          //UCID: LM457
    } else {                                                                    //DATE: 4/29/2024                           
        $db = getDB();
        $stmt = $db->prepare("SELECT user_id, places_id FROM UserLocations WHERE user_id = :uid AND places_id = :pid");
        $insert_stmt = $db->prepare("INSERT INTO UserLocations (user_id, places_id) VALUES (:uid, :pid)");
        $delete_stmt = $db->prepare("DELETE FROM UserLocations WHERE user_id = :uid AND places_id = :pid");
        foreach ($user_ids as $uid) {
            foreach ($places_ids as $pid) {
                try {
                    $stmt->execute([":uid" => $uid, ":pid" => $pid]);
                    $is_favorite = !!$stmt->fetchColumn();
                    if ($is_favorite) {
                        $delete_stmt->execute([":uid" => $uid, ":pid" => $pid]);
                        flash("Removed from Bucket List!", "success");
                    } else {
                        $insert_stmt->execute([":uid" => $uid, ":pid" => $pid]);
                        flash("Added to your Bucket List!", "success");
                    }
                } catch (PDOException $e) {
                    flash(var_export($e->errorInfo, true), "danger");
                }
            }
        }
    }
}

//get active places
$active_places = [];
$places = "";
if (isset($_GET["places"])) {                        //UCID: LM457
    $places = $_GET["places"];                      //DATE: 4/29/2024
    if (!empty($places)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, name FROM tourist_info WHERE name like :name LIMIT 25");
        try {
            $stmt->execute(["name" => "%$places%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                $active_places = $results;
            }
        } catch (PDOException $e) {
            flash(var_export($e->errorInfo, true), "danger");
        }
    } else {
        flash("Location must not be empty", "warning");
    }
}

//search for user by username
$users = [];
$username = "";
if (isset($_GET["username"])) {
    $username = se($_GET, "username", "", false);                       //UCID: LM457
    if (!empty($username)) {                                            //DATE: 4/29/2024   
        $db = getDB();
        $stmt = $db->prepare("SELECT Users.id, username, 
        (SELECT GROUP_CONCAT(name SEPARATOR ', ') from 
        UserLocations ut JOIN tourist_info t on ut.places_id = t.id WHERE ut.user_id = Users.id) as places
        from Users WHERE username like :username LIMIIT 25");
        try {
            $stmt->execute([":username" => "%$username%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                $users = $results;
            }
        } catch (PDOException $e) {
            flash(var_export($e->errorInfo, true), "danger");
        }
    } else {
        flash("Username must not be empty", "warning");
    }
}
?>
<div class="container-fluid">
    <h1>Assign Favorite Locations</h1>
    <form method="GET">
        <?php render_input(["type" => "search", "name" => "username", "placeholder" => "Username Search", "value" => $username]);/*lazy value to check if form submitted, not ideal*/ ?>
        <?php render_input(["type" => "search", "name" => "places", "placeholder" => "Location Search", "value" => $places]);/*lazy value to check if form submitted, not ideal*/ ?>
        <?php render_button(["text" => "Search", "type" => "submit"]); ?>
    </form>
    <form method="POST">
        <?php if (isset($username) && !empty($username)) : ?>
            <input type="hidden" name="username" value="<?php se($username, false); ?>" />
        <?php endif; ?>
        <table class="table">
            <thead>
                <th>Users</th>
                <th>Locations to Assign</th>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table class="table">
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td>
                                        <?php render_input(["type" => "checkbox", "id" => "user_".se($user, 'id', "", false), "name" => "users[]", "label" => se($user, "username", "", false), "value" => se($user, 'id', "", false)]); ?>

                                    </td>
                                    <td><?php se($user, "places", "No Favorite Locations"); ?></td>                 <!--UCID: LM457-->
                                </tr>                                                                               <!--DATE: 4/29/2024-->
                            <?php endforeach; ?>
                        </table>
                    </td>
                    <td>
                        <?php foreach ($active_places as $place) : ?>
                            <div>
                                <?php render_input(["type" => "checkbox", "id" => "places_".se($place, 'id', "", false), "name" => "places[]", "label" => se($place, "name", "", false), "value" => se($place, 'id', "", false)]); ?>

                            </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php render_button(["text" => "Toggle Favorite Locations", "type" => "submit", "color" => "secondary"]); ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>