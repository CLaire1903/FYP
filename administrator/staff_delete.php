<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<?php
include 'C:\xampp\htdocs\fyp\config/dbase.php';
try {     
    $admin_email = isset($_GET['admin_email']) ? $_GET['admin_email'] :  die('ERROR: Record ID not found.');

    $checkPositionQuery = "SELECT admin_position FROM admin WHERE admin_email=:admin_email";
    $checkPositionStmt = $con->prepare($checkPositionQuery);
    $checkPositionStmt->bindParam(":admin_email", $admin_email);
    $checkPositionStmt->execute();
    $getPositionRow = $checkPositionStmt->fetch(PDO::FETCH_ASSOC);
    $admin_position = $getPositionRow["admin_position"];
    if ($admin_position == "director"){
        header('Location: staff_list.php?action=director');
    }


    if($admin_email == $_SESSION["admin_email"]){
        header('Location: staff_list.php?action=deletedOwn');
    } else {
        $deleteStaffQuery = "DELETE FROM admin WHERE admin_email = ?";
        $deleteStaffStmt = $con->prepare($deleteStaffQuery);
        $deleteStaffStmt->bindParam(1, $admin_email);
        if($deleteProductStmt->execute()){
            //selected product is deleted
            header('Location: staff_list.php?action=deleted');
        }else{
            header('Location: staff_list.php?action=deletedFail');
        }
    }

    

} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
