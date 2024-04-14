<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
?>

<?php 
if(isset($_POST["nationallocation_id"])){
    $selectedPlace = [];
    foreach($_POST as $key => $value){
        if(!in_array($key, ["nationallocation_id", "location_name", "location_ranking", "location_description", "location_rating", "location_num_reviews", "location_website", "location_address", "location_phone", "write_review_link", "location_monday_open", "location_monday_close", "location_tuesday_open", "location_tuesday_close", "location_wednesday_open", "location_wednesday_close", "location_thursday_open", "location_thursday_close", "location_friday_open", "location_friday_close", "location_saturday_open", "location_saturday_close", "location_sunday_open", "location_sunday_close", "location_popular_tours_title", "location_popular_tours_category", "location_popular_tour_price", "location_popular_tour_partner", "location_popular_tour_link", "location_popular_tour_code"])){
            unset($_POST[$key]);    
        }
        $selectedPlace = $_POST;
    }

    if ($selectedPlace) {
        $db = getDB();
    
        $basicInfoQuery = "INSERT INTO `tourist_info` (`location_id`, `language`, `currency`, `NationalID`, `name`, `ranking`, `description`, `rating`, `num_reviews`, `website`, `address`, `phone`, `write_review`, `monday_open`, `monday_close`, `tuesday_open`, `tuesday_close`, `wednesday_open`, `wednesday_close`, `thursday_open`, `thursday_close`, `friday_open`, `friday_close`, `saturday_open`, `saturday_close`, `sunday_open`, `sunday_close`, `popular_tour_title`, `primary_category`, `price`, `partner`, `tour_url`, `product_code`) ";
        $basicInfoQuery .= "VALUES (:location_id, :language, :currency, :nationalID, :name, :ranking, :description, :rating, :num_reviews, :website, :address, :phone, :write_review, :monday_open, :monday_close, :tuesday_open, :tuesday_close, :wednesday_open, :wednesday_close, :thursday_open, :thursday_close, :friday_open, :friday_close, :saturday_open, :saturday_close, :sunday_open, :sunday_close, :popular_tour_title, :primary_category, :price, :partner, :tour_url, :product_code)";
    
        try {
            $stmt = $db->prepare($basicInfoQuery);
                $stmt->execute([
                    ':location_id' => '45963',
                    ':language' => 'en_US',
                    ':currency' => 'USD',
                    ':nationalID' => $selectedPlace["nationallocation_id"] ?? null,
                    ':name' => $selectedPlace["location_name"] ?? null,
                    ':ranking' => $selectedPlace["location_ranking"] ?? null,
                    ':description' => $selectedPlace["location_description"] ?? null,
                    ':rating' => $selectedPlace["location_rating"] ?? nuLl,
                    ':num_reviews' => $selectedPlace["location_num_reviews"] ?? null,
                    ':website' => $selectedPlace["location_website"] ?? null,
                    ':address' => $selectedPlace["location_address"] ?? null,
                    ':phone' => $selectedPlace["location_phone"] ?? null,
                    ':write_review' => $selectedPlace["write_review"] ?? null,
                    ':monday_open' => $selectedPlace["location_monday_open"] ?? null,
                    ':monday_close' => $selectedPlace["location_monday_close"] ?? null,
                    ':tuesday_open' => $selectedPlace["location_tuesday_open"] ?? null,
                    ':tuesday_close' => $selectedPlace["location_tuesday_close"] ?? null,
                    ':wednesday_open' => $selectedPlace["location_wednesday_open"] ?? null,
                    ':wednesday_close' => $selectedPlace["location_wednesday_close"] ?? null,
                    ':thursday_open' => $selectedPlace["location_thursday_open"] ?? null,
                    ':thursday_close' => $selectedPlace["location_thursday_close"] ?? null,
                    ':friday_open' => $selectedPlace["location_friday_open"] ?? null,
                    ':friday_close' => $selectedPlace["location_friday_close"] ?? null,
                    ':saturday_open' => $selectedPlace["location_saturday_open"] ?? null,
                    ':saturday_close' => $selectedPlace["location_saturday_close"] ?? null,
                    ':sunday_open' => $selectedPlace["location_sunday_open"] ?? null,
                    ':sunday_close' => $selectedPlace["location_sunday_close"] ?? null,
                    ':popular_tour_title' => $selectedPlace["location_popular_tours_title"] ?? null,
                    ':primary_category' => $selectedPlace["location_popular_tours_category"] ?? null,
                    ':price' => $selectedPlace["location_popular_tour_price"] ?? null,
                    ':partner' => $selectedPlace["location_popular_tour_partner"] ?? null,
                    ':tour_url' => $selectedPlace["location_popular_tour_link"] ?? null,
                    ':product_code' => $selectedPlace["location_popular_tour_code"] ?? null
                ]);
                flash("Inserted Successfully " . $db->lastInsertId(), "success");
            }
            catch (PDOException $e) {
            error_log("Something broke with the query" . var_export($e, true));
            flash("An error occurred", "danger");
        }
    }
}

?>

<div class="container-fluid">
    <h3>Add Tourist Location</h3>
    <form method="POST">
        <?php render_input(["name" => "nationallocation_id", "placeholder" => "Enter ID", "label" => "National LocationID", "required" => true]); ?>
        <?php render_input(["name" => "location_name", "placeholder" => "Enter Location Name", "label" => "Location Name", "required" => true]); ?>
        <?php render_input(["name" => "location_ranking", "placeholder" => "Enter Location Ranking", "label" => "Location Ranking"]); ?>
        <?php render_input(["name" => "location_description", "placeholder" => "Enter Location Description", "label" => "Location Description"]); ?>
        <?php render_input(["name" => "location_rating", "placeholder" => "Enter Location Rating", "label" => "Location Rating"]); ?>
        <?php render_input(["name" => "location_num_reviews", "placeholder" => "Enter Location Number of Reviews", "label" => "Location Number of Reviews"]); ?>
        <?php render_input(["name" => "location_website", "placeholder" => "Enter Location Website", "label" => "Location Website"]); ?>
        <?php render_input(["name" => "location_address", "placeholder" => "Enter Location Address", "label" => "Location Address"]); ?>
        <?php render_input(["name" => "location_phone", "placeholder" => "Enter Location Phone", "label" => "Location Phone"]); ?>
        <?php render_input(["name" => "write_review_link", "placeholder" => "Enter Write a Review Link", "label" => "Write a Review Link"]); ?>
        <?php render_input(["name" => "location_monday_open", "placeholder" => "Enter Location Monday Open Time", "label" => "Location Monday Open Time"]); ?>
        <?php render_input(["name" => "location_monday_close", "placeholder" => "Enter Location Monday Close Time", "label" => "Location Monday Close Time"]); ?>
        <?php render_input(["name" => "location_tuesday_open", "placeholder" => "Enter Location Tuesday Open Time", "label" => "Location Tuesday Open Time"]); ?>
        <?php render_input(["name" => "location_tuesday_close", "placeholder" => "Enter Location Tuesday Close Time", "label" => "Location Tuesday Close Time"]); ?>
        <?php render_input(["name" => "location_wednesday_open", "placeholder" => "Enter Location Wednesday Open Time", "label" => "Location Wednesday Open Time"]); ?>
        <?php render_input(["name" => "location_wednesday_close", "placeholder" => "Enter Location Wednesday Close Time", "label" => "Location Wednesday Close Time"]); ?>
        <?php render_input(["name" => "location_thursday_open", "placeholder" => "Enter Location Thursday Open Time", "label" => "Location Thursday Open Time"]); ?>
        <?php render_input(["name" => "location_thursday_close", "placeholder" => "Enter Location Thursday Close Time", "label" => "Location Thursday Close Time"]); ?>
        <?php render_input(["name" => "location_friday_open", "placeholder" => "Enter Location Friday Open Time", "label" => "Location Friday Open Time"]); ?>
        <?php render_input(["name" => "location_friday_close", "placeholder" => "Enter Location Friday Close Time", "label" => "Location Friday Close Time"]); ?>
        <?php render_input(["name" => "location_saturday_open", "placeholder" => "Enter Location Saturday Open Time", "label" => "Location Saturday Open Time"]); ?>
        <?php render_input(["name" => "location_saturday_close", "placeholder" => "Enter Location Saturday Close Time", "label" => "Location Saturday Close Time"]); ?>
        <?php render_input(["name" => "location_sunday_open", "placeholder" => "Enter Location Sunday Open Time", "label" => "Location Sunday Open Time"]); ?>
        <?php render_input(["name" => "location_sunday_close", "placeholder" => "Enter Location Sunday Close Time", "label" => "Location Sunday Close Time"]); ?>
        <?php render_input(["name" => "location_popular_tours_title", "placeholder" => "Enter Location Popular Tours Title", "label" => "Location Popular Tours Title"]); ?>
        <?php render_input(["name" => "location_popular_tours_category", "placeholder" => "Enter Location Popular Tours Category", "label" => "Location Popular Tours Category"]); ?>
        <?php render_input(["name" => "location_popular_tour_price", "placeholder" => "Enter Location Popular Tour Price", "label" => "Location Popular Tour Price"]); ?>
        <?php render_input(["name" => "location_popular_tour_partner", "placeholder" => "Enter Location Popular Tour Partner", "label" => "Location Popular Tour Partner"]); ?>
        <?php render_input(["name" => "location_popular_tour_link", "placeholder" => "Enter Location Popular Tour Link", "label" => "Location Popular Tour Link"]); ?>
        <?php render_input(["name" => "location_popular_tour_code", "placeholder" => "Enter Location Popular Product Tour Code", "label" => "Location Popular Tour Product Code"]); ?>
        <?php render_button(["text" => "Add Tourist Place", "type" => "submit"]); ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>