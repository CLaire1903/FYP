<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">

    <style>
        .productImage{
            width: 250px;
            height: 250px;
        }
        .deleteBtn {
            background-color: #f7A8AE;
            width: 90px;
            height: 30px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .deleteBtn:hover {
            background-color: #f7A8AE;
            font-weight: bold;
        }
        .checkoutAlert {
            background-color: #ffe1e1
        }
        .checkoutIns{
            color: #ff7474;
            text-decoration: none;
        }
        .checkoutIns:hover{
            color: black;
            text-decoration: underline;
            text-transform: 0.3s;
        }
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

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'productDeleted') {
                $product_name = isset($_GET['product_name']) ? $_GET['product_name'] :  die('ERROR: Product name not found.');
                echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            $product_name deleted from cart.
                        </div>
                    </div>";
            }
        ?>
        <?php
            if ($_POST) {
                try {
                    $con->beginTransaction();
                    $checkoutQuery = "INSERT INTO checkout SET cus_email=:cus_email, checkout_totalamount=:checkout_totalamount";
                    $checkoutStmt = $con->prepare($checkoutQuery);
                    $cus_email = $_SESSION["cus_email"];
                    $checkout_totalamount = 0;

                    for ($i = 0; $i < count($_POST['product_id']); $i++) {
                        $product_id = $_POST['product_id'][$i];
                        $selectPriceQuery = "SELECT product_price FROM product WHERE product_id=:product_id";
                        $selectPriceStmt = $con->prepare($selectPriceQuery);
                        $selectPriceStmt->bindParam(':product_id', $product_id);
                        $selectPriceStmt->execute();
                        while ($selectPriceRow = $selectPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                            $product_price = $selectPriceRow['product_price'];
                            $product_totalamount = $product_price;
                            $checkout_totalamount += $product_totalamount;
                        }
                    }
                    $checkoutStmt->bindParam(':cus_email', $cus_email);
                    $checkoutStmt->bindParam(':checkout_totalamount', $checkout_totalamount);

                    if ($checkoutStmt->execute()) {
                        $lastID = $con->lastInsertId();
                        for ($i = 0; $i < count($_POST['product_id']); $i++) {
                            $product_id = $_POST['product_id'][$i];
                            $getPriceQuery = "SELECT product_price FROM product WHERE product_id=:product_id";
                            $getPriceStmt = $con->prepare($getPriceQuery);
                            $getPriceStmt->bindParam(':product_id', $product_id);
                            $getPriceStmt->execute();
                            while ($getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC)) {
                                $product_price = $getPriceRow['product_price'];
                                $product_totalamount = $product_price;
                            }
                            $checkOutQuery = "INSERT INTO checkout_detail SET checkout_id=:checkout_id, product_id=:product_id, product_totalamount=:product_totalamount";
                            $checkOutStmt = $con->prepare($checkOutQuery);
                            $product_id = htmlspecialchars(strip_tags($_POST['product_id'][$i]));
                            $checkOutStmt->bindParam(':checkout_id', $lastID);
                            $checkOutStmt->bindParam(':product_id', $product_id);
                            $checkOutStmt->bindParam(':product_totalamount', $product_totalamount);
                            if ($checkOutStmt->execute()) {
                                $deleteCartQuery = "DELETE FROM cart WHERE cus_email = :cus_email";
                                $deleteCartStmt = $con->prepare($deleteCartQuery);
                                $cus_email = $_SESSION["cus_email"];
                                $deleteCartStmt->bindParam(':cus_email', $cus_email);
                                $deleteCartStmt->execute();
                                echo "<script>window.location.href='checkout.php?cus_email='+ '$cus_email';</script>";
                            }
                        }
                    }
                    $con->commit();
                } catch (PDOException $exception) {
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                        " . $exception->getMessage() . "
                        </div>
                    </div>";
                }
            }
        ?>
        <div class="mt-5 mx-5">
            <h1 class="text-center">Shopping Cart</h1>
            <div class="mt-5">
                <?php 
                    $checkCheckoutQuery = "SELECT * FROM checkout WHERE cus_email = :cus_email";
                    $checkCheckoutStmt = $con->prepare($checkCheckoutQuery);
                    $checkCheckoutStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                    $checkCheckoutStmt->execute();
                    $checkCheckoutRow = $checkCheckoutStmt->fetch(PDO::FETCH_ASSOC);
                    if ($checkCheckoutRow > 0) {
                        $cusEmail = $_SESSION["cus_email"];
                        echo "<div class='checkoutAlert alert d-flex align-items-center' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                        You still have order haven't checkout. Click <a href='checkout.php?cus_email=$cusEmail' id='registerAcc' class='checkoutIns fw-bold'>HERE</a> to proceed.
                        </div>
                    </div>";
                    }
                ?>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php
                        $checkCartQuery = "SELECT * FROM cart WHERE cus_email = :cus_email";
                        $checkCartStmt = $con->prepare($checkCartQuery);
                        $checkCartStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                        $checkCartStmt->execute();
                        $checkCartRow = $checkCartStmt->fetch(PDO::FETCH_ASSOC);
                        if ($checkCartRow == 0) {
                            echo "<h2 class='text-center m-5'>Your cart is empty.</h2>";
                        } else {
                            echo "<table class='table table-hover table-responsive table-bordered text-center mt-5'>";
                                echo "<thead>";
                                    echo "<tr class='tableHeader'>";
                                        echo "<th class=' border-end-0'>Product Image</th>";
                                        echo "<th class='border-start-0 border-end-0'>Product</th>";
                                        echo "<th class='border-start-0 border-end-0'>Price (RM)</th>";
                                        echo "<th class='border-start-0'></th>";
                                    echo "</tr>";
                                echo "</thead>";

                            $cartQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price
                                    FROM cart c
                                    INNER JOIN product p 
                                    ON c.product_id = p.product_id
                                    WHERE cus_email = :cus_email";
                            $cartStmt = $con->prepare($cartQuery);
                            $cartStmt->bindParam(":cus_email", $_SESSION["cus_email"]);
                            $cartStmt->execute();
                            while ($cartRow = $cartStmt->fetch(PDO::FETCH_ASSOC)) {
                                $product_image = $cartRow['product_image'];
                                $product_id = $cartRow['product_id'];
                                $product_name = ucwords($cartRow['product_name']);
                                $product_price = sprintf('%.2f',$cartRow['product_price']);
                                ?>

                                <tfoot>
                                    <tr>
                                        <td class="col-3 border-end-0">
                                            <div class="d-flex justify-content-center">
                                                <a <?php echo"href='product_detail.php?product_id={$product_id}'";?>><img src="<?php echo htmlspecialchars($product_image, ENT_QUOTES); ?>" class='productImage d-flex justify-content-center rounded'></a>
                                            </div>
                                        </td>
                                        <td class="col-4 border-start-0 border-end-0"><?php echo htmlspecialchars($product_name, ENT_QUOTES); ?> <br>
                                        <input name='product_id[]' id='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES); ?>"  class='cartProduct form-control text-center border border-0 bg.white' readonly /></td>
                                        <td class="col-2 border-start-0 border-end-0"> <input name='product_price' id='product_price' value="<?php echo htmlspecialchars($product_price, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0' readonly/></td>
                                        <td class="col-1 border-start-0">
                                            <?php 
                                            echo "<a href='#' onclick='deleteProduct({$product_id});' id='delete' class='deleteBtn btn '>Delete</a>";
                                            ?>
                                    </tr>
                                </tfoot>
                    <?php   }   ?>
                </table>
                <div class='button d-grid m-3 d-flex justify-content-center'>
                    <button type='submit' class='actionBtn btn btn-lg'>Checkout</button>
                </div>
            <?php   }   ?>
            </form>
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<script type='text/javascript'>
    function deleteProduct(product_id) {
        if (confirm('Delete product from cart?')) {
            window.location = "cart_deleteProduct.php?product_id=" + product_id;
        }
    }
</script>

</body>

</html>