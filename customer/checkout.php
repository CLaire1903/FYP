<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Checkout</Details></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/order.css" rel="stylesheet">

    
</head>

<body>
    <div class="container-fluid p-0">
        
        <?php
            include 'C:\xampp\htdocs\fyp\config/dbase.php'; 
            include 'navigationBar.php';

            /*try {
                $checkoutQuery = "SELECT * FROM checkout WHERE cus_email = :cus_email";
                $checkoutStmt = $con->prepare($checkoutQuery);
                $checkoutStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                $checkoutStmt->execute();
                $checkoutRow = $checkoutStmt->fetch(PDO::FETCH_ASSOC);
                $order_id = $order_row['order_id'];
                $order_datentime = $order_row['order_datentime'];
                $order_totalamount = sprintf('%.2f', $order_row['order_totalamount']);
                $order_depositpaid = sprintf('%.2f', $order_row['order_depositpaid']);
                $order_status = ucwords($order_row['order_status']);
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }*/
        ?>
        <h1 class="text-center mt-5">Checkout</h1>
        <div class="mx-5 mt-5">
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <h2>Billing Details</h2>
                    </tr>
                    <tr class="border-start border-end border-top border-0"> 
                        <th class="col-4 d-flex align-self-center border-0 px-3">Name</td>
                        <td class="border-0"><input type='text' name='shipping_name' id="shipping_name" value="<?php echo (isset($_POST['shipping_name'])) ? $_POST['shipping_name'] : ''; ?>" class='form-control'/></td>
                    </tr>
                    <tr class="border-start border-end border-0">
                        <th class="d-flex align-self-center border-0 px-3">Phone Number</td>
                        <td class="border-0"><input type="tel" name="shipping_phnumber" id="shipping_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['shipping_phnumber'])) ? $_POST['shipping_phnumber'] : ''; ?>" class='form-control' ></td>
                    </tr>
                    <tr class="border-start border-end border-0">
                        <th class="d-flex align-self-center border-0 px-3">Address</td>
                        <td class="border-0"><textarea type='text' name='shipping_address' id="shipping_address" class='form-control' rows="3"><?php echo (isset($_POST['shipping_address'])) ? $_POST['shipping_address'] : ''; ?></textarea></td>
                    </tr>
                    <tr class="border-start border-end border-bottom border-0">
                        <th class="d-flex align-self-center border-0 px-3">Postcode</td>
                        <td class="border-0"><input type="tel" name="shipping_postcode" id="shipping_postcode" placeholder="12345" pattern="[0-9]{5}" value="<?php echo (isset($_POST['shipping_postcode'])) ? $_POST['shipping_postcode'] : ''; ?>" class='form-control' ></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <h2 class="mt-4">Your Orders</h2>
                </tr>
                <thead>
                    <tr class='tableHeader'>
                        <th class='text-center'>Product</th>
                        <th class='col-3 col-md-2 text-center'>Price per piece</th>
                        <th class='col-3 col-md-2 text-center'>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //for ($i = 0; $i < count($_POST['product_id']); $i++){
                            $checkoutQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price
                                        FROM checkout co
                                        INNER JOIN product p 
                                        ON co.product_id = p.product_id
                                        WHERE cus_email = :cus_email";
                            $checkoutStmt = $con->prepare($checkoutQuery);
                            $checkoutStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                            $checkoutStmt->execute();
                            while ($checkoutRow = $checkoutStmt->fetch(PDO::FETCH_ASSOC)) {
                                $product_image = $checkoutRow['product_image'];
                                $product_id = $checkoutRow['product_id'][$i];
                                $product_name = $checkoutRow['product_name'];
                                $product_price = sprintf('%.2f', $checkoutRow['product_price']);
                                $productTotalAmount = $product_price;
                                $order_totalamount = 0;
                                $order_totalamount += $productTotalAmount; 
                                echo "<tr>";
                                echo "<td>";
                                echo "<div class='d-flex'>";
                                    echo "<div>";
                                        echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center'></a>";
                                    echo "</div>";
                                    echo "<div class='mx-3'>";
                                        echo "<a href='#' class='word text-center text-decoration-none'>$product_name</a>";
                                    echo "</div>";
                                echo "</div>";
                                echo "</td>";
                                echo "<td class='text-end'>RM $product_price</td>";
                                echo "<td class='text-end'>RM $productTotalAmount</td>";
                                echo "</tr>";
                            }
                        //}
                    echo "</tbody>";
                    echo "<tfoot>";
                        echo "<tr>";
                        echo "<td colspan='2' class='text-end'>The total amount is:</td>";
                        echo "<td class='text-end'>RM $order_totalamount</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='3' class='text-end'>Deposit:</td>";
                        //echo "<td class='text-end'>RM $order_depositpaid</td>";
                        echo "</tr>";
                        echo "<tr>";
                        //$balance = sprintf('%.2f', $order_totalamount - $order_depositpaid);
                        echo "<td colspan='3' class='balance text-end'>Balance you need to pay:</td>";
                        //echo "<td class='balance text-end'>RM $balance</td>";
                        echo "</tr>";
                    echo "</tfoot>";
                ?>   
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