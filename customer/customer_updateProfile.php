<?php
session_start();
if (!isset($_SESSION["cus_email"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/profile.css" rel="stylesheet">

    <style>
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include '../config/dbase.php';
        include '../alertIcon.php';
        include 'navigationBar.php';
        ?>
        <div class="page-header mx-5 mt-5">
            <h1>Update Profile</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>
        <?php
        $cus_email = isset($_GET['cus_email']) ? $_GET['cus_email'] : die('ERROR: Customer record not found.');?>

        <div class="updateProfileDetail d-flex flex-column justify-content-center mx-5">
            <?php
        try {
            $getCustomerQuery = "SELECT * FROM customer WHERE cus_email = :cus_email ";
            $getCustomerStmt = $con->prepare($getCustomerQuery);
            $getCustomerStmt->bindParam(":cus_email", $cus_email);
            $getCustomerStmt->execute();
            $getCustomerRow = $getCustomerStmt->fetch(PDO::FETCH_ASSOC);
            $cus_email = $getCustomerRow['cus_email'];
            $cus_fname = $getCustomerRow['cus_fname'];
            $cus_lname= $getCustomerRow['cus_lname'];
            $cus_address= $getCustomerRow['cus_address'];
            $cus_phnumber = $getCustomerRow['cus_phnumber'];
            $cus_gender = $getCustomerRow['cus_gender'];
            $cus_bday = $getCustomerRow['cus_bday'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        if ($_POST) {
            try {
                if (empty($_POST['cus_fname']) || empty($_POST['cus_lname']) || empty($_POST['cus_address']) || empty($_POST['cus_phnumber']) || empty($_POST['cus_gender']) || empty($_POST['cus_bday'])) {
                    throw new Exception("Please make sure all fields are not empty!");
                }

                $updateCustomerQuery = "UPDATE customer SET cus_fname=:cus_fname, cus_lname=:cus_lname, cus_address=:cus_address, cus_phnumber=:cus_phnumber , cus_gender=:cus_gender, cus_bday=:cus_bday WHERE cus_email = :cus_email";
                $updateCustomerStmt = $con->prepare($updateCustomerQuery);
                $cus_fname = htmlspecialchars(strip_tags(ucfirst($_POST['cus_fname'])));
                $cus_lname = htmlspecialchars(strip_tags(ucfirst($_POST['cus_lname'])));
                $cus_address = htmlspecialchars(strip_tags($_POST['cus_address']));
                $cus_phnumber = htmlspecialchars(strip_tags($_POST['cus_phnumber']));
                $cus_gender = htmlspecialchars(strip_tags($_POST['cus_gender']));
                $cus_bday = htmlspecialchars(strip_tags($_POST['cus_bday']));

                $updateCustomerStmt->bindParam(':cus_email', $cus_email);
                $updateCustomerStmt->bindParam(':cus_fname', $cus_fname);
                $updateCustomerStmt->bindParam(':cus_lname', $cus_lname);
                $updateCustomerStmt->bindParam(':cus_address', $cus_address);
                $updateCustomerStmt->bindParam(':cus_phnumber', $cus_phnumber);
                $updateCustomerStmt->bindParam(':cus_gender', $cus_gender);
                $updateCustomerStmt->bindParam(':cus_bday', $cus_bday);
                if ($updateCustomerStmt->execute()) {
                    echo "<script>window.location.href='customer_profile.php?cus_email='+ '$cus_email' + '&action=profileUpdated';</script>";
                } else {
                    echo "<script>window.location.href='customer_profile.php?cus_email='+ '$cus_email' + '&action=profileUpdateFail';</script>";
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?cus_email={$cus_email}"); ?>" method="post" enctype="multipart/form-data">
            <table class='profileDetailTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-5">Email</td>
                    <td><?php echo htmlspecialchars($cus_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_fname' id="cus_fname" value="<?php echo htmlspecialchars($cus_fname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_lname' id="cus_lname" value="<?php echo htmlspecialchars($cus_lname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Address <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_address' id="cus_address" value="<?php echo htmlspecialchars($cus_address, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Phone Number <span class="text-danger">*</span></td>
                    <td><input type="tel" name="cus_phnumber" id="cus_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($cus_phnumber, ENT_QUOTES);  ?>" class='form-control' ></td>
                </tr>
                <tr>
                    <td>Gender <span class="text-danger">*</span></td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="cus_gender" class="cus_gender" value="male" <?php echo htmlspecialchars($cus_gender == 'male') ? 'checked' : '' ?>>
                                Male
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="cus_gender" class="cus_gender" value="female" <?php echo htmlspecialchars($cus_gender == 'female') ? 'checked' : '' ?>>
                                Female
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth <span class="text-danger">*</span></td>
                    <td><input type='date' name='cus_bday' id="cus_bday" value="<?php echo htmlspecialchars($cus_bday, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mb-3 mx-2' />
                <a href='customer_profile.php?cus_email={$cus_email}' class='actionBtn btn mb-3 mx-2'>Back</a>
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