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
        .form-control:disabled {
            background-color: white;
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
                    for ($i = 0; $i < count($_POST['product_id']); $i++) {
                        $checkOutQuery = "INSERT INTO checkout SET cus_email=:cus_email, product_id=:product_id, cart_quantity=:cart_quantity";
                        $checkOutStmt = $con->prepare($checkOutQuery);
                        $cus_email = $_SESSION["cus_email"];
                        $product_id = htmlspecialchars(strip_tags($_POST['product_id'][$i]));
                        $cart_quantity = htmlspecialchars(strip_tags($_POST['cart_quantity'][$i]));
                        $checkOutStmt->bindParam(':cus_email', $cus_email);
                        $checkOutStmt->bindParam(':product_id', $product_id);
                        $checkOutStmt->bindParam(':cart_quantity', $cart_quantity);
                        if ($checkOutStmt->execute()) {
                            $deleteCartQuery = "DELETE FROM cart WHERE cus_email = :cus_email";
                            $deleteCartStmt = $con->prepare($deleteCartQuery);
                            $deleteCartStmt->bindParam(':cus_email', $_SESSION["cus_email"]);
                            $deleteCartStmt->execute();
                            echo "<script>window.location.href='checkout.php?cus_email='+ '$cus_email';</script>";
                        }
                    }
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
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
                                        echo "<th class='border-start-0 border-end-0'>Quantity</th>";
                                        echo "<th class='border-start-0'></th>";
                                    echo "</tr>";
                                echo "</thead>";

                            $cartQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price, c.cart_quantity
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
                                $cart_quantity = $cartRow['cart_quantity'];
                                $product_id = htmlspecialchars($product_id, ENT_QUOTES);
                                $product_name = htmlspecialchars($product_name, ENT_QUOTES);
                                $product_price = htmlspecialchars($product_price, ENT_QUOTES);
                                $cart_quantity = htmlspecialchars($cart_quantity, ENT_QUOTES);?>

                                <tfoot>
                                    <tr>
                                        <td class="col-3 border-end-0">
                                            <div class="d-flex justify-content-center">
                                                <a href='product_detail.php?product_id={$product_id}'><img src="<?php echo htmlspecialchars($product_image, ENT_QUOTES); ?>" class='productImage d-flex justify-content-center rounded'></a>
                                            </div>
                                        </td>
                                        <td class="col-4 border-start-0 border-end-0"><?php echo htmlspecialchars($product_name, ENT_QUOTES); ?> <br>
                                        <input name='product_id[]' id='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES); ?>" class='cartProduct form-control text-center border border-0'/></td>
                                        <td class="col-2 border-start-0 border-end-0"> <input name='product_price' id='product_price' value="<?php echo htmlspecialchars($product_price, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0'/></td>
                                        <td class="col-1 border-start-0 border-end-0"><input name='cart_quantity[]' id='cart_quantity' value="<?php echo htmlspecialchars($cart_quantity, ENT_QUOTES); ?>" class='col-1 form-control text-center border border-0'/></td>
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