<?php
require_once(__DIR__ . "/../../partials/nav.php");

if (isset($_GET["user_id"]) && isset($_GET["places_id"])) {
    $user_id = $_GET["user_id"];
    $place_id = $_GET["places_id"];
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM UserLocations WHERE user_id = :user_id AND places_id = :places_id");
    try {
        $stmt->execute([":user_id" => $user_id, ":places_id" => $place_id]);
        flash("Removed from bucket list successfully!", "success");
    } catch (PDOException $e) {
        flash("Error deleting association: " . $e->getMessage(), "danger");
    }
} else {
    flash("Missing user_id or place_id", "danger");
}

redirect("my_locations.php");   
?>
