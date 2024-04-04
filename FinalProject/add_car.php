<!DOCTYPE html>
<html lang="en">
<?php
include 'nav_bar.php';
date_default_timezone_set('Africa/Cairo');

if (isset($_SESSION['userRole'])) {
    if ($_SESSION['userRole'] == ROLE_OFFICE) { ?>

<head>
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
        }
    </style>

    <script>
        function validateCarForm() {
            var plateNumber = document.getElementById("plate-number").value;
            var model = document.getElementById("model").value;
            var color = document.getElementById("color").value;
            var year = document.getElementById("year").value;
            var price = document.getElementById("price").value;
            var miles = document.getElementById("miles").value;
            var image = document.getElementById("image").value;

            var messageContainer = document.getElementById("message_container");

            if (plateNumber.trim() === "" || model.trim() === "" || color.trim() === "" || year.trim() === "" || price.trim() === "" || miles.trim() === "" || image.trim() === "") {
                messageContainer.innerHTML = '<p class="error">Please fill out all fields!</p>';
                return false;
            }

            if (isNaN(year) || isNaN(price) || isNaN(miles)) {
                messageContainer.innerHTML = '<p class="error">Please enter valid numeric values for Car Year, Price per Day, and Miles.</p>';
                return false;
            }

            return true; 
        }
    </script>
</head>

<section class="mx-4">

    <form action="add_car_validation.php" method="POST" onsubmit="return validateCarForm()" enctype="multipart/form-data">
        <h3 class="d-flex align-items-center justify-content-center">Add Car</h3>
        <hr>
        <input type="hidden" id="office_id" name="office_id" value="<?php echo $_SESSION['userId']; ?>">

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
        <!-- Plate Number -->
        <div class="mb-3 mt-3">
            <label for="plate-number" class="form-label">Plate Number:</label>
            <?php
            if (isset($_GET['plate-number'])) { ?>
                <input type="text" class="form-control" id="plate-number" placeholder="Enter plate number" name="plate-number" value="<?php echo $_GET['plate-number']; ?>">
            <?php
            } else { ?>
                <input type="text" class="form-control" id="plate-number" placeholder="Enter plate number" name="plate-number">
            <?php
            }
            ?>
        </div>
        <!-- Model -->
        <div class="mb-3 mt-3">
            <label for="model" class="form-label">Car Model:</label>
            <?php
            if (isset($_GET['model'])) { ?>
                <input type="text" class="form-control" id="model" placeholder="Enter car model" name="model" value="<?php echo $_GET['model']; ?>">
            <?php
            } else { ?>
                <input type="text" class="form-control" id="model" placeholder="Enter car model" name="model">
            <?php
            }
            ?>
        </div>
        <!-- Color -->
        <div class="mb-3 mt-3">
            <label for="color" class="form-label">Color:</label>
            <?php
            if (isset($_GET['color'])) { ?>
                <input type="text" class="form-control" id="color" placeholder="Enter car color" name="color" value="<?php echo $_GET['color']; ?>">
            <?php
            } else { ?>
                <input type="text" class="form-control" id="color" placeholder="Enter car color" name="color">
            <?php
            }
            ?>
        </div>

        <div class="mb-3 mt-3">
            <label for="year" class="form-label">Car Year:</label>
            <?php
            if (isset($_GET['year'])) { ?>
                <input type="number" class="form-control" id="year" placeholder="Enter car year" name="year" value="<?php echo $_GET['year']; ?>">
            <?php
            } else { ?>
                <input type="number" class="form-control" id="year" placeholder="Enter car year" name="year">
            <?php
            }
            ?>
        </div>


        <div class="mb-3 mt-3">
            <label for="price" class="form-label">Price per Day:</label>
            <?php
            if (isset($_GET['price'])) { ?>
                <input type="number" class="form-control" id="price" placeholder="Enter price" name="price" value="<?php echo $_GET['price']; ?>">
            <?php
            } else { ?>
                <input type="number" class="form-control" id="price" placeholder="Enter price" name="price">
            <?php
            }
            ?>
        </div>

        <div class="mb-3 mt-3">
            <label for="miles" class="form-label">Miles:</label>
            <?php
            if (isset($_GET['miles'])) { ?>
                <input type="number" class="form-control" id="miles" placeholder="Enter miles" name="miles" value="<?php echo $_GET['miles']; ?>">
            <?php
            } else { ?>
                <input type="number" class="form-control" id="miles" placeholder="Enter miles" name="miles">
            <?php
            }
            ?>
        </div>

        <div class="mb-3 mt-3">
            <label for="image" class="form-label">Car Image:</label>
            <?php
            if (isset($_GET['image'])) { ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php
            } else { ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php
            }
            ?>
        </div>

        <div class="mb-3 mt-3">
            <button type="submit" class="btn btn-primary d-flex mx-auto">Add</button>
        </div>

    </form>

</section>

<?php
    }else{
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
else{
    header("Location: login.php?error=You should login first!!");
    exit();
}
?>

</html>