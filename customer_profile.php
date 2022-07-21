<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
</head>

<body>
    <div class="container-flex">
        <?php
        include 'navigationBar.php';
        ?>
        <div class="page-header mx-5 mt-5">
            <h1>My Profile</h1>
        </div>

        <?php
        include 'config/dbase.php';

        try {
            $customerQuery = "SELECT * FROM customer WHERE cus_email = :cus_email";
            $customerStmt = $con->prepare($customerQuery);
            $customerStmt->bindParam(":cus_email", $_SESSION['cus_email']);
            $customerStmt->execute();
            $customerRow = $customerStmt->fetch(PDO::FETCH_ASSOC);

            $cus_email = $customerRow['cus_email'];
            $cus_fname = ucfirst($customerRow['cus_fname']);
            $cus_lname = ucfirst($customerRow['cus_lname']);
            $cus_address = ucwords($customerRow['cus_address']);
            $cus_gender = $customerRow['cus_gender'];
            $cus_bday = $customerRow['cus_bday'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="mx-5">
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <th class="title col-4">Email</th>
                        <td><?php echo htmlspecialchars($cus_email, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo htmlspecialchars($cus_fname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?php echo htmlspecialchars($cus_lname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($cus_address, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($cus_gender, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td><?php echo htmlspecialchars($cus_bday, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
        </div>

        <div>
            <h1 class="mx-5 mt-5">Order History</h1>
            <div class="mx-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th>Order ID</th>
                            <th>Order Date and Time</th>
                            <th>Total Amount</th>
                            <th>Paid Deposit</th>
                            <th>Order Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $orderHistoryQuery = "SELECT * FROM orders WHERE cus_email = :cus_email ORDER BY order_id DESC ";
                            $orderHistoryStmt = $con->prepare($orderHistoryQuery);
                            $orderHistoryStmt->bindParam(":cus_email", $_SESSION['cus_email']);
                            $orderHistoryStmt->execute();
                            while ($orderHistoryRow = $orderHistoryStmt->fetch(PDO::FETCH_ASSOC)) {
                                $order_id = $orderHistoryRow['order_id'];
                                $order_datentime = $orderHistoryRow['order_datentime'];
                                $cus_email = $orderHistoryRow['cus_email'];
                                $order_totalamount = sprintf('%.2f', $orderHistoryRow['order_totalamount']);
                                $order_depositpaid = sprintf('%.2f', $orderHistoryRow['order_depositpaid']);
                                $order_status = ucwords($orderHistoryRow['order_status']);
                                echo "<tr>";
                                echo "<td><a href='order_detail.php?order_id={$order_id}' class='orderDetail text-center'>$order_id</a></td>";
                                echo "<td>{$order_datentime}</td>";
                                echo "<td>RM {$order_totalamount}</td>";
                                echo "<td>RM {$order_depositpaid}</td>";
                                echo "<td>{$order_status}</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-center">
            <?php
            echo "<a href='customer_updateProfile.php?cus_email={$cus_email}' class='actionBtn btn mx-2 mt-5'>Update Profile</a>";
            ?>
        </div>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>