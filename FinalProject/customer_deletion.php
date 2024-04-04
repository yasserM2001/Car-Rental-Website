<?php
include 'connection.php';
include 'models.php';
$currentFormattedTimestamp = date('Y-m-d H:i:s');

// Check if car_id is set in the POST data
if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $sql = "SELECT * FROM customers AS c NATURAL JOIN reservations AS r WHERE c.customer_id=$customer_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $customers = [];

        // Loop through the rows of the 'cars' table and create Car objects
        while ($row = $result->fetch_assoc()) {
            $customer = new Customer(
                $row['customer_id'],
                $row['fname'],
                $row['lname'],
                $row['email'],
                $row['password'],
                $row['address'],
                $row['phone'],
                $row['wallet']
            );
            $customer->setEndDate($row['endD']);
            $customer->setStartDate($row['startD']);
            $customers[] = $customer;
        }
    }

    foreach ($customers as $customer) {
        if ($customer->getEndDate() > $currentFormattedTimestamp && $customer->getStartDate() < $currentFormattedTimestamp ) {
            header("Location: table-customers.php?error=This customer can't be deleted,this customer has rented cars!!You have to wait or cancel this rental");
            exit();
        }
    }

    // SQL to delete the customer with the specified customer_id
    $sql = "DELETE FROM customers WHERE customer_id = $customer_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the page where the cars are displayed
        header("Location: table-customers.php?success=Customer deleted successfully");
        exit();
    } else {
        // Redirect back with an error message if deletion fails
        header("Location: table_customers.php?error=Error deleting car");
        exit();
    }
} else {
    // Redirect back if customer_id is not set in the POST data
    header("Location: table_customers.php?error=Invalid request");
    exit();
}

$conn->close();