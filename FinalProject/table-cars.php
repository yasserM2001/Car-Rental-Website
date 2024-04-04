<!DOCTYPE html>
<html>
<?php
include 'nav_bar.php';
include 'connection.php';
include_once 'models.php';
include_once 'constants.php';
date_default_timezone_set('Africa/Cairo');

if (isset($_SESSION['userRole'])) {
    if ($_SESSION['userRole'] == ROLE_ADMIN || $_SESSION['userRole'] == ROLE_OFFICE) {
        $userRole = $_SESSION['userRole'];
        $sql = null;
        if ($userRole == ROLE_ADMIN) {
            if (isset($_POST['office-id'])) {
                $officeId = $_POST['office-id'];
                $sql = "SELECT * FROM cars AS c NATURAL JOIN offices AS o NATURAL JOIN `status` AS s WHERE c.office_id = " . $officeId;
            } elseif (isset($_SESSION['office_id'])) {
                $officeId = $_SESSION['office_id'];
                unset($_SESSION['office_id']);
                $sql = "SELECT * FROM cars AS c NATURAL JOIN offices AS o NATURAL JOIN `status` AS s WHERE c.office_id = " . $officeId;
            } else {
                $sql = "SELECT * FROM cars AS c NATURAL JOIN offices AS o NATURAL JOIN `status` AS s";
            }
        } else {
            // if (isset($_POST['office-id'])) {
            //     $officeId = $_POST['office-id'];
            //     $sql = "SELECT * FROM cars AS c NATURAL JOIN offices AS o NATURAL JOIN `status` AS s WHERE c.office_id = " . $officeId;
            // } else
            if (isset($_SESSION['userId'])) {
                $officeId = $_SESSION['userId'];
                $sql = "SELECT * FROM cars AS c NATURAL JOIN offices AS o NATURAL JOIN `status` AS s WHERE c.office_id = " . $officeId;
            }
        }

        $result = $conn->query($sql);
        $cars = [];

        if ($result->num_rows > 0) {

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
                $car->setOfficeId($row['office_id']);
                $car->setOfficeName($row['name']);
                $car->setStatusName($row['status_name']);


                // Check if the car was reserved on the specified day
                if (isset($_POST['day'])) {
                    $selectedDay = $_POST['day'];
                    $carId = $car->getCarId();

                    // Query to check if the car was reserved on the specified day
                    $reservationQuery = "SELECT * FROM reservations WHERE car_id = $carId AND ('$selectedDay' BETWEEN startD AND endD)";
                    $reservationResult = $conn->query($reservationQuery);

                    if ($reservationResult && $reservationResult->num_rows > 0) {
                        // Car was reserved on the specified day, update its status or perform other actions
                        $car->setStatusName('Rented'); // Update the status as needed
                        $car->setStatus_id(3);
                    }
                }
                $cars[] = $car;
            }
        }

?>

        <h2>Cars <?php if (isset($_POST['day'])) {
                        echo "on " . $_POST['day'];
                    } else {
                        echo ' Today';
                    } ?></h2>
        <hr />
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
        <a href="car_search_date.php" class="btn btn-primary mb-3 mx-1">Date Search</a>

        <?php
        if ($userRole == ROLE_OFFICE) {
            // Check if the selected day is today
            $isToday = TRUE;
            if (isset($_POST['day'])) {
                if ($_POST['day'] != date('Y-m-d')) {
                    $isToday = FALSE;
                }
            }

            // Display the "Add New Car" button only when the selected day is today
            if ($isToday) {
                echo '<a href="add_car.php" class="btn btn-primary mb-3 mx-1">Add New Car</a>';
            }
        }
        ?>
        <table class="table table-bordered table-hover">
            <tr>
                <th>Model</th>

                <th>Plate No.</th>
                <th>Color</th>
                <th>Year</th>
                <th>Miles</th>
                <th>Price Per Day</th>
                <th>Office Name</th>
                <th>Status</th>
                <th colspan="3">Options</th>
            </tr>
            <?php
            foreach ($cars as $car) {
            ?>
                <tr>
                    <td><?php echo $car->getModel(); ?></td>
                    <td><?php echo $car->getPlateNo(); ?></td>
                    <td><?php echo $car->getColor(); ?></td>
                    <td><?php echo $car->getYear(); ?></td>
                    <td><?php echo $car->getMiles(); ?></td>
                    <td><?php echo $car->getPricePerDay(); ?></td>
                    <td><?php echo $car->getOfficeName(); ?></td>
                    <td><?php echo $car->getStatusName(); ?></td>
                    <td>
                        <?php
                        $isToday = TRUE;
                        if (isset($_POST['day'])) {
                            if ($_POST['day'] != date('Y-m-d')) {
                                $isToday = FALSE;
                            }
                        }

                        if ($isToday) {
                            if ($car->getStatus_id() == 1) { ?>
                                <form action="car_activation.php" method="POST">
                                    <input type="hidden" name="car_id" value="<?php echo $car->getCarId(); ?>">
                                    <input type="hidden" name="status_id" value="<?php echo $car->getStatus_id(); ?>">
                                    <?php
                                    if (isset($_POST['office-id'])) {
                                    ?>
                                        <input type="hidden" name="office_id" value="<?php echo $_POST['office-id'] ?>">

                                    <?php
                                    }
                                    ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this car?')">Deactivate</button>
                                </form>
                            <?php } else { ?>
                                <form action="car_activation.php" method="POST">
                                    <input type="hidden" name="car_id" value="<?php echo $car->getCarId(); ?>">
                                    <input type="hidden" name="status_id" value="<?php echo $car->getStatus_id(); ?>">
                                    <?php
                                    if (isset($_POST['office-id'])) {
                                    ?>
                                        <input type="hidden" name="office_id" value="<?php echo $_POST['office-id'] ?>">
                                    <?php
                                    }
                                    ?>
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to activate this car?')">Activate</button>
                                </form>
                            <?php } ?>
                    </td>
                    <td>
                        <form action="car_deletion.php" method="POST">
                            <input type="hidden" name="car_id" value="<?php echo $car->getCarId(); ?>">
                            <?php
                            if (isset($_POST['office-id'])) {
                            ?>
                                <input type="hidden" name="office_id" value="<?php echo $_POST['office-id'] ?>">
                            <?php
                            }
                            ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this car?')">Delete</button>
                        </form>
                    </td>
                    <td>
                        <form action="table-reservations.php" method="POST">
                            <input type="hidden" name="car_id" value="<?php echo $car->getCarId(); ?>">
                            <button type="submit" class="btn btn-success">Car Reservations</button>
                        </form>
                    <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
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
<style>
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
</html>