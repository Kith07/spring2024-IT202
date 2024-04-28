<?php
require(__DIR__ . "/../../partials/nav.php");
$db = getDB();
//remove all associations
if (isset($_GET["remove"])) {
    $query = "DELETE FROM `UserLocations` WHERE user_id = :user_id";
    try {
        $stmt = $db->prepare($query);
        $stmt->execute([":user_id" => get_user_id()]);
        flash("Successfully removed all brokers", "success");
    } catch (PDOException $e) {
        error_log("Error removing broker associations: " . var_export($e, true));
        flash("Error removing broker associations", "danger");
    }

    redirect("my_locations.php");
}

// Form Search
$form = [
    ["type" => "number", "name" => "NationalID", "placeholder" => "ID Search", "label" => "ID Search", "include_margin" => false],

    ["type" => "text", "name" => "name", "placeholder" => "Name Search", "label" => "Name Search", "include_margin" => false],

    ["type" => "decimal", "name" => "min_rating", "placeholder" => "Minimum Rating", "label" => "Minimum Rating", "pattern" => "\d*\.?\d*", "include_margin" => false],         //UCID: LM457
    //DATE: 4/16/2024     
    ["type" => "number", "name" => "min_num_reviews", "placeholder" => "Minimum Reviews", "label" => "Minimum Reviews", "include_margin" => false],

    ["type" => "date", "name" => "date_min", "placeholder" => "Minimum Date", "label" => "Minimum Date", "include_margin" => false],
    ["type" => "date", "name" => "date_max", "placeholder" => "Maximum Date", "label" => "Maximum Date", "include_margin" => false],

    ["type" => "select", "name" => "sort", "label" => "Sort", "options" => ["rating" => "Rating", "num_reviews" => "Number of Reviews", "date" => "Date"], "include_margin" => false, "value" => "rating"],
    ["type" => "select", "name" => "order", "label" => "Order", "options" => ["asc" => "+", "desc" => "-"], "include_margin" => false],

    ["type" => "number", "name" => "limit", "label" => "Limit", "value" => "10", "include_margin" => false],
];

$total_records = get_total_count("tourist_info t JOIN `UserLocations` ut ON t.id = ut.places_id WHERE user_id = :user_id", [":user_id" => get_user_id()]);

$query = "SELECT t.id, location_id, language, currency, NationalID, name, ranking, description, rating, num_reviews, website, address, phone, write_review, monday_open, monday_close, tuesday_open, tuesday_close, wednesday_open, wednesday_close, thursday_open, thursday_close, 
friday_open, friday_close, saturday_open, saturday_close, sunday_open, sunday_close, popular_tour_title, primary_category, price, partner, tour_url, product_code, is_api, t.created, user_id  FROM `tourist_info` t JOIN `UserLocations` ut ON t.id = ut.places_id WHERE user_id = :user_id";
$params = [":user_id" => get_user_id()];
$session_key = $_SERVER["SCRIPT_NAME"];
$is_clear = isset($_GET["clear"]);
if ($is_clear) {
    session_delete($session_key);
    unset($_GET["clear"]);
    redirect($session_key);
} else {
    $session_data = session_load($session_key);
}

if (count($_GET) == 0 && isset($session_data) && count($session_data) > 0) {
    if ($session_data) {
        $_GET = $session_data;
    }
}
if (count($_GET) > 0) {
    session_save($session_key, $_GET);
    $keys = array_keys($_GET);

    foreach ($form as $k => $v) {
        if (in_array($v["name"], $keys)) {                                              //UCID: LM457
            $form[$k]["value"] = $_GET[$v["name"]];                                     //DATE: 4/16/2024
        }
    }
    //NationalID
    if (!empty($_GET["NationalID"])) {
        $query .= " AND NationalID LIKE :NationalID";
        $params[":NationalID"] = "%" . $_GET["NationalID"] . "%";
    }

    //Name
    if (!empty($_GET["name"])) {
        $query .= " AND name LIKE :name";
        $params[":name"] = "%" . $_GET["name"] . "%";
    }

    //Rating
    if (!empty($_GET["min_rating"])) {
        $query .= " AND rating >= :min_rating";
        $params[":min_rating"] = $_GET["min_rating"];
    }

    //Number of Reviews
    if (!empty($_GET["min_num_reviews"])) {
        $query .= " AND num_reviews >= :min_num_reviews";
        $params[":min_num_reviews"] = $_GET["min_num_reviews"];
    }

    //date range
    if (!empty($_GET["date_min"])) {
        $query .= " AND created >= :date_min";
        $params[":date_min"] = $_GET["date_min"];
    }

    if (!empty($_GET["date_max"])) {
        $query .= " AND created <= :date_max";
        $params[":date_max"] = $_GET["date_max"];
    }

    //sort and order
    $sort = se($_GET, "sort", "created", false);
    if (!in_array($sort, ["rating", "num_reviews"])) {
        $sort = "created";
    }
    if ($sort === "created" || $sort === "modified") {
        $sort = "t." . $sort;
    }

    $order = se($_GET, "order", "desc", false);
    if (!in_array($order, ["asc", "desc"])) {
        $order = "desc";
    }

    //IMPORTANT make sure you fully validate/trust $sort and $order (sql injection possibility)
    $query .= " ORDER BY $sort $order";
    //limit
    try {
        $limit = (int)se($_GET, "limit", "10", false);                                              //UCID: LM457
    } catch (Exception $e) {                                                                        //DATE: 4/16/2024             
        $limit = 10;
    }
    if ($limit < 1 || $limit > 100) {
        $limit = 10;
    }
    //IMPORTANT make sure you fully validate/trust $limit (sql injection possibility)
    $query .= " LIMIT $limit";
}

$stmt = $db->prepare($query);
$results = [];
try {
    $stmt->execute($params);
    $r = $stmt->fetchAll();
    if ($r) {
        $results = $r;
    }
} catch (Exception $e) {
    error_log("Error Fetching Locations: " . var_export($e, true));
    flash("An error occurred, please try again", "danger");
}

$table = ["data" => $results, "title" => "List of Tourist Locations Data", "ignored_columns" => [
    "id", "location_id", "language", "currency", "description", "write_review", "monday_open", "monday_close", "tuesday_open", "tuesday_close", "wednesday_open", "wednesday_close",
    "thursday_open", "thursday_close", "friday_open", "friday_close", "saturday_open", "saturday_close", "sunday_open", "sunday_close", "popular_tour_title", "primary_category", "price", "partner", "tour_url", "product_code", "is_api"
], "view_url" => get_url("viewLocations.php")];
?>

<div class="container-fluid">
    <h3>My Travel Bucket List</h3>
    <form method="GET">
        <div class="row mb-3" style="align-items: center;">
            <?php foreach ($form as $k => $v) : ?>
                <div class="col">
                    <?php render_input($v); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="display: flex-end; align-items: center;">
            <?php render_button(["text" => "Filter", "type" => "submit"]); ?>
            <a href="?clear" class="btn btn-secondary ml-2">Clear</a>
            <a href="?remove" onclick="confirm('Are you sure?')?'':event.preventDefault()" class="btn btn-danger ml-2">Remove All Brokers</a>
        </div>
    </form>
    <?php render_result_counts(count($results), $total_records); ?>
    <div class="row w-100 row-cols-auto row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-4">
        <?php foreach ($results as $broker) : ?>
            <div class="col">
                <?php render_tourist_card($broker); ?>
            </div>
        <?php endforeach; ?>
        <?php if (count($results) === 0) : ?>
            <div class="col">
                No results to show
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../partials/flash.php");
?>