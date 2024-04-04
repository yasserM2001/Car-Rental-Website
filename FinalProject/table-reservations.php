<!DOCTYPE html>
<html>
<?php
include 'nav_bar.php';
include 'connection.php';
include_once 'models.php';
include_once 'constants.php';

if (isset($_SESSION['userId'])) {
    if ($_SESSION['userRole'] == ROLE_CUSTOMER || $_SESSION['userRole'] == ROLE_ADMIN || $_SESSION['userRole'] == ROLE_OFFICE) {
        $userRole = $_SESSION['userRole'];
        if ($userRole == ROLE_ADMIN || $userRole == ROLE_OFFICE) {
            if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
                $startDate = date('Y-m-d', strtotime($_POST['start_date']));
                $endDate = date('Y-m-d', strtotime($_POST['end_date'] . " +1 day"));
                if(isset($_POST['customer_id'])){
                    $customerId = $_POST['customer_id'];
                    $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                    FROM reservations AS r 
                    JOIN cars AS c ON r.car_id = c.car_id 
                    JOIN offices AS o ON c.office_id = o.office_id
                    JOIN `status` AS s ON c.status_id = s.status_id
                    JOIN customers AS cu ON r.customer_id = cu.customer_id
                    WHERE r.customer_id = '$customerId'
                    AND r.res_date >= '$startDate'
                    AND r.res_date <= '$endDate'
                    ORDER BY r.res_date DESC";
                }elseif(isset($_POST['car_id'])){
                    $carId = $_POST['car_id'];
                    $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                    FROM reservations AS r 
                    JOIN cars AS c ON r.car_id = c.car_id 
                    JOIN offices AS o ON c.office_id = o.office_id
                    JOIN `status` AS s ON c.status_id = s.status_id
                    JOIN customers AS cu ON r.customer_id = cu.customer_id
                    WHERE r.car_id = '$carId'
                    AND r.res_date >= '$startDate'
                    AND r.res_date <= '$endDate'
                    ORDER BY r.res_date DESC";
                }
                else{
                $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                        FROM reservations AS r 
                        JOIN cars AS c ON r.car_id = c.car_id 
                        JOIN offices AS o ON c.office_id = o.office_id
                        JOIN `status` AS s ON c.status_id = s.status_id
                        JOIN customers AS cu ON r.customer_id = cu.customer_id
                        WHERE r.res_date >= '$startDate'
                        AND r.res_date <= '$endDate'
                        ORDER BY r.res_date DESC";}
            } elseif (isset($_POST['customer_id'])) {
                $customerId = $_POST['customer_id'];
                $_SESSION['customer_id'] = $customerId;
                $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                        FROM reservations AS r 
                        JOIN cars AS c ON r.car_id = c.car_id 
                        JOIN offices AS o ON c.office_id = o.office_id
                        JOIN `status` AS s ON c.status_id = s.status_id
                        JOIN customers AS cu ON r.customer_id = cu.customer_id
                        WHERE r.customer_id = $customerId
                        ORDER BY r.res_date DESC";
            } elseif (isset($_POST['car_id'])) {
                $carId = $_POST['car_id'];

                $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                        FROM reservations AS r 
                        JOIN cars AS c ON r.car_id = c.car_id 
                        JOIN offices AS o ON c.office_id = o.office_id
                        JOIN `status` AS s ON c.status_id = s.status_id
                        JOIN customers AS cu ON r.customer_id = cu.customer_id
                        WHERE r.car_id = $carId
                        ORDER BY r.res_date DESC";
            } else {
                $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                        FROM reservations AS r 
                        JOIN cars AS c ON r.car_id = c.car_id 
                        JOIN offices AS o ON c.office_id = o.office_id
                        JOIN `status` AS s ON c.status_id = s.status_id
                        JOIN customers AS cu ON r.customer_id = cu.customer_id
                        ORDER BY r.res_date DESC";
            }
        } else {
            $customerId = $_SESSION['userId'];

            $sql = "SELECT r.*, c.*, o.*, s.*, cu.fname, cu.lname
                    FROM reservations AS r 
                    JOIN cars AS c ON r.car_id = c.car_id 
                    JOIN offices AS o ON c.office_id = o.office_id
                    JOIN `status` AS s ON c.status_id = s.status_id
                    JOIN customers AS cu ON r.customer_id = cu.customer_id
                    WHERE r.customer_id = $customerId
                    ORDER BY r.endD DESC";
        }


        $result = $conn->query($sql);
        $reservations = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservation = new Reservation(
                    $row['reserve_no'],
                    $row['customer_id'],
                    $row['car_id'],
                    $row['startD'],
                    $row['endD'],
                    $row['res_date'],
                    $row['cost']
                );
                $reservation->setFName($row['fname']);
                $reservation->setLName($row['lname']);

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

                $car->setOfficeName($row['name']);
                $car->setStatusName($row['status_name']);

                $reservation->setCar($car);

                $reservations[] = $reservation;
            }
        }
?>

        <h2>Reservations</h2>
        <hr />
        <?php if ($_SESSION['userRole'] == ROLE_ADMIN) { ?>
            <form action="advanced_search.php" method="POST">
                <?php if (isset($_POST['customer_id'])) { ?>
                    <input type="hidden" name="customer_id" value="<?php echo $_POST['customer_id']; ?>">
                <?php } ?>
                <?php if (isset($_POST['car_id'])) { ?>
                    <input type="hidden" name="car_id" value="<?php echo $_POST['car_id']; ?>">
                <?php } ?>
                <button type="submit" class="btn btn-primary mb-3 mx-1">Date Search</button>
            </form>

        <?php   } ?>
        <table class="table table-bordered table-hover">
            <tr>
                <th>Customer Name</th>

                <th>Car Model</th>
                <th>Plate No.</th>
                <th>Car Color</th>
                <th>Year</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reservation Date</th>
                <th>Total Cost</th>
                <th>Office Name</th>
                <th>Car Status</th>
            </tr>
            <?php
            foreach ($reservations as $reservation) {
            ?>
                <tr>
                    <td><?php echo $reservation->getFname() . ' ' . $reservation->getLname(); ?></td>

                    <td><?php echo $reservation->getCar()->getModel(); ?></td>
                    <td><?php echo $reservation->getCar()->getPlateNo(); ?></td>
                    <td><?php echo $reservation->getCar()->getColor(); ?></td>
                    <td><?php echo $reservation->getCar()->getYear(); ?></td>
                    <td><?php echo $reservation->getStartD(); ?></td>
                    <td><?php echo $reservation->getEndD(); ?></td>
                    <td><?php echo $reservation->getResDate(); ?></td>
                    <td><?php echo $reservation->getCost(); ?></td>
                    <td><?php echo $reservation->getCar()->getOfficeName(); ?></td>
                    <td><?php echo $reservation->getCar()->getStatusName(); ?></td>
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

</html>