<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<?php
include 'C:\xampp\htdocs\fyp\config/dbase.php';
try {     
    $faq_id = isset($_GET['faq_id']) ? $_GET['faq_id'] :  die('ERROR: FAQ ID not found.');

        $deleteFAQQuery = "DELETE FROM admin WHERE admin_email = ?";
        $deleteFAQStmt = $con->prepare($deleteFAQQuery);
        $deleteFAQStmt->bindParam(1, $admin_email);
        if($deleteFAQStmt->execute()){
            //selected product is deleted
            header('Location: FAQ.php?action=deleted');
        }else{
            header('Location: FAQ.php?action=deletedFail');
        }

} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
