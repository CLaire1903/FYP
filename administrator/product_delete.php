<?php
include 'C:\xampp\htdocs\fyp\config/dbase.php';
try {     
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] :  die('ERROR: Record ID not found.');

    $checkODQuery = "SELECT * FROM order_detail WHERE product_id = ?";
    $checkODStmt = $con->prepare($checkODQuery);
    $checkODStmt->bindParam(1, $product_id);
    $checkODStmt->execute();
    $ODnum = $checkODStmt->rowCount();

    $checkCDQuery = "SELECT * FROM checkout_detail WHERE product_id = ?";
    $checkCDStmt = $con->prepare($checkCDQuery);
    $checkCDStmt->bindParam(1, $product_id);
    $checkCDStmt->execute();
    $CDnum = $checkCDStmt->rowCount();

    $checkCartQuery = "SELECT * FROM cart WHERE product_id = ?";
    $checkCartStmt = $con->prepare($checkCartQuery);
    $checkCartStmt->bindParam(1, $product_id);
    $checkCartStmt->execute();
    $cartNum = $checkCartStmt->rowCount();

    if($ODnum != 0){
        header('Location: product_list.php?action=productInStock');
    } else if ($CDnum != 0){
        header('Location: product_list.php?action=productInStock');
    }else if ($cartNum != 0){
        header('Location: product_list.php?action=productInStock');
    }
    else {
        $getImageNameQuery = "SELECT product_image FROM product WHERE product_id = :product_id";
        $getImageNameStmt = $con->prepare($getImageNameQuery);
        $getImageNameStmt->bindParam(":product_id", $product_id);
        $getImageNameStmt->execute();
        $getImageNameRow = $getImageNameStmt->fetch(PDO::FETCH_ASSOC);
        $product_image = $getImageNameRow["product_image"];


        $deleteProductQuery = "DELETE FROM product WHERE product_id = ?";
        $deleteProductStmt = $con->prepare($deleteProductQuery);
        $deleteProductStmt->bindParam(1, $product_id);
        if (unlink($product_image)){
            if($deleteProductStmt->execute()){
                header('Location: product_list.php?action=deleted');
            }else{
                die('Unable to delete record.');
            }
        }
    }
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
