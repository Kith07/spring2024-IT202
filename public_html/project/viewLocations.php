<?php
require(__DIR__ . "/../../partials/nav.php");

?>

<?php

$id = se($_GET, "id", -1, false);           //UCID: LM457
$output = [];                               //DATE: 4/16/2024
if ($id > -1) {
    $db = getDB();
    $query = "SELECT id, location_id, language, currency, NationalID, name, ranking, description, rating, num_reviews, website, address, phone, write_review, monday_open, monday_close, tuesday_open, tuesday_close, wednesday_open, wednesday_close, thursday_open, thursday_close, 
    friday_open, friday_close, saturday_open, saturday_close, sunday_open, sunday_close, popular_tour_title, primary_category, price, partner, tour_url, product_code FROM `tourist_info` WHERE id = :id";
    try {
        $stmt = $db->prepare($query);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result) {
            $output = $result;
        }
    } catch (PDOException $e) {
        error_log("Error fetching data: " . var_export($e, true));
        flash("An error occurred", "danger");
    }
} else {
    flash("Invalid ID passed", "warning");
    die(header("Location: " . get_url("locations.php")));
}

foreach ($output as $key => $value) {
    if (is_null($value)) {
        $output[$key] = "N/A";
    }
}

?>
<div class="container-fluid">
    <h3>Location: <?php se($output, "name", "Unknown"); ?></h3>
    <div>
        <a href="<?php echo get_url("locations.php"); ?>" class="btn btn-secondary">Back</a>
    </div>

    <?php render_tourist_card($output); ?>

</div>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../partials/flash.php");
?>