<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
    include 'C:\xampp\htdocs\fyp\config/dbase.php';

    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] :  die('ERROR: Product ID not found.');

    try{
        $getProductNameQuery = "SELECT product_name FROM product WHERE product_id=:product_id";
        $getProductNameStmt = $con->prepare($getProductNameQuery);
        $getProductNameStmt->bindParam(':product_id', $product_id);
        $getProductNameStmt->execute();
        $getProductNameRow = $getProductNameStmt->fetch(PDO::FETCH_ASSOC);
        $product_name = $getProductNameRow['product_name'];

        $checkCartQuery = "SELECT product_id, cus_email FROM cart WHERE product_id=:product_id AND cus_email=:cus_email";
        $checkCartStmt = $con->prepare($checkCartQuery);
        $cus_email = $_SESSION['cus_email'];
        $checkCartStmt->bindParam(':product_id', $product_id);
        $checkCartStmt->bindParam(":cus_email", $cus_email);
        $checkCartStmt->execute();
        $productExist = $checkCartStmt->rowCount();
        
        if($productExist == 1){
            echo "<script>window.location.href='product_detail.php?product_id='+ $product_id + '&product_name=' + '$product_name' + '&action=productExist';</script>";
        }
        else {
            $addToCartQuery = "INSERT INTO cart SET product_id=:product_id, cus_email=:cus_email";
            $addToCartStmt = $con->prepare($addToCartQuery);
            $cus_email = $_SESSION["cus_email"];
            $addToCartStmt->bindParam(':product_id', $product_id);
            $addToCartStmt->bindParam(":cus_email", $cus_email);
            if ($addToCartStmt->execute()) {
                echo "<script>window.location.href='product_detail.php?product_id='+ $product_id + '&product_name=' + '$product_name' + '&action=productAdded';</script>";
                } else {
                    throw new Exception("Product unable to save into cart.");
            }
        }
        } catch (PDOException $exception) {
            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
    }   
?>