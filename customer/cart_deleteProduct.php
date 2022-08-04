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

        $deleteProductQuery = "DELETE FROM cart WHERE product_id=:product_id";
        $deleteProductStmt = $con->prepare($deleteProductQuery);
        $deleteProductStmt->bindParam(':product_id', $product_id);
        
        if($deleteProductStmt->execute()){
            echo "<script>window.location.href='cart.php?product_name=' + '$product_name' + '&action=productDeleted';</script>";
        }
        } catch (PDOException $exception) {
            echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
    }   
?>