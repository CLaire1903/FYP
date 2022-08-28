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

    <style>
        .form-control[readonly]{
            background-color: white;
        }
        .checkoutBtn {
            background-color: rgb(252, 184, 192);
            width: 100px;
            height: 50px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .checkoutBtn:hover {
            background-color: rgb(252, 184, 192);
            font-weight: bold;
        }
    </style>
    
</head>

<body>
    <div class="container-fluid p-0">
        <?php
            include 'C:\xampp\htdocs\fyp\config/dbase.php'; 
            include 'C:\xampp\htdocs\fyp\alertIcon.php';
            include 'navigationBar.php';
        ?>
        <div class="mx-5 mt-5">
            <h1 class="text-center mt-5">Checkout</h1>
            <?php
                if($_POST){
                    try {
                        if (empty($_POST['order_depositpaid']) || empty($_POST['shipping_name']) || empty($_POST['shipping_phnumber']) || empty($_POST['shipping_address']) || empty($_POST['shipping_postcode']) || empty($_POST['order_paymethod'])) {
                            throw new Exception("Please make sure all fields are not empty !");
                        }

                        $depositPercent = ($_POST['order_depositpaid'] * 100) / $_POST['checkout_totalamount'];
                        if ($depositPercent < 30){
                            throw new Exception("Deposit must be at least 30% from the total amount!");
                        }

                        $con->beginTransaction();
                        $getCheckoutIdQuery = "SELECT checkout_id FROM checkout WHERE cus_email=:cus_email";
                        $getCheckoutIdStmt = $con->prepare($getCheckoutIdQuery);
                        $getCheckoutIdStmt->bindParam(":cus_email", $_SESSION['cus_email']);
                        $getCheckoutIdStmt->execute();
                        $getCheckoutIdRow = $getCheckoutIdStmt->fetch(PDO::FETCH_ASSOC);
                        $checkout_id = $getCheckoutIdRow['checkout_id'];

                        $createOrderQuery = "INSERT INTO orders SET order_datentime=:order_datentime, cus_email=:cus_email, order_totalamount=:order_totalamount, order_depositpaid=:order_depositpaid, shipping_name=:shipping_name, shipping_phnumber=:shipping_phnumber, shipping_address=:shipping_address, shipping_postcode=:shipping_postcode, order_status=:order_status, order_paymethod=:order_paymethod";

                        $createOrderStmt = $con->prepare($createOrderQuery);

                        $order_datentime = date('Y-m-d H:i:s');
                        $cus_email = $_SESSION["cus_email"];
                        $order_totalamount = htmlspecialchars(strip_tags($_POST['checkout_totalamount']));
                        $order_depositpaid = $_POST['order_depositpaid'];
                        $shipping_name = $_POST['shipping_name'];
                        $shipping_phnumber = $_POST['shipping_phnumber'];
                        $shipping_address = $_POST['shipping_address'];
                        $shipping_postcode = $_POST['shipping_postcode'];
                        $order_status = "new order";
                        $order_paymethod = $_POST['order_paymethod'];

                        $createOrderStmt->bindParam(':order_datentime', $order_datentime);
                        $createOrderStmt->bindParam(':cus_email', $cus_email);
                        $createOrderStmt->bindParam(':order_totalamount', $order_totalamount);
                        $createOrderStmt->bindParam(':order_depositpaid', $order_depositpaid);
                        $createOrderStmt->bindParam(':shipping_name', $shipping_name);
                        $createOrderStmt->bindParam(':shipping_phnumber', $shipping_phnumber);
                        $createOrderStmt->bindParam(':shipping_address', $shipping_address);
                        $createOrderStmt->bindParam(':shipping_postcode', $shipping_postcode);
                        $createOrderStmt->bindParam(':order_status', $order_status);
                        $createOrderStmt->bindParam(':order_paymethod', $order_paymethod);

                        if ($createOrderStmt->execute()) {
                            $lastID = $con->lastInsertId();
                            for ($i = 0; $i < count($_POST['product_id']); $i++) {
                                $product_id = htmlspecialchars(strip_tags($_POST['product_id'][$i]));
                                $getPriceQuery = "SELECT product_price FROM product WHERE product_id=:product_id";
                                $getPriceStmt = $con->prepare($getPriceQuery);
                                $getPriceStmt->bindParam(':product_id', $product_id);
                                $getPriceStmt->execute();
                                while ($getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $product_price = $getPriceRow['product_price'];
                                    $product_totalamount = $product_price;
                                }

                                $orderDetailQuery = "INSERT INTO order_detail SET order_id=:order_id, product_id=:product_id, product_totalamount=:product_totalamount, product_selected=:product_selected";
                                $orderDetailStmt = $con->prepare($orderDetailQuery);
                                $product_selected = 1;
                                $orderDetailStmt->bindParam(':order_id', $lastID);
                                $orderDetailStmt->bindParam(':product_id', $product_id);
                                $orderDetailStmt->bindParam(':product_totalamount', $product_totalamount);
                                $orderDetailStmt->bindParam(':product_selected', $product_selected);
                                if ($orderDetailStmt->execute()) {
                                    $deleteCheckoutDetailQuery = "DELETE FROM checkout_detail  WHERE checkout_id = :checkout_id";
                                    $deleteCheckoutDetailStmt = $con->prepare($deleteCheckoutDetailQuery);
                                    $deleteCheckoutDetailStmt->bindParam(':checkout_id', $checkout_id);
                                    if ($deleteCheckoutDetailStmt->execute()){
                                        $deleteCheckoutQuery = "DELETE FROM checkout WHERE checkout_id = :checkout_id";
                                        $deleteCheckoutStmt = $con->prepare($deleteCheckoutQuery);
                                        $deleteCheckoutStmt->bindParam(':checkout_id', $checkout_id);
                                        if($deleteCheckoutStmt->execute()){
                                            echo "<script>window.location.href='index.php?cus_email='+ '$cus_email' + '&action=ordered';</script>";
                                        }
                                        else{
                                            echo "<script>window.location.href='index.php?cus_email='+ '$cus_email' + '&action=noOrdered';</script>";
                                        }
                                    }
                                }
                            }
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table class='table table-hover table-responsive table-bordered'>
                    <thead>
                        <tr>
                            <h2>Billing Details</h2>
                        </tr>
                        <tr class="border-start border-end border-top border-0"> 
                            <th class="col-4 d-flex align-self-center border-0 px-3">Name</th>
                            <td class="border-0"><input type='text' name='shipping_name' id="shipping_name" value="<?php echo (isset($_POST['shipping_name'])) ? $_POST['shipping_name'] : ''; ?>" class='form-control'/></td>
                        </tr>
                        <tr class="border-start border-end border-0">
                            <th class="d-flex align-self-center border-0 px-3">Phone Number</th>
                            <td class="border-0"><input type="tel" name="shipping_phnumber" id="shipping_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['shipping_phnumber'])) ? $_POST['shipping_phnumber'] : ''; ?>" class='form-control' ></td>
                        </tr>
                        <tr class="border-start border-end border-0">
                            <th class="d-flex align-self-center border-0 px-3">Address</th>
                            <td class="border-0"><textarea type='text' name='shipping_address' id="shipping_address" class='form-control' rows="3"><?php echo (isset($_POST['shipping_address'])) ? $_POST['shipping_address'] : ''; ?></textarea></td>
                        </tr>
                        <tr class="border-start border-end border-bottom border-0">
                            <th class="d-flex align-self-center border-0 px-3">Postcode</th>
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
                            <th class='col-3 col-md-2 text-center'>Price per piece (RM)</th>
                            <th class='col-3 col-md-2 text-center'>Total Price (RM)</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $getCheckoutQuery = "SELECT * FROM checkout WHERE cus_email=:cus_email";
                            $getCheckoutStmt = $con->prepare($getCheckoutQuery);
                            $getCheckoutStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                            $getCheckoutStmt->execute();
                            $getCheckoutRow = $getCheckoutStmt->fetch(PDO::FETCH_ASSOC);
                            $checkout_id = $getCheckoutRow['checkout_id'];
                            $checkout_totalamount = sprintf('%.2f', $getCheckoutRow['checkout_totalamount']);

                            $getProductQuery = "SELECT cd.product_id, p.product_image, p.product_name, p.product_price, cd.product_totalamount
                                        FROM checkout_detail cd
                                        INNER JOIN product p 
                                        ON cd.product_id = p.product_id
                                        WHERE checkout_id = :checkout_id";
                            $getProductStmt = $con->prepare($getProductQuery);
                            $getProductStmt->bindParam(":checkout_id", $checkout_id);
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
                                        <div class="d-flex flex-column flex-md-row">
                                            <div class="d-flex justify-content-center col-2">
                                                <a <?php echo"href='product_detail.php?product_id={$product_id}'";?> ><img src="<?php echo htmlspecialchars($product_image, ENT_QUOTES); ?>" class='productImage d-flex justify-content-center rounded'></a>
                                            </div>
                                            <div class='mx-3 col-lg-7'>
                                                <a <?php echo"href='product_detail.php?product_id={$product_id}'";?> class='word text-center text-decoration-none'><?php echo htmlspecialchars($product_name, ENT_QUOTES); ?></a>
                                                <input type='hidden' name='product_id[]' id='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES);?>" class='cartProduct form-control text-center border border-0' readonly/>
                                            </div>
                                            <div class='d-lg-flex  flex-shrink-1 flex-column'>
                                                <?php echo"<a href='#' onclick='delete_product(&#39;$product_id&#39;, &#39;$checkout_id&#39;, &#39;$cus_email&#39;);' id='delete' class='checkoutBtn btn m-1 m-lg-2'>Delete</a>"; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><input name='product_price' id='product_price' value="<?php echo htmlspecialchars($product_price, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' readonly/></td>
                                    <td><input name='product_totalamount' id='product_totalamount' value="<?php echo htmlspecialchars($product_totalamount, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' aria-readonly="true" readonly/></td>
                                </tr>
                            <?php }?>
                            <tr>
                            <td colspan='2' class='text-end'>The total amount is: RM</td>
                            <td class='text-end'><input name='checkout_totalamount' id='checkout_totalamount' value="<?php echo htmlspecialchars($checkout_totalamount, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' readonly/></td>
                            </tr>
                        </tfoot>
                </table>
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <h2 class="mt-4">Deposit and Payment Method:</h2>
                    </tr>
                    <thead>
                        <tr class="border-start border-end border-top border-0"> 
                            <th class="d-flex align-self-center border-0 px-3">Deposit you pay now: RM</th>
                            <td class="border-0"><input type='text' name='order_depositpaid' id="order_depositpaid" value="<?php echo (isset($_POST['order_depositpaid'])) ? $_POST['order_depositpaid'] : ''; ?>" class='form-control'/></td>
                        </tr>
                        <tr class="border-start border-end border-bottom border-0">
                            <th class="d-flex align-self-center border-0 px-3">Payment method</th>
                            <td class="border-0">
                                <div class="form-check">
                                    <label>
                                        <input type="radio" name="order_paymethod" value="online" 
                                        <?php
                                        if(isset($_POST['order_paymethod'])){
                                            echo $_POST['order_paymethod'] == "online" ? 'checked' : '';
                                        }
                                        ?>>
                                        Online Transfer
                                        <span class="select"></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label>
                                        <input type="radio" name="order_paymethod" value="ewallet" 
                                        <?php
                                        if(isset($_POST['order_paymethod'])){
                                            echo $_POST['order_paymethod'] == "ewallet" ? 'checked' : '';
                                        }
                                        ?>>
                                        E-wallet (Touch n Go e-wallet, Boost, Grabpay, ShopeePay)
                                        <span class="select"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </thead>
                </table>
                <div class='button d-grid m-3 d-flex justify-content-center'>
                    <button type='submit' class='actionBtn btn btn-lg mt-5'>Checkout</button>
                </div>
        </form>
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
<script type='text/javascript'>
    function delete_product(product_id, checkout_id, cus_email) {
        if (confirm('Do you want to delete this product from yout checkout list?')) {
            window.location = 'checkout_delete.php?product_id=' + product_id + "&checkout_id=" + checkout_id + "&cus_email=" + cus_email;
        }
    }
</script>

</html>