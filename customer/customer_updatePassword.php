<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/profile.css" rel="stylesheet">

    <style>
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'navigationBar.php';
        include 'C:\xampp\htdocs\fyp\alertIcon.php';
        ?>
        <div class="page-header mx-5 mt-5">
            <h1 class="text-center">Update Password</h1>
        </div>
        <?php
        $cus_email = isset($_GET['cus_email']) ? $_GET['cus_email'] : die('ERROR: Customer record not found.');?>

        <div class="updateProfileDetail d-flex flex-column justify-content-center mx-5">
            <?php
        try {
            //display the customer record from the database
            $checkCurrentPasswordQuery = "SELECT * FROM customer WHERE cus_email = :cus_email ";
            $checkCurrentPasswordStmt = $con->prepare($checkCurrentPasswordQuery);
            $checkCurrentPasswordStmt->bindParam(":cus_email", $cus_email);
            $checkCurrentPasswordStmt->execute();
            $checkCurrentPasswordRow = $checkCurrentPasswordStmt->fetch(PDO::FETCH_ASSOC);
            $cus_currentpword = $checkCurrentPasswordRow['cus_pword'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {
            try {
                if (empty($_POST['cus_newpword']) || empty($_POST['cus_newcpword']) || empty($_POST['cus_currentpword'])) {
                    throw new Exception("Please make sure all fields are not empty!");
                }
                if ($_POST['cus_currentpword'] != $cus_currentpword) {
                    throw new Exception("Wrong current password.");
                }
                if ($_POST['cus_newpword'] != $_POST['cus_newcpword']) {
                    throw new Exception("Password and confirm password are not the same.");
                }
                if (strlen($_POST['cus_newpword']) < 8 || !preg_match("@[0-9]@", $_POST['cus_newpword']) || !preg_match("@[a-z]@", $_POST['cus_newpword']) || !preg_match("@[A-Z]@", $_POST['cus_newpword']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["cus_newpword"])) {
                    throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a <strong>CAPITAL</strong> letter");
                }

                //update the customer detail into the database
                $updatePasswordQuery = "UPDATE customer SET cus_pword=:cus_pword, cus_cpword=:cus_cpword WHERE cus_email = :cus_email";
                $updatePasswordStmt = $con->prepare($updatePasswordQuery);
                $cus_pword = $_POST['cus_newpword'];
                $cus_cpword = $_POST['cus_newcpword'];

                $updatePasswordStmt->bindParam(':cus_email', $cus_email);
                $updatePasswordStmt->bindParam(':cus_pword', $cus_pword);
                $updatePasswordStmt->bindParam(':cus_cpword', $cus_cpword);

                if ($updatePasswordStmt->execute()) {
                    echo "<script>window.location.href='customer_profile.php?cus_email='+ '$cus_email' + '&action=passwordUpdated';</script>";
                } else {
                    echo "<script>window.location.href='customer_profile.php?cus_email='+ '$cus_email' + '&action=passwordUpdateFail';</script>";
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            } catch (Exception $exception) {
                echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                        " . $exception->getMessage() . "
                        </div>
                    </div>";
            }
        } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?cus_email={$cus_email}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='updatePasswordTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Current Password</td>
                    <td><input type='text' name='cus_currentpword' id="cus_currrentpword" value="<?php echo (isset($_POST['cus_currentpword'])) ? $_POST['cus_currentpword'] : ''; ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='text' name='cus_newpword' id="cus_newpword" value="<?php echo (isset($_POST['cus_newpword'])) ? $_POST['cus_newpword'] : ''; ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='text' name='cus_newcpword' id="cus_newcpword" value="<?php echo (isset($_POST['cus_newcpword'])) ? $_POST['cus_newcpword'] : ''; ?>" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mb-3 mx-2' />
                <a href='customer_profile.php?cus_username={$cus_username}' class='actionBtn btn mb-3 mx-2'>Back</a>
            </div>
        </form>
    </div>
            
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>