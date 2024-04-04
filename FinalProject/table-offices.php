<!DOCTYPE html>
<html>
<?php
include 'nav_bar.php';
include 'connection.php';
include 'models.php';



if (isset($_SESSION['userRole'])) {
    if ($_SESSION['userRole'] == ROLE_ADMIN) {
        $userRole = $_SESSION['userRole'];
        // Fetch cars data from the 'cars' table
        $sql = "SELECT * FROM offices";
        $result = $conn->query($sql);
        $offices = [];
        if ($result->num_rows > 0) {
            // Create an array to store Car objects

            // Loop through the rows of the 'cars' table and create Car objects
            while ($row = $result->fetch_assoc()) {
                $office = new Office(
                    $row['office_id'],
                    $row['city'],
                    $row['country'],
                    $row['name']
                );


                $offices[] = $office;
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
        <h2>offices</h2>
        <hr />
        <?php

        if ($userRole == ROLE_ADMIN) {
        ?> <a href="add_office.php" class="btn btn-primary mb-3 mx-1">Add New office</a>
        <?php } ?>

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

        <table class="table table-bordered table-hover">
            <tr>
                <th>Name Office</th>
                <th>City</th>
                <th>Country</th>
                <th colspan="2">Options</th>
            </tr>
            <?php
            foreach ($offices as $office) {
            ?>
                <tr>
                    <td><?php echo $office->getName(); ?></td>
                    <td><?php echo $office->getCity(); ?></td>
                    <td><?php echo $office->getCountry(); ?></td>

                    <td>
                        <form action="office-deletion.php" method="POST">
                            <input type="hidden" name="office-id" value="<?php echo $office->getOfficeId(); ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                    <td>
                        <form action="table-cars.php" method="POST">
                            <input type="hidden" name="office-id" value="<?php echo $office->getOfficeId(); ?>">
                            <button type="submit" class="btn btn-success">View Cars In This Office</button>
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