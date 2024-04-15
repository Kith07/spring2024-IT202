<?php
require(__DIR__ . "/../../partials/nav.php");

$output = [];

if (isset($_POST["nationallocation_id"])) {
    // $location_id = $_POST["llocation_id"];
    // $language = $_POST["language"];
    // $currency = $_POST["currency"];
    $nationalLocID = $_POST["nationallocation_id"];

    $data = ["location_id" => '45963', "language" => 'en_US', "currency" => 'USD', "datatype" => "json"];
    $endpoint = "https://tourist-attraction.p.rapidapi.com/search";
    $isRapidAPI = true;
    $rapidAPIHost = "tourist-attraction.p.rapidapi.com";
    $output = post($endpoint, "TOURIST_API_KEY", $data, $isRapidAPI, $rapidAPIHost);
    var_dump($output);
    error_log("Response: " . var_export($output, true));
    if (se($output, "status", 400, false) == 200 && isset($output["response"])) {
        $output = json_decode($output["response"], true);
    } else {
        $output = [];
    }
    $output = $output["results"]["data"];
}

$selectedPlace = null;
foreach ($output as $place) {
    if ($place['location_id'] == $nationalLocID) { //replace hard coded value with the post form data
        $selectedPlace = $place;
        break;
    }
}


if ($selectedPlace) {
    $db = getDB();

    $basicInfoQuery = "INSERT INTO `tourist_info` (`location_id`, `language`, `currency`, `NationalID`, `name`, `ranking`, `description`, `rating`, `num_reviews`, `website`, `address`, `phone`, `write_review`, `monday_open`, `monday_close`, `tuesday_open`, `tuesday_close`, `wednesday_open`, `wednesday_close`, `thursday_open`, `thursday_close`, `friday_open`, `friday_close`, `saturday_open`, `saturday_close`, `sunday_open`, `sunday_close`, `popular_tour_title`, `primary_category`, `price`, `partner`, `tour_url`, `product_code`) ";
    $basicInfoQuery .= "VALUES (:location_id, :language, :currency, :nationalID, :name, :ranking, :description, :rating, :num_reviews, :website, :address, :phone, :write_review, :monday_open, :monday_close, :tuesday_open, :tuesday_close, :wednesday_open, :wednesday_close, :thursday_open, :thursday_close, :friday_open, :friday_close, :saturday_open, :saturday_close, :sunday_open, :sunday_close, :popular_tour_title, :primary_category, :price, :partner, :tour_url, :product_code)";

    try {
        $stmt = $db->prepare($basicInfoQuery);
        foreach ($selectedPlace["offer_group"]["offer_list"] as $offer_list) {
            $stmt->execute([
                ':location_id' => '45963', //hard coded value, replace with post form data
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
                ':popular_tour_title' => $offer_list['title'] ?? null,
                ':primary_category' => $offer_list['primary_category'] ?? null,
                ':price' => $offer_list['price'] ?? null,
                ':partner' => $offer_list['partner'] ?? null,
                ':tour_url' => $offer_list['url'] ?? null,
                ':product_code' => $offer_list['product_code'] ?? null
            ]);
            flash("Inserted Successfully", "success");
        }
    } catch (PDOException $e) {
        error_log("Something broke with the query" . var_export($e, true));
        flash("An error occurred", "danger");
    }
}

?>
<div class="container-fluid">
    <h1>Tourist Destination Info</h1>
    <p>Remember, we typically won't be frequently calling live data from our API, this is merely a quick sample. We'll want to cache data in our DB to save on API quota.</p>
    <form method="POST">
        <div>
            <!-- <label for="location_id">LocationID:</label>
            <input type="text" name="llocation_id" id="llocation_id" /><br>
            <label for="language">Language:</label>
            <input type="text" name="language" id="language" /><br>
            <label for="currency">Currency:</label>
            <input type="text" name="currency" id="currency" /><br> -->
            <label for="location_id">National LocationID:</label>
            <input type="text" name="nationallocation_id" id="nationallocation_id" /><br>
            <input type="submit" value="Fetch Details" />
        </div>
    </form>
    <?php if ($selectedPlace) : ?>
        <h1><?php echo isset($selectedPlace["name"]) ? $selectedPlace["name"] : "Not Available"; ?></h1>
        <p><?php echo isset($selectedPlace["ranking"]) ? $selectedPlace["ranking"] : "Not Available"; ?></p>
        <p><strong>Description:</strong> <?php echo isset($selectedPlace["description"]) ? $selectedPlace["description"] : "Not Available"; ?></p>
        <p><strong>Rating:</strong> <?php echo isset($selectedPlace["rating"]) ? $selectedPlace["rating"] : "Not Available"; ?></p>
        <p><strong>Reviews:</strong> <?php echo isset($selectedPlace["num_reviews"]) ? $selectedPlace["num_reviews"] : "Not Available"; ?></p>
        <p><strong>Website:</strong> <?php echo isset($selectedPlace["website"]) ? $selectedPlace["website"] : "Not Available"; ?></p>
        <p><strong>Location:</strong> <?php echo isset($selectedPlace["address"]) ? $selectedPlace["address"] : "Not Available"; ?></p>
        <p><strong>Phone:</strong> <?php echo isset($selectedPlace["phone"]) ? $selectedPlace["phone"] : "Not Available"; ?></p>
        <p><strong>Write a Review:</strong> <?php echo isset($selectedPlace["write_review"]) ? $selectedPlace["write_review"] : "Not Available"; ?></p>
    <?php endif; ?>

</div>

<div class="container-fluid">
    <?php if ($selectedPlace) : ?>
        <h2>Timings</h2>
        <table>
            <tr>
                <th>Day</th>
                <th>Open Hours</th>
            </tr>
            <tr>
                <td>Monday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][0][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][0][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][0][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Tuesday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][1][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][1][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][1][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Wednesday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][2][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][2][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][2][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Thursday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][3][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][3][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][3][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Friday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][4][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][4][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][4][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Saturday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][5][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][5][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][5][0]["close_time"] : "Not Available"; ?></td>
            </tr>
            <tr>
                <td>Sunday:</td>
                <td><?php echo isset($selectedPlace["hours"]["week_ranges"][6][0]["open_time"]) ? $selectedPlace["hours"]["week_ranges"][6][0]["open_time"] . " - " . $selectedPlace["hours"]["week_ranges"][6][0]["close_time"] : "Not Available"; ?></td>
            </tr>
        </table>
    <?php endif; ?>
</div>

<div class="container-fluid">
    <ul>
        <?php if ($selectedPlace && isset($selectedPlace["offer_group"]["offer_list"])) : ?>
            <h2> <br>Popular Tours </h2>
            <?php foreach ($selectedPlace["offer_group"]["offer_list"] as $offer_list) : ?>
                <li>
                    <?php echo "<strong>" . (isset($offer_list['title']) ? $offer_list['title'] : "Not Available") . "</strong><br>"; ?>
                    <?php echo "Primary Category: " . (isset($offer_list['primary_category']) ? $offer_list['primary_category'] : "Not Available") . "<br>"; ?>
                    <?php echo "Price: " . (isset($offer_list['price']) ? $offer_list['price'] : "Not Available") . "<br>"; ?>
                    <?php echo "Partner: " . (isset($offer_list['partner']) ? $offer_list['partner'] : "Not Available") . "<br>"; ?>
                    <?php echo "URL: " . (isset($offer_list['url']) ? $offer_list['url'] : "Not Available") . "<br>"; ?>
                    <?php echo "Product Code: " . (isset($offer_list['product_code']) ? $offer_list['product_code'] : "Not Available") . "<br>"; ?>
                    <?php echo "<br>"; ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<?php
require(__DIR__ . "/../../partials/flash.php");
