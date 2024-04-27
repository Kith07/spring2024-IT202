<?php
if (!isset($output)) {
    error_log("Using output partial without data");
    flash("Dev Alert: output called without data", "danger");
}
?>
<?php if (isset($output)) : ?>
    <div class="card mx-auto" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title"><strong><?php se($output, "name", "Unknown") ?></strong> <?php se($output, "rarity"); ?></h5>
            <div class="card-text">
                <!-- <div>
                    <a href="<?php echo get_url("admin/viewLocations.php?id=" . $id); ?>" class="btn btn-primary">View</a>
                    <a href="<?php echo get_url("admin/delete_touristLocs.php?id=" . $id); ?>" class="btn btn-danger">Delete</a>

                </div> -->
                <ul class="list-group">
                    <li class="list-group-item"><strong>National ID: </strong> <?php se($output, "NationalID", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Name:</strong> <?php se($output, "name", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Description:</strong> <?php se($output, "description", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Rating:</strong> <?php se($output, "rating", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Number of Reviews:</strong> <?php se($output, "num_reviews", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Website: </strong><?php se($output, "website", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Address: </strong><?php se($output, "address", "Unknown"); ?></li>
                    <li class="list-group-item"><strong>Phone: </strong><?php se($output, "phone", "Unknown"); ?></li>
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
                <?php if (!isset($output["user_id"]) || $output["user_id"] === "N/A") : ?>
                    <div class="card-body">
                        <a href="<?php echo get_url('api/bucketList_locations.php?places_id=' . $output["id"]); ?>" class="card-link">Add to Travel Bucket List</a>
                    </div>
                <?php else : ?>
                    <div class="card-body">
                        <div class="bg-warning text-dark text-center">Output not available</div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>