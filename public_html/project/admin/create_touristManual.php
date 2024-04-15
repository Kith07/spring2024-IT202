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
    $selectedPlace = [];
    $requiredFields = [
        "nationallocation_id" => "National Location ID",
        "location_name" => "Location Name",
        "location_ranking" => "Location Ranking",
        "location_description" => "Location Description",
        "location_rating" => "Location Rating",
        "location_num_reviews" => "Location Number of Reviews",
        "location_website" => "Location Website",
        "location_address" => "Location Address",
        "location_phone" => "Location Phone",
        "write_review_link" => "Write a Review Link",
        "location_monday_open" => "Location Monday Open Time",
        "location_monday_close" => "Location Monday Close Time",
        "location_tuesday_open" => "Location Tuesday Open Time",
        "location_tuesday_close" => "Location Tuesday Close Time",
        "location_wednesday_open" => "Location Wednesday Open Time",
        "location_wednesday_close" => "Location Wednesday Close Time",
        "location_thursday_open" => "Location Thursday Open Time",
        "location_thursday_close" => "Location Thursday Close Time",
        "location_friday_open" => "Location Friday Open Time",
        "location_friday_close" => "Location Friday Close Time",
        "location_saturday_open" => "Location Saturday Open Time",
        "location_saturday_close" => "Location Saturday Close Time",
        "location_sunday_open" => "Location Sunday Open Time",
        "location_sunday_close" => "Location Sunday Close Time",
        "location_popular_tours_title" => "Location Popular Tours Title",
        "location_popular_tours_category" => "Location Popular Tours Category",
        "location_popular_tour_price" => "Location Popular Tour Price",
        "location_popular_tour_partner" => "Location Popular Tour Partner",
        "location_popular_tour_link" => "Location Popular Tour Link",
        "location_popular_tour_code" => "Location Popular Tour Product Code"
    ];

    $hasError = false;

    foreach ($requiredFields as $fieldName => $fieldLabel) {
        if (empty($_POST[$fieldName])) {
            flash("$fieldLabel must not be empty", "danger");
            $hasError = true;
        }
    }

    if ($selectedPlace && !$hasError) {
        $db = getDB();

        $query = "INSERT INTO `tourist_info` (`location_id`, `language`, `currency`, `NationalID`, `name`, `ranking`, `description`, `rating`, `num_reviews`, `website`, `address`, `phone`, `write_review`, `monday_open`, `monday_close`, `tuesday_open`, `tuesday_close`, `wednesday_open`, `wednesday_close`, `thursday_open`, `thursday_close`, `friday_open`, `friday_close`, `saturday_open`, `saturday_close`, `sunday_open`, `sunday_close`, `popular_tour_title`, `primary_category`, `price`, `partner`, `tour_url`, `product_code`) ";
        $query .= "VALUES (:location_id, :language, :currency, :nationalID, :name, :ranking, :description, :rating, :num_reviews, :website, :address, :phone, :write_review, :monday_open, :monday_close, :tuesday_open, :tuesday_close, :wednesday_open, :wednesday_close, :thursday_open, :thursday_close, :friday_open, :friday_close, :saturday_open, :saturday_close, :sunday_open, :sunday_close, :popular_tour_title, :primary_category, :price, :partner, :tour_url, :product_code)";

        try {
            $stmt = $db->prepare($query);
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


<script>
    // JS Validation for Empty Fields
    
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            let isValid = true;
            const fields = [
                { name: "nationallocation_id", label: "National Location ID" },
                { name: "location_name", label: "Location Name" },
                { name: "location_ranking", label: "Location Ranking" },
                { name: "location_description", label: "Location Description" },
                { name: "location_rating", label: "Location Rating" },
                { name: "location_num_reviews", label: "Location Number of Reviews" },
                { name: "location_website", label: "Location Website" },
                { name: "location_address", label: "Location Address" },
                { name: "location_phone", label: "Location Phone" },
                { name: "write_review_link", label: "Write a Review Link" },
                { name: "location_monday_open", label: "Location Monday Open Time" },
                { name: "location_monday_close", label: "Location Monday Close Time" },
                { name: "location_tuesday_open", label: "Location Tuesday Open Time" },
                { name: "location_tuesday_close", label: "Location Tuesday Close Time" },
                { name: "location_wednesday_open", label: "Location Wednesday Open Time" },
                { name: "location_wednesday_close", label: "Location Wednesday Close Time" },
                { name: "location_thursday_open", label: "Location Thursday Open Time" },
                { name: "location_thursday_close", label: "Location Thursday Close Time" },
                { name: "location_friday_open", label: "Location Friday Open Time" },
                { name: "location_friday_close", label: "Location Friday Close Time" },
                { name: "location_saturday_open", label: "Location Saturday Open Time" },
                { name: "location_saturday_close", label: "Location Saturday Close Time" },
                { name: "location_sunday_open", label: "Location Sunday Open Time" },
                { name: "location_sunday_close", label: "Location Sunday Close Time" },
                { name: "location_popular_tours_title", label: "Location Popular Tours Title" },
                { name: "location_popular_tours_category", label: "Location Popular Tours Category" },
                { name: "location_popular_tour_price", label: "Location Popular Tour Price" },
                { name: "location_popular_tour_partner", label: "Location Popular Tour Partner" },
                { name: "location_popular_tour_link", label: "Location Popular Tour Link" },
                { name: "location_popular_tour_code", label: "Location Popular Tour Product Code" }
            ];

            fields.forEach(field => {
                const input = form.elements[field.name];
                const value = input.value.trim();
                if (value === "") {
                    flash(`"${field.label}" must not be empty [JS]`, "warning");
                    isValid = false;
                }
            });

            if (isValid) {
                form.submit();
            }
        });
    });
</script>

<div class="container-fluid">
    <h3>Add Tourist Location</h3>
    <form method="POST">
        <?php render_input(["name" => "nationallocation_id", "placeholder" => "Enter ID", "label" => "National LocationID", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_name", "placeholder" => "Enter Location Name", "label" => "Location Name", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_ranking", "placeholder" => "Enter Location Ranking", "label" => "Location Ranking", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_description", "placeholder" => "Enter Location Description", "label" => "Location Description", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_rating", "placeholder" => "Enter Location Rating", "label" => "Location Rating", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_num_reviews", "placeholder" => "Enter Location Number of Reviews", "label" => "Location Number of Reviews", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_website", "placeholder" => "Enter Location Website", "label" => "Location Website", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_address", "placeholder" => "Enter Location Address", "label" => "Location Address", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_phone", "placeholder" => "Enter Location Phone", "label" => "Location Phone", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "write_review_link", "placeholder" => "Enter Write a Review Link", "label" => "Write a Review Link", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_monday_open", "placeholder" => "Enter Location Monday Open Time", "label" => "Location Monday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_monday_close", "placeholder" => "Enter Location Monday Close Time", "label" => "Location Monday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_tuesday_open", "placeholder" => "Enter Location Tuesday Open Time", "label" => "Location Tuesday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_tuesday_close", "placeholder" => "Enter Location Tuesday Close Time", "label" => "Location Tuesday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_wednesday_open", "placeholder" => "Enter Location Wednesday Open Time", "label" => "Location Wednesday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_wednesday_close", "placeholder" => "Enter Location Wednesday Close Time", "label" => "Location Wednesday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_thursday_open", "placeholder" => "Enter Location Thursday Open Time", "label" => "Location Thursday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_thursday_close", "placeholder" => "Enter Location Thursday Close Time", "label" => "Location Thursday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_friday_open", "placeholder" => "Enter Location Friday Open Time", "label" => "Location Friday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_friday_close", "placeholder" => "Enter Location Friday Close Time", "label" => "Location Friday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_saturday_open", "placeholder" => "Enter Location Saturday Open Time", "label" => "Location Saturday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_saturday_close", "placeholder" => "Enter Location Saturday Close Time", "label" => "Location Saturday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_sunday_open", "placeholder" => "Enter Location Sunday Open Time", "label" => "Location Sunday Open Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_sunday_close", "placeholder" => "Enter Location Sunday Close Time", "label" => "Location Sunday Close Time", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tours_title", "placeholder" => "Enter Location Popular Tours Title", "label" => "Location Popular Tours Title", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tours_category", "placeholder" => "Enter Location Popular Tours Category", "label" => "Location Popular Tours Category", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tour_price", "placeholder" => "Enter Location Popular Tour Price", "label" => "Location Popular Tour Price", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tour_partner", "placeholder" => "Enter Location Popular Tour Partner", "label" => "Location Popular Tour Partner", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tour_link", "placeholder" => "Enter Location Popular Tour Link", "label" => "Location Popular Tour Link", "rules" => ["required" => "required"]]); ?>
        <?php render_input(["name" => "location_popular_tour_code", "placeholder" => "Enter Location Popular Product Tour Code", "label" => "Location Popular Tour Product Code", "rules" => ["required" => "required"]]); ?>
        <?php render_button(["text" => "Add Tourist Place", "type" => "submit"]); ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>