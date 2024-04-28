<?php
// This is an internal API endpoint to receive data and do something with it
// this is not a standalone page
//Note: no nav.php here because this is a temporary stop, it's not a user page
require(__DIR__ . "/../../../lib/functions.php");
session_start();
if (isset($_GET["places_id"]) && is_logged_in()) {
    //TODO implement purchase logic (for now it's all free)
    $db = getDB();
    $query = "INSERT INTO `UserLocations` (user_id, places_id) VALUES (:user_id, :places_id)";
    try {
        $stmt = $db->prepare($query);
        $stmt->execute([":user_id" => get_user_id(), ":places_id" => $_GET["places_id"]]);
        flash("Congrats you added this location to your Travel Bucket List!", "success");
        redirect("my_locations.php");
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
            flash("This location has already been favorited", "danger");
        } else {
            flash("Unhandled error occurred", "danger");
        }
        error_log("Error adding location: " . var_export($e, true));
    }
}

//for now I'll redirect, but if I later use AJAX I need to send a reply instead
redirect("locations.php");