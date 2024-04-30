<?php
require_once(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to perform this action", "warning");
    redirect("home.php");
}

if (isset($_GET["username"])) {
    $username = $_GET["username"];                      //UCID: LM457
    $partialName = "%$username%";                       //DATE: 4/29/2024

    $db = getDB();
    $stmt = $db->prepare("DELETE FROM UserLocations WHERE user_id IN (SELECT id FROM Users WHERE username LIKE :username)");

    try {
        $stmt->execute([":username" => $partialName]);
        flash("Associations removed successfully", "success");
    } catch (PDOException $e) {
        flash("Error removing associations: " . $e->getMessage(), "danger");
    }
} else {
    flash("Missing username flag", "danger");
}
redirect("admin/location_Associations.php");

?>
<?php require(__DIR__ . "/../../../partials/flash.php");?>