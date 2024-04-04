<!DOCTYPE html>
<html>

<?php
include 'nav_bar.php';
include 'connection.php';
include_once 'models.php';
include_once 'constants.php';

if (isset($_SESSION['userId'])) {
    if ($_SESSION['userRole'] == ROLE_ADMIN || $_SESSION['userRole'] == ROLE_OFFICE) {
        $userRole = $_SESSION['userRole'];

        // Check if the user submitted a date range
        if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $startDate = date('Y-m-d', strtotime($_POST['start_date']));
            $endDate = date('Y-m-d', strtotime($_POST['end_date']));
            $sql = "SELECT DATE(r.res_date) as date, SUM(r.cost) as daily_payment
                    FROM reservations AS r 
                    WHERE DATE(r.res_date) >= '$startDate'
                    AND DATE(r.res_date) <= '$endDate'
                    GROUP BY DATE(r.res_date)
                    ORDER BY DATE(r.res_date) DESC";
        } else {
            $sql = "SELECT DATE(r.res_date) as date, SUM(r.cost) as daily_payment
                    FROM reservations AS r 
                    GROUP BY DATE(r.res_date)
                    ORDER BY DATE(r.res_date) DESC";
        }

        $result = $conn->query($sql);
        $dailyPayments = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dailyPayment = new DailyPayment(
                    $row['date'],
                    $row['daily_payment']
                );

                $dailyPayments[] = $dailyPayment;
            }
        }
?>

        <h2>Daily Payments</h2>
        <hr />

        <form action="table_daily_payments.php" method="POST" onsubmit="return validateDate()">
        <div id="message_container" class="mb-3 mt-3">
                        <?php
                        if (isset($_GET["error"])) { ?>
                            <p id="error_paragraph" class="error"> <?php echo $_GET["error"]; ?> </p>
                        <?php
                        } ?>
                    </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 mt-3">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3 mt-3">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mb-3 mx-1">Search</button>
        </form>


        <table class="table table-bordered table-hover">
            <tr>
                <th>Date</th>
                <th>Daily Payment</th>
            </tr>
            <?php
            foreach ($dailyPayments as $dailyPayment) {
            ?>
                <tr>
                    <td><?php echo $dailyPayment->getDate(); ?></td>
                    <td><?php echo $dailyPayment->getDailyPayment(); ?></td>
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