<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
?>

<?php
$id = se($_GET, "id", -1, false);

if (isset($_POST["NationalID"])) {
    $selectedPlace = [];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, ["NationalID", "name", "ranking", "description", "rating", "num_reviews", "website", "address", "phone", "write_review", "monday_open", "monday_close", "tuesday_open", "tuesday_close", "wednesday_open", "wednesday_close", "thursday_open", "thursday_close", "friday_open", "friday_close", "saturday_open", "saturday_close", "sunday_open", "sunday_close", "popular_tour_title", "primary_category", "price", "partner", "tour_url", "product_code"])) {
            unset($_POST[$key]);
        }
        $selectedPlace = $_POST;
    }
    // PHP Server Side Validation for Empty Fields
    $hasError = false;
    foreach ($selectedPlace as $k => $v) {
        if (strlen($v) == 0) {
            flash("\"$k\" cannot be empty", "danger");
            $hasError = true;
            break;
        }
    }

    if ($selectedPlace && !$hasError) {
        $db = getDB();
        $query = "UPDATE `tourist_info` SET ";
        $params = [];
        
        foreach ($selectedPlace as $k => $v) {
            if ($params) {
                $query .= ",";
            }
            $query .= "$k=:$k";
            $params[":$k"] = $v;
        }

        $query .= " WHERE id = :id";
        $params[":id"] = $id;
        error_log("Query: " . $query);
        error_log("Params: " . var_export($params, true));
        try {
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            flash("Updated Record ", "success");
        } catch (PDOException $e) {
            error_log("Something broke with the query" . var_export($e, true));
            flash("An error occurred", "danger");
        }
    }
}

$output = [];
if ($id > -1) {
    $db = getDB();
    $query = "SELECT id, location_id, language, currency, NationalID, name, ranking, description, rating, num_reviews, website, address, phone, write_review, monday_open, monday_close, tuesday_open, tuesday_close, wednesday_open, wednesday_close, thursday_open, thursday_close, friday_open, friday_close, saturday_open, saturday_close, sunday_open, sunday_close, popular_tour_title, primary_category, price, partner, tour_url, product_code FROM `tourist_info` WHERE id = :id";
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
    die(header("Location: " . get_url("admin/list_touristLocs.php")));
}

if ($output) {
    $form = [
        ["name" => "NationalID", "placeholder" => "Enter ID", "label" => "National LocationID", "rules" => ["required" => "required"]],
        ["name" => "name", "placeholder" => "Enter Location Name", "label" => "Location Name", "rules" => ["required" => "required"]],
        ["name" => "ranking", "placeholder" => "Enter Location Ranking", "label" => "Location Ranking", "rules" => ["required" => "required"]],
        ["name" => "description", "placeholder" => "Enter Location Description", "label" => "Location Description", "rules" => ["required" => "required"]],
        ["name" => "rating", "placeholder" => "Enter Location Rating", "label" => "Location Rating", "rules" => ["required" => "required"]],
        ["name" => "num_reviews", "placeholder" => "Enter Location Number of Reviews", "label" => "Location Number of Reviews", "rules" => ["required" => "required"]],
        ["name" => "website", "placeholder" => "Enter Location Website", "label" => "Location Website", "rules" => ["required" => "required"]],
        ["name" => "address", "placeholder" => "Enter Location Address", "label" => "Location Address", "rules" => ["required" => "required"]],
        ["name" => "phone", "placeholder" => "Enter Location Phone", "label" => "Location Phone", "rules" => ["required" => "required"]],
        ["name" => "write_review", "placeholder" => "Enter Write a Review Link", "label" => "Write a Review Link", "rules" => ["required" => "required"]],
        ["name" => "monday_open", "placeholder" => "Enter Location Monday Open Time", "label" => "Location Monday Open Time", "rules" => ["required" => "required"]],
        ["name" => "monday_close", "placeholder" => "Enter Location Monday Close Time", "label" => "Location Monday Close Time", "rules" => ["required" => "required"]],
        ["name" => "tuesday_open", "placeholder" => "Enter Location Tuesday Open Time", "label" => "Location Tuesday Open Time", "rules" => ["required" => "required"]],
        ["name" => "tuesday_close", "placeholder" => "Enter Location Tuesday Close Time", "label" => "Location Tuesday Close Time", "rules" => ["required" => "required"]],
        ["name" => "wednesday_open", "placeholder" => "Enter Location Wednesday Open Time", "label" => "Location Wednesday Open Time", "rules" => ["required" => "required"]],
        ["name" => "wednesday_close", "placeholder" => "Enter Location Wednesday Close Time", "label" => "Location Wednesday Close Time", "rules" => ["required" => "required"]],
        ["name" => "thursday_open", "placeholder" => "Enter Location Thursday Open Time", "label" => "Location Thursday Open Time", "rules" => ["required" => "required"]],
        ["name" => "thursday_close", "placeholder" => "Enter Location Thursday Close Time", "label" => "Location Thursday Close Time", "rules" => ["required" => "required"]],
        ["name" => "friday_open", "placeholder" => "Enter Location Friday Open Time", "label" => "Location Friday Open Time", "rules" => ["required" => "required"]],
        ["name" => "friday_close", "placeholder" => "Enter Location Friday Close Time", "label" => "Location Friday Close Time", "rules" => ["required" => "required"]],
        ["name" => "saturday_open", "placeholder" => "Enter Location Saturday Open Time", "label" => "Location Saturday Open Time", "rules" => ["required" => "required"]],
        ["name" => "saturday_close", "placeholder" => "Enter Location Saturday Close Time", "label" => "Location Saturday Close Time", "rules" => ["required" => "required"]],
        ["name" => "sunday_open", "placeholder" => "Enter Location Sunday Open Time", "label" => "Location Sunday Open Time", "rules" => ["required" => "required"]],
        ["name" => "sunday_close", "placeholder" => "Enter Location Sunday Close Time", "label" => "Location Sunday Close Time", "rules" => ["required" => "required"]],
        ["name" => "popular_tour_title", "placeholder" => "Enter Location Popular Tours Title", "label" => "Location Popular Tours Title", "rules" => ["required" => "required"]],
        ["name" => "primary_category", "placeholder" => "Enter Location Popular Tours Category", "label" => "Location Popular Tours Category", "rules" => ["required" => "required"]],
        ["name" => "price", "placeholder" => "Enter Location Popular Tour Price", "label" => "Location Popular Tour Price", "rules" => ["required" => "required"]],
        ["name" => "partner", "placeholder" => "Enter Location Popular Tour Partner", "label" => "Location Popular Tour Partner", "rules" => ["required" => "required"]],
        ["name" => "tour_url", "placeholder" => "Enter Location Popular Tour Link", "label" => "Location Popular Tour Link", "rules" => ["required" => "required"]],
        ["name" => "product_code", "placeholder" => "Enter Location Popular Product Tour Code", "label" => "Location Popular Tour Product Code", "rules" => ["required" => "required"]]
    ];

    $keys = array_keys($output);
    foreach ($form as $idx => $field) {
        if (in_array($field["name"], $keys)) {
            $form[$idx]["value"] = $output[$field["name"]];
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
                { name: "NationalID", label: "National Location ID" },
                { name: "name", label: "Location Name" },
                { name: "ranking", label: "Location Ranking" },
                { name: "description", label: "Location Description" },
                { name: "rating", label: "Location Rating" },
                { name: "num_reviews", label: "Location Number of Reviews" },
                { name: "website", label: "Location Website" },
                { name: "address", label: "Location Address" },
                { name: "phone", label: "Location Phone" },
                { name: "write_review", label: "Write a Review Link" },
                { name: "monday_open", label: "Location Monday Open Time" },
                { name: "monday_close", label: "Location Monday Close Time" },
                { name: "tuesday_open", label: "Location Tuesday Open Time" },
                { name: "tuesday_close", label: "Location Tuesday Close Time" },
                { name: "wednesday_open", label: "Location Wednesday Open Time" },
                { name: "wednesday_close", label: "Location Wednesday Close Time" },
                { name: "thursday_open", label: "Location Thursday Open Time" },
                { name: "thursday_close", label: "Location Thursday Close Time" },
                { name: "friday_open", label: "Location Friday Open Time" },
                { name: "friday_close", label: "Location Friday Close Time" },
                { name: "saturday_open", label: "Location Saturday Open Time" },
                { name: "saturday_close", label: "Location Saturday Close Time" },
                { name: "sunday_open", label: "Location Sunday Open Time" },
                { name: "sunday_close", label: "Location Sunday Close Time" },
                { name: "popular_tour_title", label: "Location Popular Tours Title" },
                { name: "primary_category", label: "Location Popular Tours Category" },
                { name: "price", label: "Location Popular Tour Price" },
                { name: "partner", label: "Location Popular Tour Partner" },
                { name: "tour_url", label: "Location Popular Tour Link" },
                { name: "product_code", label: "Location Popular Tour Product Code" }
            ];

            fields.forEach(field => {
                const input = form.elements[field.name];
                if (!input) {
                    console.error(`Input element for "${field.name}" not found`);
                } else {
                    const value = input.value.trim();
                    if (value === "") {
                        flash(`"${field.label}" must not be empty [JS]`, "warning");
                        isValid = false;
                    }
                }
            });

            if (isValid) {
                form.submit();
            }
        });
    });
</script>


<div class="container-fluid">
    <h3>Edit Tourist Location</h3>
    <div>
        <a href="<?php echo get_url("admin/list_touristLocs.php"); ?>" class="btn btn-secondary">Back</a>
    </div>
    <form method="POST">
        <?php foreach ($form as $field) : ?>
            <?php render_input($field); ?>
        <?php endforeach; ?>
        <?php render_button(["text" => "Edit Tourist Place", "type" => "submit"]); ?>
    </form>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>