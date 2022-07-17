<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order Detail</Details></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/order.css" rel="stylesheet">

    
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
            include 'navigationBar.php';

            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Order record not found.');
            
            include 'config/dbase.php';

            try {
                $order_query = "SELECT * FROM orders WHERE order_id = :order_id";
                $order_stmt = $con->prepare($order_query);
                $order_stmt->bindParam(":order_id", $order_id);
                $order_stmt->execute();
                $order_row = $order_stmt->fetch(PDO::FETCH_ASSOC);
                $order_id = $order_row['order_id'];
                $order_datentime = $order_row['order_datentime'];
                $order_totalamount = sprintf('%.2f', $order_row['order_totalamount']);
                $order_depositpaid = sprintf('%.2f', $order_row['order_depositpaid']);
                $order_status = ucwords($order_row['order_status']);
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        ?>
        <div class="m-3">
            <table class='table table-hover table-responsive table-bordered'>
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
                <?php
                    $orderDetail_query = "SELECT p.product_id, p.product_image, p.product_name, od.order_quantity, p.product_price, od.product_totalamount
                                FROM orderdetail od
                                INNER JOIN product p ON od.product_id = p.product_id
                                WHERE order_id = :order_id";
                    $orderDetail_stmt = $con->prepare($orderDetail_query);
                    $orderDetail_stmt->bindParam(":order_id", $order_id);
                    $orderDetail_stmt->execute();

                    echo "<thead>";
                    echo "<th class='text-center'>Product</th>";
                    echo "<th class='col-1 text-center'>Quantity</th>";
                    echo "<th class='col-3 col-md-2 text-center'>Price per piece</th>";
                    echo "<th class='col-3 col-md-2 text-center'>Total Price</th>";
                    echo "</thead>";

                    echo "<tbody>";
                        while ($orderDetail_row = $orderDetail_stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>";
                            echo "<div class='d-flex'>";
                                echo "<div>";
                                    $product_image = $orderDetail_row['product_image'];
                                    $product_id = $orderDetail_row['product_id'];
                                    echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center'></a>";
                                echo "</div>";
                                echo "<div class='mx-3'>";
                                    echo "<a href='#' class='word text-center text-decoration-none'>$orderDetail_row[product_name]</a>";
                                echo "</div>";
                            echo "</div>";
                            echo "</td>";
                            echo "<td class='text-center'>$orderDetail_row[order_quantity]</td>";
                            $productPrice = sprintf('%.2f', $orderDetail_row['product_price']);
                            echo "<td class='text-end'>RM $productPrice</td>";
                            $productTotalAmount = sprintf('%.2f', $orderDetail_row['product_totalamount']);
                            echo "<td class='text-end'>RM $productTotalAmount</td>";
                            echo "</tr>";
                        }
                    echo "</tbody>";
                    echo "<tfoot>";
                        echo "<tr>";
                        echo "<td colspan='3' class='text-end'>The total amount is:</td>";
                        echo "<td class='text-end'>RM $order_totalamount</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='3' class='text-end'>Deposit:</td>";
                        echo "<td class='text-end'>RM $order_depositpaid</td>";
                        echo "</tr>";
                        echo "<tr>";
                        $balance = sprintf('%.2f', $order_totalamount - $order_depositpaid);
                        echo "<td colspan='3' class='balance text-end'>Balance you need to pay:</td>";
                        echo "<td class='balance text-end'>RM $balance</td>";
                        echo "</tr>";
                    echo "</tfoot>";
                ?>   
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <a href='customer_profile.php?cus_username={$cus_username}'  class='actionBtn btn mx-2 mt-3'>Back</a>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>