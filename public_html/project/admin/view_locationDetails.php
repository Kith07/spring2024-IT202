<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
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
    die(header("Location: " . get_url("admin/list_touristLocs.php")));
}

foreach ($output as $key => $value) {
    if (is_null($value)) {
        $output[$key] = "N/A";
    }
}

?>
<div class="container-fluid">
    <h3>Tourist Location: <?php se($output, "name", "Unknown"); ?></h3>
    <div>
        <a href="<?php echo get_url("admin/list_touristLocs.php"); ?>" class="btn btn-secondary">Back</a>
        <a href="<?php echo get_url("admin/edit_touristLoc.php?id=" . $id); ?>" class="btn btn-primary">Edit</a>
        <form method="POST" action="<?php echo get_url("admin/delete_touristLocs.php?id=" . $id); ?>" style="display: inline-block;">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
        </form>

    </div>
    <ul class="list-group">
        <li class="list-group-item"><strong>National ID: </strong> <?php se($output, "NationalID", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Name:</strong> <?php se($output, "name", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Description:</strong> <?php se($output, "description", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Rating:</strong> <?php se($output, "rating", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Number of Reviews:</strong> <?php se($output, "num_reviews", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Website: </strong><?php se($output, "website", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Address: </strong><?php se($output, "address", "Unknown"); ?></li> <!--UCID: LM457-->
        <li class="list-group-item"><strong>Phone: </strong><?php se($output, "phone", "Unknown"); ?></li> <!--DATE: 4/16/2024-->
        <li class="list-group-item"><strong>Write Review: </strong><?php se($output, "write_review", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Timings: </strong><br><br>
            <table>
                <tr>
                    <th>Day</th>
                    <th>Open Hours</th>
                </tr>
                <tr>
                    <td>Monday:</td>
                    <td><?php se($output, "monday_open", "Not Available"); ?> - <?php se($output, "monday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Tuesday:</td>
                    <td><?php se($output, "tuesday_open", "Not Available"); ?> - <?php se($output, "tuesday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Wednesday:</td>
                    <td><?php se($output, "wednesday_open", "Not Available"); ?> - <?php se($output, "wednesday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Thursday:</td>
                    <td><?php se($output, "thursday_open", "Not Available"); ?> - <?php se($output, "thursday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Friday:</td>
                    <td><?php se($output, "friday_open", "Not Available"); ?> - <?php se($output, "friday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Saturday:</td>
                    <td><?php se($output, "saturday_open", "Not Available"); ?> - <?php se($output, "saturday_close", "Not Available"); ?></td>
                </tr>
                <tr>
                    <td>Sunday:</td>
                    <td><?php se($output, "sunday_open", "Not Available"); ?> - <?php se($output, "sunday_close", "Not Available"); ?></td>
                </tr>
            </table>
        </li>
        <li class="list-group-item"><strong>Popular Tour Title: </strong><?php se($output, "popular_tour_title", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Primary Category: </strong><?php se($output, "primary_category", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Price: </strong><?php se($output, "price", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Partner: </strong><?php se($output, "partner", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Tour URL: </strong><?php se($output, "tour_url", "Unknown"); ?></li>
        <li class="list-group-item"><strong>Product Code: </strong><?php se($output, "product_code", "Unknown"); ?></li>
    </ul>

</div>
</div>
<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>