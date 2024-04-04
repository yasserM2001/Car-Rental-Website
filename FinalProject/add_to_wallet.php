<?php
include_once 'connection.php';

function getWalletValue($customerId)
{
    include_once 'connection.php';
    global $conn;
    // Query to get wallet value for the given customer ID
    $sql = "SELECT wallet FROM customers WHERE customer_id = $customerId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['wallet'];
    } else {
        // Handle the case where the customer ID is not found
        return 0; // Default to 0 if the customer ID is not found
    }

    // Note: It's important to close the connection after using it
    $conn->close();
}

// Check if the form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    // Sanitize and validate the amount (you may want to add more validation)
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    // Validate that the amount is a positive number
    if (!is_numeric($amount) || $amount <= 0) {
        header("Location: index.php?error=Invalid amount");
        exit();
    }

    // Get customer ID from the session
    session_start();
    $customerId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$customerId) {
        header("Location: index.php?error=User not logged in");
        exit();
    }

    // Update wallet value in the database
    $sql = "UPDATE customers SET wallet = wallet + $amount WHERE customer_id = $customerId";

    if ($conn->query($sql) === TRUE) {
        // Fetch the updated wallet value
        $updatedWalletValue = getWalletValue($customerId);

        // Redirect back to index with success message
        header("Location: index.php?success=Wallet updated successfully&newWalletValue=$updatedWalletValue");
        exit();
    } else {
        // Redirect back to index with error message
        header("Location: index.php?error=Error updating wallet");
        exit();
    }

    $conn->close();
} else {
    // Redirect back to index with error message if form data is not received
    header("Location: index.php?error=Invalid request");
    exit();
}
?>
