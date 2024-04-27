<?php

session_start();
require(__DIR__ . "/../../../lib/functions.php");
if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");        //UCID: LM457
    redirect("home.php");                   //DATE: 4/16/2024
}

$id = se($_GET, "id", -1, false);
if ($id < 1) {
    flash("Invalid ID passed. Cannot delete", "danger");
    redirect("admin/list_touristLocs.php");
}

$db = getDB();
$query = "DELETE FROM `tourist_info` WHERE id = :id";
try {
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $id]);
    flash("Deleted record with ID = $id", "success");
} catch (Exception $e) {
    error_log("Error deleting Tourist Location $id" . var_export($e, true));
    flash("Error deleting record", "danger");
}
redirect("admin/list_touristLocs.php");