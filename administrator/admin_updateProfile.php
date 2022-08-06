<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Profile</title>
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
        $admin_email = isset($_GET['admin_email']) ? $_GET['admin_email'] : die('ERROR: Admin record not found.');
        ?>
        <div class="page-header mx-5 mt-5">
            <h1>Update Profile</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>

        <div class="updateProfileDetail d-flex flex-column justify-content-center mx-5">
            <?php
                try {
                    $getAdminQuery = "SELECT * FROM admin WHERE admin_email = :admin_email ";
                    $getAdminStmt = $con->prepare($getAdminQuery);
                    $getAdminStmt->bindParam(":admin_email", $admin_email);
                    $getAdminStmt->execute();
                    $getAdminRow = $getAdminStmt->fetch(PDO::FETCH_ASSOC);
                    $admin_email = $getAdminRow['admin_email'];
                    $admin_fname = $getAdminRow['admin_fname'];
                    $admin_lname= $getAdminRow['admin_lname'];
                    $admin_address= $getAdminRow['admin_address'];
                    $admin_phnumber = $getAdminRow['admin_phnumber'];
                    $admin_gender = $getAdminRow['admin_gender'];
                    $admin_bday = $getAdminRow['admin_bday'];
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                if ($_POST) {
                    try {
                        if (empty($_POST['admin_fname']) || empty($_POST['admin_lname']) || empty($_POST['admin_address']) || empty($_POST['admin_phnumber']) || empty($_POST['admin_gender']) || empty($_POST['admin_bday'])) {
                            throw new Exception("Please make sure all fields are not empty!");
                        }

                        //update the customer detail into the database
                        $updateAdminQuery = "UPDATE admin SET admin_fname=:admin_fname, admin_lname=:admin_lname, admin_address=:admin_address, admin_phnumber=:admin_phnumber , admin_gender=:admin_gender, admin_bday=:admin_bday WHERE admin_email = :admin_email";
                        $updateAdminStmt = $con->prepare($updateAdminQuery);
                        $admin_fname = htmlspecialchars(strip_tags(ucfirst($_POST['admin_fname'])));
                        $admin_lname = htmlspecialchars(strip_tags(ucfirst($_POST['admin_lname'])));
                        $admin_address = htmlspecialchars(strip_tags($_POST['admin_address']));
                        $admin_phnumber = htmlspecialchars(strip_tags($_POST['admin_phnumber']));
                        $admin_gender = htmlspecialchars(strip_tags($_POST['admin_gender']));
                        $admin_bday = htmlspecialchars(strip_tags($_POST['admin_bday']));

                        $updateAdminStmt->bindParam(':admin_email', $admin_email);
                        $updateAdminStmt->bindParam(':admin_fname', $admin_fname);
                        $updateAdminStmt->bindParam(':admin_lname', $admin_lname);
                        $updateAdminStmt->bindParam(':admin_address', $admin_address);
                        $updateAdminStmt->bindParam(':admin_phnumber', $admin_phnumber);
                        $updateAdminStmt->bindParam(':admin_gender', $admin_gender);
                        $updateAdminStmt->bindParam(':admin_bday', $admin_bday);
                        if ($updateAdminStmt->execute()) {
                            echo "<script>window.location.href='admin_profile.php?admin_email='+ '$admin_email' + '&action=profileUpdated';</script>";
                        } else {
                            echo "<script>window.location.href='admin_profile.php?admin_email='+ '$admin_email' + '&action=profileUpdateFail';</script>";
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?admin_email={$admin_email}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='profileDetailTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-5">Email</td>
                    <td><?php echo htmlspecialchars($admin_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='admin_fname' id="admin_fname" value="<?php echo htmlspecialchars($admin_fname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='admin_lname' id="admin_lname" value="<?php echo htmlspecialchars($admin_lname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><input type='text' name='admin_address' id="admin_address" value="<?php echo htmlspecialchars($admin_address, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><input type="tel" name="admin_phnumber" id="admin_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($admin_phnumber, ENT_QUOTES);  ?>" class='form-control' ></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="admin_gender" class="admin_gender" value="male" <?php echo htmlspecialchars($admin_gender == 'male') ? 'checked' : '' ?>>
                                Male
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="admin_gender" class="admin_gender" value="female" <?php echo htmlspecialchars($admin_gender == 'female') ? 'checked' : '' ?>>
                                Female
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><input type='date' name='admin_bday' id="admin_bday" value="<?php echo htmlspecialchars($admin_bday, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mb-3 mx-2' />
                <a href='admin_profile.php?admin_email={$admin_email}' class='actionBtn btn mb-3 mx-2'>Back</a>
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