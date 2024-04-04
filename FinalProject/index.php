<!DOCTYPE html>
<html lang="en">
<?php
include 'nav_bar.php';
include 'connection.php';
include 'models.php';

// global $conn;
// Query to get reservations with start dates that have come
$currentDate = date('Y-m-d');

$sqlStart = "UPDATE cars SET status_id = 3 WHERE status_id = 1 AND car_id IN (
        SELECT car_id FROM reservations WHERE startD <= '" . $currentDate . "' AND endD >= '" . $currentDate . "'
    )";

// Query to get reservations with end dates that have come
$sqlEnd = "UPDATE cars SET status_id = 1 WHERE status_id = 3 AND car_id IN (
        SELECT car_id FROM reservations WHERE endD <= '$currentDate'
    )";

$conn->query($sqlStart);

$conn->query($sqlEnd);

?>

<head>
    <style>
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .error {
            background: #F2DEDE;
            color: #A94442;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
        }

        .success {
            background: #D4EDDA;
            color: #40754C;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            margin: 20px auto;
        }
    </style>


</head>

<section class="mx-5 my-3">
    <div id="message_container" class="mb-3 mt-3">
        <?php

        if (isset($_GET["error"])) {
        ?>
            <p id="error_paragraph" class="error"> <?php echo $_GET["error"]; ?> </p>
        <?php } elseif (isset($_GET["success"])) { ?>
            <p id="success_paragraph" class="success"> <?php echo $_GET["success"]; ?> </p>
        <?php
        } ?>
    </div>

    <div class="search-container">
        <form action="" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search..." id="search" name="search" value="<?php if (isset($_GET['search'])) {
                                                                                                                        echo $_GET['search'];
                                                                                                                    } ?>">
                <button class="btn btn-outline-primary mx-2" type="submit">Search</button>
            </div>

            <!-- <div class="mb-3">
                <label for="filter_by" class="form-label">Filter By:</label>
                <div class="btn-group mx-3" role="group" aria-label="Filter By" id="filter_by">
                    <input class="btn-check" type="radio" name="filter_option" id="filter_model" value="model" checked>
                    <label class="btn btn-outline-primary" for="filter_model">Model</label>

                    <input class="btn-check" type="radio" name="filter_option" id="filter_year" value="year">
                    <label class="btn btn-outline-primary" for="filter_year">Year</label>

                    <input class="btn-check" type="radio" name="filter_option" id="filter_color" value="color">
                    <label class="btn btn-outline-primary" for="filter_color">Color</label>
                </div>
            </div> -->
        </form>
    </div>

    <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 justify-content-center">
        <?php

        if (isset($_GET['search'])) {
            $filterValues = $_GET['search'];
            $sql = "SELECT * FROM cars WHERE status_id = 1 AND CONCAT(model,`year`,color) LIKE '%" . $filterValues . "%'";
        } else {
            // Fetch cars data from the 'cars' table
            $sql = "SELECT * FROM cars WHERE status_id = 1";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Create an array to store Car objects
            $cars = [];

            // Loop through the rows of the 'cars' table and create Car objects
            while ($row = $result->fetch_assoc()) {
                $car = new Car(
                    $row['car_id'],
                    $row['plate_no'],
                    $row['model'],
                    $row['year'],
                    $row['status_id'],
                    $row['image'],
                    $row['date'],
                    $row['miles'],
                    $row['color'],
                    $row['price_per_day']
                );

                $cars[] = $car;
            }


            foreach ($cars as $car) {
        ?>
                <div class="col mb-4">
                    <div class="card">
                        <img class="card-img-top" src="Images/<?php echo $car->getImagePath(); ?>" alt="Image of <?php echo $car->getModel(); ?> car">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $car->getModel(); ?></h4>
                            <p class="card-text"><?php echo $car->getYear(); ?></p>
                            <p class="card-text">Price/day: $<?php echo $car->getPricePerDay(); ?></p>

                            <?php
                            if (isset($_SESSION['userId'])) {
                                if ($_SESSION['userRole'] == ROLE_CUSTOMER) { ?>
                                    <form action="reserve.php" method="POST">
                                        <input type="hidden" name="car_id" value="<?php echo $car->getCarId(); ?>">
                                        <button type="submit" class="btn btn-primary">Reserve</button>
                                    </form>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
    </div>
</section>

<?php
        } else {
?>
    <h3 class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 justify-content-center">No cars found in the database.</h3>
<?php

        }
        $conn->close();
?>

</html>