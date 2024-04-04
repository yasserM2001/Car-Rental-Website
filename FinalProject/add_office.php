<!DOCTYPE html>
<html lang="en">
<?php
include 'nav_bar.php';
if (isset($_SESSION['userRole'])) {
    if ($_SESSION['userRole'] == ROLE_ADMIN) { ?>
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
                function validateOfficeForm() {
                    var office_name = document.getElementById("office-name").value;
                    var email = document.getElementById("email").value;
                    var password = document.getElementById("password").value;
                    var confirmPassword = document.getElementById("confirm_password").value;
                    var country = document.getElementById("country").value;
                    var city = document.getElementById("city").value;

                    if (office_name == "" || email == "" || password == "" || country == "" || city == "") {
                        var messageContainer = document.getElementById("message_container");
                        messageContainer.innerHTML =
                            `<p class="error">Please fill out all fields!</p>`;
                        return false;
                    }

                    if (password != confirmPassword) {
                        console.log('password');
                        var message_container = document.getElementById("message_container");
                        message_container.innerHTML =
                            `<p class="error">Confirm password should match password</p>`;
                        return false;
                    }

                    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                    if (!emailRegex.test(email)) {
                        var messageContainer = document.getElementById("message_container");
                        messageContainer.innerHTML =
                            `<p class="error">Please enter a valid email address!</p>`;
                        return false;
                    }

                    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
                    if (!passwordRegex.test(password)) {
                        var message_container = document.getElementById("message_container");
                        message_container.innerHTML =
                            `<p class="error">Please enter a valid password. Password should contain at least 8 characters, one uppercase letter, one lowercase letter and one number!</p>`;
                        return false;
                    }

                    return true;
                }
            </script>

        </head>


        <section class="mx-4">

            <form action="add_office_validation.php" method="POST" onsubmit="return validateOfficeForm()" enctype="multipart/form-data">
                <h3 class="d-flex align-items-center justify-content-center">Add Office</h3>
                <hr>

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
                <!-- Office Name -->
                <div class="mb-3 mt-3">
                    <label for="office-name" class="office-name">Office Name:</label>
                    <?php
                    if (isset($_GET['office-name'])) { ?>
                        <input type="text" class="form-control" id="office-name" placeholder="Enter office name" name="office-name" value="<?php echo $_GET['office-name']; ?>">
                    <?php
                    } else { ?>
                        <input type="text" class="form-control" id="office-name" placeholder="Enter office name" name="office-name">
                    <?php
                    }
                    ?>
                </div>
                <!-- Email -->
                <div class="mb-3 mt-3">
                    <label for="email" class="form-label">Email:</label>
                    <?php
                    if (isset($_GET['email'])) { ?>
                        <input type="text" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $_GET['email']; ?>">
                    <?php
                    } else { ?>
                        <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                    <?php
                    }
                    ?>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
                </div>
                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirm password" name="confirm_password">
                </div>

                <div class="mb-3 mt-3">
                    <label for="country" class="form-label">Country:</label>
                    <?php
                    if (isset($_GET['country'])) { ?>
                        <input type="text" class="form-control" id="country" placeholder="Enter country" name="country" value="<?php echo $_GET['country']; ?>">
                    <?php
                    } else { ?>
                        <input type="text" class="form-control" id="country" placeholder="Enter country" name="country">
                    <?php
                    }
                    ?>
                </div>


                <div class="mb-3 mt-3">
                    <label for="city" class="form-label">City:</label>
                    <?php
                    if (isset($_GET['city'])) { ?>
                        <input type="text" class="form-control" id="city" placeholder="Enter city" name="city" value="<?php echo $_GET['city']; ?>">
                    <?php
                    } else { ?>
                        <input type="text" class="form-control" id="city" placeholder="Enter city" name="city">
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