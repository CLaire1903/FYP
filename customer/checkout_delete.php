<?php
include '../config/dbase.php';
try {     
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] :  die('ERROR: Product ID not found.');
    $checkout_id = isset($_GET['checkout_id']) ? $_GET['checkout_id'] :  die('ERROR: Checkout ID not found.');
    $cus_email = isset($_GET['cus_email']) ? $_GET['cus_email'] :  die('ERROR: Customer not found.');

    $deleteProductQuery = "DELETE FROM checkout_detail WHERE product_id = ?";
    $deleteProductStmt = $con->prepare($deleteProductQuery);
    $deleteProductStmt->bindParam(1, $product_id);
    if($deleteProductStmt->execute()){
        $checkProductQuery = "SELECT * FROM checkout_detail WHERE checkout_id=?";
        $checkProductStmt = $con->prepare($checkProductQuery); 
        $checkProductStmt->bindParam(1, $checkout_id);
        $checkProductStmt->execute();
        $checkProductRow = $checkProductStmt->fetch(PDO::FETCH_ASSOC);
        if ($checkProductRow > 0) {
            $getTotalAmountQuery = "SELECT * FROM checkout WHERE checkout_id=:checkout_id";
            $getTotalAmountStmt = $con->prepare($getTotalAmountQuery); 
            $getTotalAmountStmt->bindParam(":checkout_id", $checkout_id);
            $getTotalAmountStmt->execute();
            $getTotalAmountRow = $getTotalAmountStmt->fetch(PDO::FETCH_ASSOC);
            $checkout_totalamount = $getTotalAmountRow['checkout_totalamount'];

            $getPriceQuery = "SELECT * FROM product WHERE product_id=:product_id";
            $getPriceStmt = $con->prepare($getPriceQuery); 
            $getPriceStmt->bindParam(":product_id", $product_id);
            $getPriceStmt->execute();
            $getPriceRow = $getPriceStmt->fetch(PDO::FETCH_ASSOC);
            $product_price = $getPriceRow['product_price'];

            $totalamount == 0;
            $totalamount = $checkout_totalamount - $product_price;

            $updateTotalAmountQuery = "UPDATE checkout SET checkout_totalamount=:checkout_totalamount WHERE checkout_id=:checkout_id";
            $updateTotalAmountStmt = $con->prepare($updateTotalAmountQuery);
            $updateTotalAmountStmt->bindParam(':checkout_totalamount', $totalamount);
            $updateTotalAmountStmt->bindParam(':checkout_id', $checkout_id);
            if($updateTotalAmountStmt->execute()){
                header("Location: checkout.php?cus_email=$cus_email");
            }

        }
        else {
            $deleteCheckoutQuery = "DELETE FROM checkout WHERE checkout_id = ?";
            $deleteCheckoutStmt = $con->prepare($deleteCheckoutQuery);
            $deleteCheckoutStmt->bindParam(1, $checkout_id);
            if($deleteCheckoutStmt->execute()){
                header("Location: cart.php?cus_email=$cus_email");
            }
        }
        
    }else{
        die('Unable to delete record.');
    }
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
