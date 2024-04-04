<!DOCTYPE html>
<html lang="en">
<?php
include 'nav_bar.php';
include 'connection.php';

if (isset($_SESSION['userId'])) {
    if ($_SESSION['userRole'] == ROLE_OFFICE || $_SESSION['userRole'] == ROLE_ADMIN) {

?>

        <head>
            <title>Car Rental MYM</title>
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
                    width: 100%;
                    border-radius: 5px;
                }
            </style>
        </head>

        <body>
            <section class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                <form action="table-cars.php" method="POST" onsubmit="">
                    <h3 class="d-flex align-items-center justify-content-center">Choose day</h3>
                    <hr>
                    
                    <!-- <?php
                    //if (isset($_POST['car_id'])) {

                    ?>
                        <input type="hidden" name="car_id" value="<?php //echo $_POST['car_id']; ?>">
                    <?php
                    //}
                    ?> -->
                    <div id="message_container" class="mb-3 mt-3">
                        <?php
                        if (isset($_GET["error"])) { ?>
                            <p id="error_paragraph" class="error"> <?php echo $_GET["error"]; ?> </p>
                        <?php
                        } ?>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="day" class="form-label">Date:</label>
                        <?php
                        if (isset($_GET['end_date'])) { ?>
                            <input type="date" class="form-control" id="day" placeholder="Choose day" name="day" value="<?php echo $_GET['day'] ?> ">
                        <?php
                        } else { ?>
                            <input type="date" class="form-control" id="day" placeholder="Choose day" name="day">
                        <?php
                        }
                        ?>
                    </div>

                    <div class="mb-3 mt-3">
                        <button type="submit" class="btn btn-primary d-flex mx-auto">Submit</button>
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