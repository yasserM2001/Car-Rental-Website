<?php
include 'connection.php';
session_start();
date_default_timezone_set('Africa/Cairo');

if (isset($_SESSION['userId']) && isset($_POST['office_id']) && isset($_POST['plate-number']) && isset($_POST['model']) && isset($_POST['color']) && isset($_POST['year']) && isset($_POST['price']) && isset($_POST['miles']) && isset($_FILES['image'])) {
    $plate_number = $_POST["plate-number"];
    $model = $_POST["model"];
    $color = $_POST["color"];
    $year = $_POST["year"];
    $price = $_POST["price"];
    $miles = $_POST["miles"];
    $image = $_FILES["image"]["name"];
    $officeId = $_SESSION['userId'];

    $user_input = "plate-number=" . $plate_number . "&model=" . $model . "&color=" . $color . "&year=" . $year . "&price=" . $price . "&miles=" . $miles . "&image=" . $image;
    if (empty($plate_number)) {
        header("Location: add_car.php?error=plate number is required.&model=" . $model . "&color=" . $color . "&year=" . $year . "&price=" . $price . "&miles=" . $miles . "&image=" . $image);
        exit();
    } elseif (empty($model)) {
        header("Location: add_car.php?error=Model is required.&plate-number=" . $plate_number . "&color=" . $color . "&year=" . $year . "&price=" . $price . "&miles=" . $miles . "&image=" . $image);
        exit();
    } elseif (empty($color)) {
        header("Location: add_car.php?error=Color is required.&plate-number=" . $plate_number . "&model=" . $model . "&year=" . $year . "&price=" . $price . "&miles=" . $miles . "&image=" . $image);
        exit();
    } elseif (empty($year)) {
        header("Location: add_car.php?error=Year is required.&plate-number=" . $plate_number . "&model=" . $model . "&color=" . $color . "&price=" . $price . "&miles=" . $miles . "&image=" . $image);
        exit();
    } elseif (empty($price)) {
        header("Location: add_car.php?error=price is required.&plate-number=" . $plate_number . "&model=" . $model . "&color=" . $color . "&year=" . $year . "&miles=" . $miles . "&image=" . $image);
        exit();
    } elseif (empty($miles)) {
        header("Location: add_car.php?error=miles are required.&plate-number=" . $plate_number . "&model=" . $model . "&color=" . $color . "&year=" . $year . "&price=" . $price . "&image=" . $image);
        exit();
    } elseif (empty($image)) { // $_FILES["image"]["error"] == UPLOAD_ERR_NO_FILE
        header("Location: add_car.php?error=image is required.&plate-number=" . $plate_number . "&model=" . $model . "&color=" . $color . "&year=" . $year . "&price=" . $price . "&miles=" . $miles);
        exit();
    } else {
        $sql1 = 'SELECT * FROM `cars` WHERE `plate_no`="' . $plate_number . '"';

        $result = $conn->query($sql1);

        if ($result->num_rows > 0) {
            header("Location: add_car.php?error=plate-number already exists.&$user_input");
            exit();
        } else {
            $targetDir = "Images/";
            $currentFormattedTimestamp = date('Y-m-d_H-i-s');
            $fileName = $currentFormattedTimestamp . '_' . basename($image); 
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            // Check if the file is an actual image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    echo "The file " . $fileName . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "File is not an image.";
            }
            $sql2 = 'INSERT INTO cars ( `model`, `plate_no`, `color`, `year`, `miles`,`price_per_day`,`office_id`,`image` )
                    VALUES ("' . $model . '", "' . $plate_number . '","' . $color . '", "' . $year . '","' . $miles . '","' . $price . '","' . $officeId . '","' . $fileName . '")';

            $result2 = mysqli_query($conn, $sql2);

            if ($result2) {
                header("Location: add_car.php?success=Car succesfully added!");
                exit();
            } else {
                header("Location: add_car.php?error=Unknown error!");
                exit();
            }
            $conn->close();
        }
    }
} else {
    header("Location:  add_car.php");
    exit();
}
