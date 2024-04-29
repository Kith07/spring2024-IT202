<?php
if (!isset($output)) {
    error_log("Using output partial without data");
    flash("Dev Alert: output called without data", "danger");
}
?>
<?php if (isset($output)) : ?>
    <div class="card mx-auto" style="width: 18rem;">
        <?php if (isset($output["username"])) : ?>
            <div class="card-header">
                <Strong>Selected By: </Strong><?php se($output, "username", "N/A"); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($output["total_users"])) : ?>
            <div class="card-header">
                <strong>Favorited By: </strong><?php se($output, "total_users", "N/A"); ?> Users
            </div>
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><strong><?php se($output, "name", "Unknown") ?></strong> <?php se($output, "rarity"); ?></h5>
            <div class="card-text">
                <ul class="list-group">
                    <li class="list-group-item"><strong>National ID: </strong> <?php se($output, "NationalID", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Name:</strong> <?php se($output, "name", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Rating:</strong> <?php se($output, "rating", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Number of Reviews:</strong> <?php se($output, "num_reviews", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Website: </strong><?php se($output, "website", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Address: </strong><?php se($output, "address", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Phone: </strong><?php se($output, "phone", "Unknown"); ?></li>
                </ul>
            </div>
            <div class="card body">
                <?php if (isset($output["id"])) : ?>
                    <a class="btn btn-secondary" href="<?php echo get_url("viewLocations.php?id=" . $output["id"]); ?>">View</a>
                <?php endif; ?>
                <?php if (isset($output["user_id"])) : ?>
                    <a class="btn btn-primary" href="<?php echo get_url("profile.php?id=" . $output["user_id"]); ?>"><?php se($output, "username"); ?>'s Profile</a>
                <?php endif; ?>
                <?php if (isset($output["user_id"]) && isset($output["places_id"])) : ?>
                    <a class="btn btn-danger" href="<?php echo get_url("delete_Association.php?user_id=" . $output["user_id"] . "&places_id=" . $output["places_id"]); ?>">Remove</a>
                <?php endif; ?>
                <?php if ($output["is_favorite"] == 0 || $output["user_id"] === "N/A") : ?>
                    <div class="card-body">
                        <?php $id = isset($output["id"]) ? $output["id"] : (isset($_GET["id"]) ? $_GET["id"] : -1); ?>
                        <div class="d-flex justify-content-center">
                            <a href="<?php echo get_url('api/bucketList_locations.php?places_id=' . $output["id"]); ?>" class="btn btn-success">Add to Travel Bucket List</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="card-body">
                        <div class="bg-warning text-dark text-center">Output not available</div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>