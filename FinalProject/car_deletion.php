<?php
include 'connection.php';

// Check if car_id is set in the POST data
if (isset($_POST['car_id'])) {
    $car_id = $_POST['car_id'];

    // Check if the car is rented (status_id = 3 indicates rented status)
    $checkRentStatus = "SELECT status_id FROM cars WHERE car_id = $car_id";
    $result = $conn->query($checkRentStatus);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status_id = $row['status_id'];

        // Check if the car is rented
        if ($status_id == 3) {
            // Redirect back with an error message indicating the car is rented
            header("Location: table-cars.php?error=Cannot delete a rented car");
            exit();
        }
    }

    // SQL to delete the car with the specified car_id
    $sql = "DELETE FROM cars WHERE car_id = $car_id";

    if ($conn->query($sql) === TRUE) {
        if (isset($_POST['office_id'])) {
            $_SESSION['office_id'] = $_POST['office_id'];
        }
        // Redirect back to the page where the cars are displayed
        header("Location: table-cars.php?success=Car deleted successfully");
        exit();
    } else {
        // Redirect back with an error message if deletion fails
        header("Location: table-cars.php?error=Error deleting car");
        exit();
    }
} else {
    // Redirect back if car_id is not set in the POST data
    header("Location: table-cars.php?error=Invalid request");
    exit();
}

$conn->close();
?>
