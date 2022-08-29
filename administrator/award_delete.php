<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<?php
include '../config/dbase.php';
try {     
    $designer_email = isset($_GET['designer_email']) ? $_GET['designer_email'] :  die('ERROR: Designer not found.');
    $award_name = isset($_GET['award_name']) ? $_GET['award_name'] :  die('ERROR: Designer not found.');
    $award_year = isset($_GET['award_year']) ? $_GET['award_year'] :  die('ERROR: Designer not found.');
    
    $deleteAwardQuery = "DELETE FROM award WHERE designer_email=:designer_email AND award_name=:award_name AND award_year=:award_year";
    $deleteAwardStmt = $con->prepare($deleteAwardQuery);
    $deleteAwardStmt->bindParam(":designer_email", $designer_email);
    $deleteAwardStmt->bindParam(":award_name", $award_name);
    $deleteAwardStmt->bindParam(":award_year", $award_year);
    if($deleteAwardStmt->execute()){
        header("Location: designer_detail.php?designer_email=$designer_email&action=deleted");
    }else{
        header("Location: designer_detail.php?designer_email=$designer_email&action=deletedFail");
    }

} catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>