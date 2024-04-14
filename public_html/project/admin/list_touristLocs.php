<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: " . get_url("home.php")));
}
?>
<?php 
$query = "SELECT id, location_id, language, currency, NationalID, name, ranking, description, rating, num_reviews, website, address, phone, write_review, monday_open, monday_close, tuesday_open, tuesday_close, wednesday_open, wednesday_close, thursday_open, thursday_close, friday_open, friday_close, saturday_open, saturday_close, sunday_open, sunday_close, popular_tour_title, primary_category, price, partner, tour_url, product_code FROM `tourist_info` ORDER BY id DESC";
$db = getDB();
$results = [];
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error Fetching Locations: " . var_export($e, true));
    flash("An error occurred, please try again", "danger");
}

$table = ["data" => $results, "title" => "List of Tourist Locations Data", "ignored_columns" => ["id"], "edit_url" => get_url("admin/edit_touristLoc.php")];
?>

<div class = "container-fluid">
    <?php render_table($table); ?>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>