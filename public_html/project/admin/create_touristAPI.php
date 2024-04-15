<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
?>

<?php
if (isset($_POST["nationallocation_id"])) {
    $nationalLocID = se($_POST, "nationallocation_id", "", false);
    if ($nationalLocID) {
        $selectedPlace = fetch_touristPlace($nationalLocID);
        if(!$selectedPlace){
            flash("Tourist Place Not Found", "warning");
        }
        error_log("API Data" . var_export($selectedPlace, true));
    } else {
        flash("Invalid National ID", "warning");
    }

    if ($selectedPlace) {
        $db = getDB();

        $basicInfoQuery = "INSERT INTO `tourist_info` (`location_id`, `language`, `currency`, `NationalID`, `name`, `ranking`, `description`, `rating`, `num_reviews`, `website`, `address`, `phone`, `write_review`, `monday_open`, `monday_close`, `tuesday_open`, `tuesday_close`, `wednesday_open`, `wednesday_close`, `thursday_open`, `thursday_close`, `friday_open`, `friday_close`, `saturday_open`, `saturday_close`, `sunday_open`, `sunday_close`, `popular_tour_title`, `primary_category`, `price`, `partner`, `tour_url`, `product_code`, `is_api`) ";
        $basicInfoQuery .= "VALUES (:location_id, :language, :currency, :nationalID, :name, :ranking, :description, :rating, :num_reviews, :website, :address, :phone, :write_review, :monday_open, :monday_close, :tuesday_open, :tuesday_close, :wednesday_open, :wednesday_close, :thursday_open, :thursday_close, :friday_open, :friday_close, :saturday_open, :saturday_close, :sunday_open, :sunday_close, :popular_tour_title, :primary_category, :price, :partner, :tour_url, :product_code, :is_api)";

        try {
            $stmt = $db->prepare($basicInfoQuery);
            $stmt->execute([
                ':location_id' => '45963',
                ':language' => 'en_US',
                ':currency' => 'USD',
                ':nationalID' => $nationalLocID,
                ':name' => $selectedPlace["name"] ?? null,
                ':ranking' => $selectedPlace["ranking"] ?? null,
                ':description' => $selectedPlace["description"] ?? null,
                ':rating' => $selectedPlace["rating"] ?? null,
                ':num_reviews' => $selectedPlace["num_reviews"] ?? null,
                ':website' => $selectedPlace["website"] ?? null,
                ':address' => $selectedPlace["address"] ?? null,
                ':phone' => $selectedPlace["phone"] ?? null,
                ':write_review' => $selectedPlace["write_review"] ?? null,
                ':monday_open' => $selectedPlace["hours"]["week_ranges"][0][0]["open_time"] ?? null,
                ':monday_close' => $selectedPlace["hours"]["week_ranges"][0][0]["close_time"] ?? null,
                ':tuesday_open' => $selectedPlace["hours"]["week_ranges"][1][0]["open_time"] ?? null,
                ':tuesday_close' => $selectedPlace["hours"]["week_ranges"][1][0]["close_time"] ?? null,
                ':wednesday_open' => $selectedPlace["hours"]["week_ranges"][2][0]["open_time"] ?? null,
                ':wednesday_close' => $selectedPlace["hours"]["week_ranges"][2][0]["close_time"] ?? null,
                ':thursday_open' => $selectedPlace["hours"]["week_ranges"][3][0]["open_time"] ?? null,
                ':thursday_close' => $selectedPlace["hours"]["week_ranges"][3][0]["close_time"] ?? null,
                ':friday_open' => $selectedPlace["hours"]["week_ranges"][4][0]["open_time"] ?? null,
                ':friday_close' => $selectedPlace["hours"]["week_ranges"][4][0]["close_time"] ?? null,
                ':saturday_open' => $selectedPlace["hours"]["week_ranges"][5][0]["open_time"] ?? null,
                ':saturday_close' => $selectedPlace["hours"]["week_ranges"][5][0]["close_time"] ?? null,
                ':sunday_open' => $selectedPlace["hours"]["week_ranges"][6][0]["open_time"] ?? null,
                ':sunday_close' => $selectedPlace["hours"]["week_ranges"][6][0]["close_time"] ?? null,
                ':popular_tour_title' => $selectedPlace["offer_group"]["offer_list"][0]['title'] ?? null,
                ':primary_category' => $selectedPlace["offer_group"]["offer_list"][0]['primary_category'] ?? null,
                ':price' => $selectedPlace["offer_group"]["offer_list"][0]['price'] ?? null,
                ':partner' => $selectedPlace["offer_group"]["offer_list"][0]['partner'] ?? null,
                ':tour_url' => $selectedPlace["offer_group"]["offer_list"][0]['url'] ?? null,
                ':product_code' => $selectedPlace["offer_group"]["offer_list"][0]['product_code'] ?? null,
                ':is_api' => 1
            ]);
            flash("Inserted Successfully " . $db->lastInsertId(), "success");
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                flash("Duplicate Entry", "warning");
            } else {
                error_log("Something broke with the query" . var_export($e, true));
                flash("An error occurred", "danger");
            }
        }
    }
}

?>

<div class="container-fluid">
    <h3>Fetch Tourist Location</h3>
    <form method="POST">
        <?php render_input(["type" => "text", "name" => "nationallocation_id", "placeholder" => "Enter ID", "label" => "National LocationID", "rules" => ["required" => "required"]]); ?>
        <?php render_button(["text" => "Fetch Details", "type" => "submit"]); ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>