<?php
include 'connection.php';

if (isset($_POST['office-name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['country']) && isset($_POST['city'])) {
    $officeName = $_POST["office-name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $country = $_POST["country"];
    $city = $_POST["city"];

    $user_input = "office-name=" . $officeName . "&email=" . $email . "&country=" . $country . "&city=" . $city;
    
    if (empty($officeName)) {
        header("Location: add_office.php?error=Office Name is required.&email=" . $email . "&country=" . $country . "&city=" . $city);
        exit();
    } elseif (empty($email)) {
        header("Location: add_office.php?error=Email is required.&office-name=" . $officeName . "&country=" . $country . "&city=" . $city);
        exit();
    } elseif (empty($password)) {
        header("Location: add_office.php?error=Password is required.&$user_input");
        exit();
    } elseif ($password != $confirmPassword) {
        header("Location: add_office.php?error=Confirm password should match password.&$user_input");
        exit();
    } elseif (empty($country)) {
        header("Location: add_office.php?error=Country is required.&office-name=" . $officeName . "&email=" . $email . "&city=" . $city);
        exit();
    } elseif (empty($city)) {
        header("Location: add_office.php?error=City is required.&office-name=" . $officeName . "&email=" . $email . "&country=" . $country);
        exit();
    } else {
        $sql1 = 'SELECT * FROM `offices` WHERE `email`="' . $email . '"';

        $result = $conn->query($sql1);

        if ($result->num_rows > 0) {
            header("Location: add_office.php?error=Email is already taken.&$user_input");
            exit();
        } 
        
        
        // Check if the office name is already taken
        $sql2 = 'SELECT * FROM `offices` WHERE `name`="' . $officeName . '"';
        $result2 = $conn->query($sql2);

        if ($result2->num_rows > 0) {
            header("Location: add_office.php?error=Office Name is already taken.&$user_input");
            exit();
        }

        else {
            // Insert new office if email and office name are not taken
            $sql3 = 'INSERT INTO offices (`name`, `email`, `password`, `country`, `city`)
            VALUES ("' . $officeName . '","' . $email . '","' . $password . '","' . $country . '", "' . $city . '")';

            $result3 = mysqli_query($conn, $sql3);

            if ($result3) {
                header("Location: add_office.php?success=New office successfully registered!");
                exit();
            } else {
                header("Location: add_office.php?error=Unknown error!");
                exit();
            }
            $conn->close();
        }
    }
} else {
    header("Location: add_office.php");
    exit();
}
?>
