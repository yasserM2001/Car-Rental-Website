<?php
session_start();
date_default_timezone_set('Africa/Cairo');
function getWalletValue($customerId)
{
    include_once 'connection.php';
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

    $conn->close();
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MYM Car Rental</title>
    <?php
    include 'links.html';
    include_once 'constants.php';
    ?>




</head>
<header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-sm navbar-light bg-light static-top" style="background-image: url('Images/'); background-size: cover; background-repeat: no-repeat;">
        <div class="container">
            <a class="navbar-brand" href="index.php">MYM Cars</a>
            <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <div class="navbar-collapse collapse d-sm-inline-flex justify-content-between">
                <ul class="navbar-nav flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="index.php">Home</a>
                    </li>


                    <?php
                    if (isset($_SESSION['userId'])) {
                        if ($_SESSION['userRole'] == ROLE_ADMIN) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_page.php">Admin Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="table_daily_payments.php">Daily Payments</a>
                            </li>
                        <?php  }

                        if ($_SESSION['userRole'] == ROLE_OFFICE) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="table-cars.php">My Cars</a>
                            </li>
                        <?php  }
                        if ($_SESSION['userRole'] == ROLE_CUSTOMER) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="table-reservations.php">Your Reservations</a>
                            </li>
                    <?php  }
                    }
                    ?>

                </ul>
                <div class="d-flex">
                    <?php
                    if (!isset($_SESSION['email'])) { ?>
                        <a class="btn btn-primary me-1" href="signup.php">Sign Up</a>
                        <a class="btn btn-primary me-1" href="login.php">Login</a>
                        <?php
                    } else {
                        if (isset($_SESSION['userId'])) {
                            if ($_SESSION['userRole'] == ROLE_CUSTOMER) {
                                $walletValue = getWalletValue($_SESSION['userId']);
                        ?>
                                <button class="btn btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#walletModal">
                                    <span class="bi bi-wallet2 me-1"></span>
                                    Wallet
                                    <span id="cartTotal" class="badge bg-primary text-white ms-1 rounded-pill"><?php echo '$ ' . $walletValue ?></span>
                                </button>
                        <?php
                            }
                        }

                        ?>

                        <a class="btn btn-primary me-1" href="logout.php">Log Out</a>
                    <?php }

                    ?>
                </div>
            </div>
        </div>
    </nav>

</header>

<!-- Wallet Modal -->
<div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="walletModalLabel">Add Funds to Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="walletForm" action="add_to_wallet.php" method="POST">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount:</label>
                        <input type="number" class="form-control" placeholder="Enter Amount" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Wallet</button>
                </form>
            </div>
        </div>
    </div>
</div>