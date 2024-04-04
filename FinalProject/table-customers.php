<!DOCTYPE html>
<html>
<?php
include 'nav_bar.php';
include 'connection.php';
include 'models.php';


if (isset($_SESSION['userRole'])) {
    if ($_SESSION['userRole'] == ROLE_ADMIN) {
        $userRole = $_SESSION['userRole'];
        // Fetch customers data from the 'customers' table
        $sql = "SELECT * FROM customers AS c";
        $result = $conn->query($sql);
        $customers = [];
        if ($result->num_rows > 0) {


            while ($row = $result->fetch_assoc()) {

                $customer_id = $row['customer_id'];
                $first_name = $row['fname'];
                $last_name = $row['lname'];
                $phone_num = $row['phone'];
                $password = $row['password'];
                $wallet = $row['wallet'];

                $customer = new Customer($customer_id, $first_name, $last_name, $phone_num, $password, $wallet);


                $customers[] = $customer;
            }
        }

?>

        <head>
            <style>
                .error {
                    background: #F2DEDE;
                    color: #A94442;
                    padding: 10px;
                    width: 95%;
                    border-radius: 5px;
                }

                .success {
                    background: #D4EDDA;
                    color: #40754C;
                    padding: 10px;
                    width: 95%;
                    border-radius: 5px;
                    margin: 20px auto;
                }
            </style>
        </head>
        <h2>Customers</h2>
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

        <!-- <button id="AddCategoryBtn" onclick="NewCategory()" class="btn btn-dark mb-3">Add New Category</button> -->


        <table class="table table-bordered table-hover">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone no.</th>
                <th>wallet</th>

                <th colspan="2">Options</th>
            </tr>
            <?php
            foreach ($customers as $customer) {
            ?>
                <tr>
                    <td><?php echo $customer->getFirstName(); ?></td>
                    <td><?php echo $customer->getLastName(); ?></td>
                    <td><?php echo $customer->getPhoneNum(); ?></td>
                    <td><?php echo $customer->getWallet(); ?></td>

                    <td>
                        <form action="customer_deletion.php" method="POST">
                            <input type="hidden" name="customer_id" value="<?php echo $customer->getCustomerId(); ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                        </form>
                    </td>
                    <td>
                        <form action="table-reservations.php" method="POST">
                            <input type="hidden" name="customer_id" value="<?php echo $customer->getCustomerId(); ?>">
                            <button type="submit" class="btn btn-success">View reservations</button>
                        </form>
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

</html>