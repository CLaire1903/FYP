<?php
session_start();
include 'config/dbase.php';
if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
    $errorMessage = "Please login for further proceed!";
}
    include 'config/dbase.php';

    $feedback_detail = isset($_GET['feedback_detail']) ? $_GET['feedback_detail'] :  die('ERROR: No feedback will be sent.');

try {
    $cus_email = $_SESSION['cus_email'];
    $sendFeedbackQuery = "INSERT INTO feedback SET cus_email=:cus_email, feedback_detail=:feedback_detail";
    $sendFeedbackStmt = $con->prepare($sendFeedbackQuery);
    $feedback_detail = $_POST['feedback_detail'];
    $sendFeedbackStmt->bindParam(':cus_email', $cus_email);
    $sendFeedbackStmt->bindParam(':feedback_detail', $feedback_detail);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Thank you for your feedback!</div>";
        } else {
            throw new Exception("Unable to save record.");
        }
    } catch (PDOException $exception) {
        echo "<div class='alert alert-danger'>" . $exception->getMessage() . "</div>";
    }
?>