<?php
include '../config/dbase.php';
try {     
    $designer_email = isset($_GET['designer_email']) ? $_GET['designer_email'] :  die('ERROR: Record ID not found.');

    $checkQuery = "SELECT * FROM product WHERE designer_email = ?";
    $checkStmt = $con->prepare($checkQuery);
    $checkStmt->bindParam(1, $designer_email);
    $checkStmt->execute();
    $num = $checkStmt->rowCount();

    $checkCustomizedQuery = "SELECT * FROM custoized WHERE designer_email = ?";
    $checkCustomizedStmt = $con->prepare($checkCustomizedQuery);
    $checkCustomizedStmt->bindParam(1, $designer_email);
    $checkCustomizedStmt->execute();
    $customizedNum = $checkCustomizedStmt->rowCount();

    if($num != 0){
        header('Location: designer_list.php?action=deletedFail');
    } else if ($customizedNum != 0){
        header('Location: designer_list.php?action=deletedFail');
    }
    else {
        $deleteAwardQuery = "DELETE FROM award WHERE designer_email = ?";
        $deleteAwardStmt = $con->prepare($deleteAwardQuery);
        $deleteAwardStmt->bindParam(1, $designer_email);
        if($deleteAwardStmt->execute()) {
            $deleteDesignerQuery = "DELETE FROM designer WHERE designer_email = ?";
            $deleteDesignerStmt = $con->prepare($deleteDesignerQuery);
            $deleteDesignerStmt->bindParam(1, $designer_email);
            if($deleteDesignerStmt->execute()){
                header('Location: designer_list.php?action=deleted');
            }
        }
}
} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
