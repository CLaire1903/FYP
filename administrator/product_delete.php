<?php
include 'C:\xampp\htdocs\fyp\config/dbase.php';
try {     
    $product_id = isset($_GET['product_id']) ? $_GET['product_id'] :  die('ERROR: Record ID not found.');

    //check whether the product involved in any order
    $checkQuery = "SELECT * FROM order_detail WHERE product_id = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $product_id);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();
    if($num != 0){
        //selected product cannot be deleted because involved in at least a order
        header('Location: product_list.php?action=productInStock');
    }else {

    $getImageNameQuery = "SELECT product_image FROM product WHERE product_id = :product_id";
    $getImageNameStmt = $con->prepare($getImageNameQuery);
    $getImageNameStmt->bindParam(":product_id", $product_id);
    $getImageNameStmt->execute();
    $getImageNameRow = $getImageNameStmt->fetch(PDO::FETCH_ASSOC);
    $product_image = $getImageNameRow["product_image"];


    $deleteProductQuery = "DELETE FROM product WHERE product_id = ?";
    $deleteProductStmt = $con->prepare($deleteProductQuery);
    $deleteProductStmt->bindParam(1, $product_id);
    unlink($product_image);
    if($deleteProductStmt->execute()){
        //selected product is deleted
        header('Location: product_list.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
