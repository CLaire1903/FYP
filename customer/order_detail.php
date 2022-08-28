<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/order.css" rel="stylesheet">

    
</head>

<body>
    <div class="container-fluid p-0">
        <?php
            include 'C:\xampp\htdocs\fyp\config/dbase.php'; 
            include 'navigationBar.php';

            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Order record not found.');

            try {
                $orderQuery = "SELECT * FROM orders WHERE order_id = :order_id";
                $orderStmt = $con->prepare($orderQuery);
                $orderStmt->bindParam(":order_id", $order_id);
                $orderStmt->execute();
                $orderRow = $orderStmt->fetch(PDO::FETCH_ASSOC);
                $order_id = $orderRow['order_id'];
                $order_datentime = $orderRow['order_datentime'];
                $cus_email = $orderRow['cus_email'];
                $order_totalamount = sprintf('%.2f', $orderRow['order_totalamount']);
                $order_depositpaid = sprintf('%.2f', $orderRow['order_depositpaid']);
                $shipping_name = $orderRow['shipping_name'];
                $shipping_phnumber = $orderRow['shipping_phnumber'];
                $shipping_address = $orderRow['shipping_address'];
                $shipping_postcode = $orderRow['shipping_postcode'];
                $order_status = $orderRow['order_status'];
                $order_paymethod = $orderRow['order_paymethod'];
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        ?>
        <div class="mx-5">
            <h1 class="text-center mt-5">Order Detail</h1>
            <table class='table table-hover table-responsive table-bordered mt-5'>
                <thead>
                    <tr>
                        <th class="col-4">Order ID</td>
                        <td><?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Order Date & Time</td>
                        <td><?php echo htmlspecialchars($order_datentime, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Order Status</td>
                        <td><?php echo htmlspecialchars($order_status, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <h2 class="mt-5">Your Orders</h2>
                </tr>
                <?php
                    $orderDetailQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price, od.product_totalamount
                                FROM order_detail od
                                INNER JOIN product p ON od.product_id = p.product_id
                                WHERE order_id = :order_id";
                    $orderDetailStmt = $con->prepare($orderDetailQuery);
                    $orderDetailStmt->bindParam(":order_id", $order_id);
                    $orderDetailStmt->execute();

                    echo "<thead>";
                    echo "<th class='text-center'>Product</th>";
                    echo "<th class='col-3 col-md-2 text-center'>Price per piece</th>";
                    echo "<th class='col-3 col-md-2 text-center'>Total Price</th>";
                    echo "</thead>";

                    echo "<tbody>";
                        while ($orderDetailRow = $orderDetailStmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>";
                            echo "<div class='d-flex'>";
                                echo "<div>";
                                    $product_image = $orderDetailRow['product_image'];
                                    $product_id = $orderDetailRow['product_id'];
                                    echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center'></a>";
                                echo "</div>";
                                echo "<div class='mx-3'>";
                                    echo "<a href='#' class='word text-center text-decoration-none'>$orderDetailRow[product_name]</a>";
                                echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            $productPrice = sprintf('%.2f', $orderDetailRow['product_price']);
                            echo "<td class='text-end'>RM $productPrice</td>";
                            $productTotalAmount = sprintf('%.2f', $orderDetailRow['product_totalamount']);
                            echo "<td class='text-end'>RM $productTotalAmount</td>";
                            echo "</tr>";
                        }
                    echo "</tbody>";
                    echo "<tfoot>";
                        echo "<tr>";
                        echo "<td colspan='2' class='text-end'>The total amount is:</td>";
                        echo "<td class='text-end'>RM $order_totalamount</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='2' class='text-end'>Deposit:</td>";
                        echo "<td class='text-end'>RM $order_depositpaid</td>";
                        echo "</tr>";
                        echo "<tr>";
                        $balance = sprintf('%.2f', $order_totalamount - $order_depositpaid);
                        echo "<td colspan='2' class='balance text-end'>Balance you need to pay:</td>";
                        echo "<td class='balance text-end'>RM $balance</td>";
                        echo "</tr>";
                    echo "</tfoot>";
                ?>   
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <h2 class="mt-5">Billing Details</h2>
                    </tr>
                    <tr> 
                        <th class="col-4">Name</th>
                        <td><?php echo htmlspecialchars($shipping_name, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($shipping_phnumber, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo htmlspecialchars($shipping_address, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Postcode</th>
                        <td><?php echo htmlspecialchars($shipping_postcode, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <h2 class="mt-5">Payment Method:</h2>
                </tr>
                <thead>
                    <tr>
                        <th class="col-4">Payment method</th>
                        <td><?php echo htmlspecialchars($order_paymethod, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <a href='customer_profile.php?cus_email={$cus_email}'  class='actionBtn btn mx-2 mt-3'>Back</a>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>