<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Order</Details></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/order.css" rel="stylesheet">

    <style>
        .form-control[readonly]{
            background-color: white;
        }
    </style>
    
</head>

<body>
    <div class="container-fluid p-0">
        <?php
            include 'C:\xampp\htdocs\fyp\config/dbase.php'; 
            include 'C:\xampp\htdocs\fyp\alertIcon.php';
            include 'navigationBar.php';
            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: 
            Order record not found.');
        ?>
        <div class="mx-5 mt-5">
            <h1 class="text-center mt-5">Update Order</h1>
            <?php
                if($_POST){
                    try {
                        if (empty($_POST['order_depositpaid']) || empty($_POST['shipping_name']) || empty($_POST['shipping_phnumber']) || empty($_POST['shipping_address']) || empty($_POST['shipping_postcode']) || empty($_POST['order_paymethod'])) {
                            throw new Exception("Please make sure all fields are not empty !");
                        }

                        $depositPercent = ($_POST['order_depositpaid'] * 100) / $_POST['order_totalamount'];
                        if ($depositPercent < 30){
                            throw new Exception("Deposit must be at least 30% from the total amount!");
                        }

                        $con->beginTransaction();

                        $updateOrderQuery = "UPDATE orders SET order_depositpaid=:order_depositpaid, shipping_name=:shipping_name, shipping_phnumber=:shipping_phnumber, shipping_address=:shipping_address, shipping_postcode=:shipping_postcode, order_status=:order_status, order_paymethod=:order_paymethod WHERE order_id=:order_id";
                        $updateOrderStmt = $con->prepare($updateOrderQuery);
                        $order_depositpaid = htmlspecialchars(strip_tags($_POST['order_depositpaid']));
                        $shipping_name = htmlspecialchars(strip_tags($_POST['shipping_name']));
                        $shipping_phnumber = htmlspecialchars(strip_tags($_POST['shipping_phnumber']));
                        $shipping_address = htmlspecialchars(strip_tags($_POST['shipping_address']));
                        $shipping_postcode = htmlspecialchars(strip_tags($_POST['shipping_postcode']));
                        $order_status = htmlspecialchars(strip_tags($_POST['order_status']));
                        $order_paymethod = htmlspecialchars(strip_tags($_POST['order_paymethod']));

                        $updateOrderStmt->bindParam(':order_id', $order_id);
                        $updateOrderStmt->bindParam(':order_depositpaid', $order_depositpaid);
                        $updateOrderStmt->bindParam(':shipping_name', $shipping_name);
                        $updateOrderStmt->bindParam(':shipping_phnumber', $shipping_phnumber);
                        $updateOrderStmt->bindParam(':shipping_address', $shipping_address);
                        $updateOrderStmt->bindParam(':shipping_postcode', $shipping_postcode);
                        $updateOrderStmt->bindParam(':order_status', $order_status);
                        $updateOrderStmt->bindParam(':order_paymethod', $order_paymethod);

                        if ($updateOrderStmt->execute()) {
                            echo "<script>window.location.href='order_detail.php?order_id='+ '$order_id' + '&action=updated';</script>";
                            }
                            else {
                                echo "<script>window.location.href='order_detail.php?order_id='+ '$order_id' + '&action=updateFail';</script>";
                            }
                        $con->commit();
                    } catch (PDOException $exception) {
                        echo "<div class='alert alert-danger d-flex align-items-center m-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                            " . $exception->getMessage() . "
                            </div>
                        </div>";
                    } catch (Exception $exception) {
                        echo "<div class='alert alert-danger d-flex align-items-center m-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                            " . $exception->getMessage() . "
                            </div>
                        </div>";
                    }
                    
                }
                
            ?>

            <?php 
            $getOrderQuery = "SELECT * FROM orders WHERE order_id = :order_id";
            $getOrderStmt = $con->prepare($getOrderQuery);
            $getOrderStmt->bindParam(":order_id", $order_id);
            $getOrderStmt->execute();
            $getOrderRow = $getOrderStmt->fetch(PDO::FETCH_ASSOC);
            $order_id = $getOrderRow['order_id'];
            $order_datentime = $getOrderRow['order_datentime'];
            $cus_email = $getOrderRow['cus_email'];
            $order_totalamount = sprintf('%.2f', $getOrderRow['order_totalamount']);
            $order_depositpaid = sprintf('%.2f', $getOrderRow['order_depositpaid']);
            $shipping_name = $getOrderRow['shipping_name'];
            $shipping_phnumber = $getOrderRow['shipping_phnumber'];
            $shipping_address = $getOrderRow['shipping_address'];
            $shipping_postcode = $getOrderRow['shipping_postcode'];
            $order_status = $getOrderRow['order_status'];
            $order_paymethod = $getOrderRow['order_paymethod'];
            ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?order_id={$order_id}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
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
                    <tr class="border-start">
                        <th class="d-flex border-0">Order Status</td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="new order" <?php echo htmlspecialchars($order_status == 'new order') ? 'checked' : '' ?>>
                                    New Order
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="payment received" <?php echo htmlspecialchars($order_status == 'payment received') ? 'checked' : '' ?>>
                                    Payment Received
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="payment failed" <?php echo htmlspecialchars($order_status == 'payment failed') ? 'checked' : '' ?>>
                                    Payment Failed
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="processing" <?php echo htmlspecialchars($order_status == 'processing') ? 'checked' : '' ?>>
                                    Processing
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="shipping" <?php echo htmlspecialchars($order_status == 'shipping') ? 'checked' : '' ?>>
                                    Shipping
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="cancelled" <?php echo htmlspecialchars($order_status == 'cancelled') ? 'checked' : '' ?>>
                                    Cancelled
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_status" class="order_status" value="completed" <?php echo htmlspecialchars($order_status == 'completed') ? 'checked' : '' ?>>
                                    Completed
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </thead>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <h2 class="mt-5">Your Orders</h2>
                </tr>
                <thead>
                    <tr class='tableHeader'>
                        <th class='text-center'>Product</th>
                        <th class='col-3 col-md-2 text-center'>Price per piece (RM)</th>
                        <th class='col-3 col-md-2 text-center'>Total Price (RM)</th>
                    </tr>
                </thead>
                <tfoot>
                    <?php
                        $getProductQuery = "SELECT od.product_id, p.product_image, p.product_name, p.product_price, od.product_totalamount
                                    FROM order_detail od
                                    INNER JOIN product p 
                                    ON od.product_id = p.product_id
                                    WHERE order_id = :order_id";
                        $getProductStmt = $con->prepare($getProductQuery);
                        $getProductStmt->bindParam(":order_id", $order_id);
                        $getProductStmt->execute();
                        while ($getProductRow = $getProductStmt->fetch(PDO::FETCH_ASSOC)) {
                            $product_image = $getProductRow['product_image'];
                            $product_id = $getProductRow['product_id'];
                            $product_name = $getProductRow['product_name'];
                            $product_price = sprintf('%.2f', $getProductRow['product_price']);
                            $product_totalamount = sprintf('%.2f', $getProductRow['product_totalamount']);
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex">
                                        <div class="d-flex justify-content-center col-2">
                                            <a <?php echo"href='product_detail.php?product_id={$product_id}'";?> ><img src="<?php echo htmlspecialchars($product_image, ENT_QUOTES); ?>" class='productImage d-flex justify-content-center rounded'></a>
                                        </div>
                                        <div class='mx-3'>
                                            <a <?php echo"href='product_detail.php?product_id={$product_id}'";?> class='word text-center text-decoration-none'><?php echo htmlspecialchars($product_name, ENT_QUOTES); ?></a>
                                            <input type='hidden' name='product_id[]' id='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES);?>" class='cartProduct form-control text-center border border-0' readonly/>
                                        </div>
                                    </div>
                                </td>
                                <td><input name='product_price' id='product_price' value="<?php echo htmlspecialchars($product_price, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' readonly/></td>
                                <td><input name='product_totalamount' id='product_totalamount' value="<?php echo htmlspecialchars($product_totalamount, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' aria-readonly="true" readonly/></td>
                            </tr>
                        <?php }?>
                        <tr>
                        <td colspan='2' class='text-end'>The total amount is: RM</td>
                        <td class='text-end'><input name='order_totalamount' id='order_totalamount' value="<?php echo htmlspecialchars($order_totalamount, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' readonly/></td>
                        </tr>
                    </tfoot>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <h2 class="mt-5">Billing Details</h2>
                    </tr>
                    <tr class="border-start border-end border-top border-0"> 
                        <th class="col-4 d-flex align-self-center border-0 px-3">Name</th>
                        <td class="border-0"><input type='text' name='shipping_name' id="shipping_name" value="<?php echo htmlspecialchars($shipping_name, ENT_QUOTES); ?>" class='form-control'/></td>
                    </tr>
                    <tr class="border-start border-end border-0">
                        <th class="d-flex align-self-center border-0 px-3">Phone Number</th>
                        <td class="border-0"><input type="tel" name="shipping_phnumber" id="shipping_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($shipping_phnumber, ENT_QUOTES); ?>" class='form-control' ></td>
                    </tr>
                    <tr class="border-start border-end border-0">
                        <th class="d-flex align-self-center border-0 px-3">Address</th>
                        <td class="border-0"><textarea type='text' name='shipping_address' id="shipping_address" class='form-control' rows="3"><?php echo htmlspecialchars($shipping_address, ENT_QUOTES); ?></textarea></td>
                    </tr>
                    <tr class="border-start border-end border-bottom border-0">
                        <th class="d-flex align-self-center border-0 px-3">Postcode</th>
                        <td class="border-0"><input type="tel" name="shipping_postcode" id="shipping_postcode" placeholder="12345" pattern="[0-9]{5}" value="<?php echo htmlspecialchars($shipping_postcode, ENT_QUOTES); ?>" class='form-control' ></td>
                    </tr>
                </thead>
            </table>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <h2 class="mt-5">Deposit and Payment Method:</h2>
                </tr>
                <thead>
                    <tr class="border-start border-end border-top border-0"> 
                        <th class="d-flex align-self-center border-0 px-3">Deposit paid: RM</th>
                        <td class="border-0"><input type='text' name='order_depositpaid' id="order_depositpaid" value="<?php echo htmlspecialchars($order_depositpaid, ENT_QUOTES); ?>" class='form-control'/></td>
                    </tr>
                    <tr class="border-start border-end border-bottom border-0">
                        <th class="d-flex align-self-center border-0 px-3">Payment method</th>
                        <td class="border-0">
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_paymethod" class="order_paymethod" value="online" <?php echo htmlspecialchars($order_paymethod == 'online') ? 'checked' : '' ?>>
                                    Online Transfer
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="order_paymethod" class="order_paymethod" value="ewallet" <?php echo htmlspecialchars($order_paymethod == 'ewallet') ? 'checked' : '' ?>>
                                    E-wallet (Touch n Go e-wallet, Boost, Grabpay, ShopeePay)
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </thead>
            </table>
            <div class='button d-grid m-3 d-flex justify-content-center'>
                <button type='submit' class='actionBtn btn btn-lg mt-5'>Update</button>
            </div>
        </form>
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>