<!DOCTYPE html>
<html lang="en">
<?php 
include 'nav_bar.php';
include 'connection.php';

if (isset($_SESSION['userId'])) {
    if ($_SESSION['userRole'] == ROLE_CUSTOMER && isset($_POST['car_id'])) {
?>
        <head>
            <title>Car Rental MYM</title>
            <script>
                function validateDate() {
                    var startDate = document.getElementById('start_date').value;
                    var endDate = document.getElementById('end_date').value;
                    var today = new Date().toISOString().split('T')[0]; // Get today's date

                    if (startDate === "" || endDate === "") {
                        alert("Please select both start and end dates.");
                        return false;
                    }

                    if (startDate < today) {
                        alert("Start date must be equal to or greater than today.");
                        return false;
                    }

                    if (endDate <= startDate) {
                        alert("End date must be greater than the start date.");
                        return false;
                    }

                    return true;
                }
            </script>

            <?php
            include 'links.html';
            ?>
            <style>
                form {
                    width: 300px;
                    padding: 16px;
                    background-color: white;
                    box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.1);
                }

                .error {
                    background: #F2DEDE;
                    color: #A94442;
                    padding: 10px;
                    width: 95%;
                    border-radius: 5px;
                }

                .card {
                    border: 1px solid #ddd;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    margin: 10px;
                    padding: 16px;
                    text-align: center;
                }

                .card-img-top {
                    object-fit: cover;
                    height: 200px;
                }
            </style>
        </head>

        <body>
            <section class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">

                <?php

                $carId = $_POST['car_id'];
                $sql = "SELECT * FROM cars WHERE car_id = $carId";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $car = $result->fetch_assoc();
                ?>
                    <div class="card">
                        <img class="card-img-top" src="Images/<?php echo $car['image']; ?>" alt="Car Image" style="width: 100%">

                        <h4>Car Details:</h4>
                        <p>Model: <?php echo $car['model']; ?></p>
                        <p>Plate No.: <?php echo $car['plate_no']; ?></p>
                        <p>Color: <?php echo $car['color']; ?></p>
                        <p>Year: <?php echo $car['year']; ?></p>
                        <p>Miles: <?php echo $car['miles']; ?></p>
                        <p>Price/Day: $<?php echo $car['price_per_day']; ?></p>
                    </div>
                <?php
                }
                ?>

                <form action="reserve_validation.php" method="POST" onsubmit="return validateDate()">
                    <h3 class="d-flex align-items-center justify-content-center">Rent Car</h3>
                    <hr>
                    <div id="message_container" class="mb-3 mt-3">
                        <?php
                        if (isset($_GET["error"])) { ?>
                            <p id="error_paragraph" class="error"> <?php echo $_GET["error"]; ?> </p>
                        <?php
                        } ?>
                    </div>
                    <input type="hidden" name="car_id" value="<?php echo $carId ?>">
                    <input type="hidden" name="customer_id" value="<?php echo $_SESSION['userId'] ?>">
                    <div class="mb-3 mt-3">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <?php
                        if (isset($_GET['start_date'])) { ?>
                            <input type="date" class="form-control" id="start_date" placeholder="Enter start date" name="start_date" value="<?php echo $_GET['start_date'] ?> ">
                        <?php
                        } else { ?>
                            <input type="date" class="form-control" id="start_date" placeholder="Enter start date" name="start_date">
                        <?php
                        }
                        ?>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="end_date" class="form-label">End Date:</label>
                        <?php
                        if (isset($_GET['end_date'])) { ?>
                            <input type="date" class="form-control" id="end_date" placeholder="Enter end date" name="end_date" value="<?php echo $_GET['end_date'] ?> ">
                        <?php
                        } else { ?>
                            <input type="date" class="form-control" id="end_date" placeholder="Enter end date" name="end_date">
                        <?php
                        }
                        ?>
                    </div>

                    <div class="mb-3 mt-3">
                        <button type="submit" class="btn btn-primary d-flex mx-auto">Rent</button>
                    </div>

                </form>
            </section>
        </body>
<?php
    } else {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
} else {
    header("Location: login.php?error=You should login first!!");
    exit();
}
?>

</html>