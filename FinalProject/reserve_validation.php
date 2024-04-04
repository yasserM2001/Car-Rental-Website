<?php

include 'connection.php';

if (isset($_POST['customer_id']) && isset($_POST['car_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $customerId = $_POST['customer_id'];
    $carId = $_POST['car_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $isValid = validateRent($customerId, $carId, $startDate, $endDate);

    if ($isValid) {
        // Perform the rental process
        $success = rentCar($customerId, $carId, $startDate, $endDate);

        if ($success) {
            header("Location: index.php?success=Rent successfull!!");
            exit();
        } else {
            header("Location: index.php?error=Rent failed. Please,Check your wallet may there is no enough funds");
            exit();
        }
    } else {
        header("Location: index.php?error=Not valid reservation!!,The car could be unavailable in this period");
        exit();
    }
} else {
    header("Location: index.php?error=Invalid request. Please provide all required information.");
    exit();
}

function validateRent($customerId, $carId, $startDate, $endDate)
{
    global $conn;
    // Check if the car is available for the specified dates
    $availabilityCheck = "SELECT * FROM reservations 
                          WHERE car_id = $carId 
                          AND (
                              (startD <= '$startDate' AND endD >= '$startDate') OR
                              (startD <= '$endDate' AND endD >= '$endDate') OR
                              ('$startDate' <= startD AND '$endDate' >= startD)
                          )";

    $result = $conn->query($availabilityCheck);
    return ($result->num_rows == 0);
}

function rentCar($customerId, $carId, $startDate, $endDate)
{
    global $conn;

    // Calculate the cost
    $cost = calculateCost($carId, $startDate, $endDate);

    // Check if the customer has enough funds in their wallet
    $checkWalletQuery = "SELECT wallet FROM customers WHERE customer_id = $customerId";
    $walletResult = $conn->query($checkWalletQuery);

    if ($walletResult->num_rows > 0) {
        $walletRow = $walletResult->fetch_assoc();
        $customerWallet = $walletRow['wallet'];

        // Check if the customer has enough funds
        if ($customerWallet >= $cost) {
            // Deduct the cost from the customer's wallet
            $updatedWallet = $customerWallet - $cost;

            // Update the customer's wallet
            $updateWalletQuery = "UPDATE customers SET wallet = $updatedWallet WHERE customer_id = $customerId";
            $conn->query($updateWalletQuery);

            // // Update the car status to rented and set status_id to Rented (assuming Rented status_id is 3)
            // $updateCarStatusQuery = "UPDATE cars SET status_id = 3 WHERE car_id = $carId";
            // $conn->query($updateCarStatusQuery);

            // Insert data into the reservations table
            $insertQuery = "INSERT INTO reservations (customer_id, car_id, startD, endD, cost) 
                            VALUES ($customerId, $carId, '$startDate', '$endDate', $cost)";

            $result = $conn->query($insertQuery);

            // Return true if the insertion is successful, false otherwise
            return ($result === TRUE);
        } else {
            // Customer doesn't have enough funds
            return false;
        }
    } else {
        // Customer not found
        return false;
    }
}


function calculateCost($carId, $startDate, $endDate)
{
    global $conn;

    // Fetch the daily rate for the specified car
    $dailyRateQuery = "SELECT price_per_day FROM cars WHERE car_id = " . $carId;
    $result = $conn->query($dailyRateQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dailyRate = $row['price_per_day'];

        // Calculate the duration in days
        $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);

        // Calculate the total cost
        $totalCost = $days * $dailyRate;

        return $totalCost;
    } else {
        return 0;
    }
}
