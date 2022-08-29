<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Staff Detail</title>
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

        $admin_email = isset($_GET['admin_email']) ? $_GET['admin_email'] : die('ERROR: Admin record not found.');
        if($admin_email == $_SESSION["admin_email"]){
            echo "<script>window.location.href='staff_list.php?action=updateOwn';</script>";
        }
        ?>
        <div class="page-header mx-5">
            <h1 class="text-center my-5">Update Staff Detail</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>

        <div class="updateProfileDetail d-flex flex-column justify-content-center mx-5">
            <?php
                try {
                    $getStaffQuery = "SELECT * FROM admin WHERE admin_email = :admin_email ";
                    $getStaffStmt = $con->prepare($getStaffQuery);
                    $getStaffStmt->bindParam(":admin_email", $admin_email);
                    $getStaffStmt->execute();
                    $getStaffRow = $getStaffStmt->fetch(PDO::FETCH_ASSOC);
                    $admin_email = $getStaffRow['admin_email'];
                    $admin_pword = $getStaffRow['admin_pword'];
                    $admin_fname = $getStaffRow['admin_fname'];
                    $admin_lname= $getStaffRow['admin_lname'];
                    $admin_address= $getStaffRow['admin_address'];
                    $admin_phnumber = $getStaffRow['admin_phnumber'];
                    $admin_gender = $getStaffRow['admin_gender'];
                    $admin_bday = $getStaffRow['admin_bday'];
                    $admin_position = $getStaffRow['admin_position'];
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                if ($_POST) {
                    try {
                        if (empty($_POST['admin_pword']) || empty($_POST['admin_fname']) || empty($_POST['admin_lname']) || empty($_POST['admin_address']) || empty($_POST['admin_phnumber']) || empty($_POST['admin_gender']) || empty($_POST['admin_bday'])) {
                            throw new Exception("Please make sure all fields are not empty!");
                        }
                        
                        if (15 < strlen($_POST['admin_pword']) || strlen($_POST['admin_pword']) < 8 || !preg_match("@[0-9]@", $_POST['admin_pword']) || !preg_match("@[a-z]@", $_POST['admin_pword']) || !preg_match("@[A-Z]@", $_POST['admin_pword']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['admin_pword'])) {
                            throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                        }
    
                        $today = strtotime(date("Y-m-d"));
                        $admin_bday = strtotime($_POST['admin_bday']);
                        $age = $today - $admin_bday;
                        if ($age < 18) {
                            throw new Exception("Staff must at least 18 years old!");
                        }

                        $updateStaffQuery = "UPDATE admin SET admin_pword=:admin_pword, admin_fname=:admin_fname, admin_lname=:admin_lname, admin_address=:admin_address, admin_phnumber=:admin_phnumber , admin_gender=:admin_gender, admin_bday=:admin_bday, admin_position=:admin_position WHERE admin_email = :admin_email";
                        $updateStaffStmt = $con->prepare($updateStaffQuery);
                        $admin_pword = htmlspecialchars(strip_tags($_POST['admin_pword']));
                        $admin_fname = htmlspecialchars(strip_tags(ucfirst($_POST['admin_fname'])));
                        $admin_lname = htmlspecialchars(strip_tags(ucfirst($_POST['admin_lname'])));
                        $admin_address = htmlspecialchars(strip_tags($_POST['admin_address']));
                        $admin_phnumber = htmlspecialchars(strip_tags($_POST['admin_phnumber']));
                        $admin_gender = htmlspecialchars(strip_tags($_POST['admin_gender']));
                        $admin_bday = htmlspecialchars(strip_tags($_POST['admin_bday']));
                        $admin_position = htmlspecialchars(strip_tags($_POST['admin_position']));

                        $updateStaffStmt->bindParam(':admin_email', $admin_email);
                        $updateStaffStmt->bindParam(':admin_pword', $admin_pword);
                        $updateStaffStmt->bindParam(':admin_fname', $admin_fname);
                        $updateStaffStmt->bindParam(':admin_lname', $admin_lname);
                        $updateStaffStmt->bindParam(':admin_address', $admin_address);
                        $updateStaffStmt->bindParam(':admin_phnumber', $admin_phnumber);
                        $updateStaffStmt->bindParam(':admin_gender', $admin_gender);
                        $updateStaffStmt->bindParam(':admin_bday', $admin_bday);
                        $updateStaffStmt->bindParam(':admin_position', $admin_position);
                        if ($updateStaffStmt->execute()) {
                            echo "<script>window.location.href='staff_detail.php?admin_email='+ '$admin_email' + '&action=detailUpdated';</script>";
                        } else {
                            echo "<script>window.location.href='staff_detail.php?admin_email='+ '$admin_email' + '&action=detailUpdateFail';</script>";
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?admin_email={$admin_email}"); ?>" method="post" enctype="multipart/form-data">
            <table class='profileDetailTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-5">Email</td>
                    <td><?php echo htmlspecialchars($admin_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Password <span class="text-danger">*</span></td>
                    <td><input type='text' name='admin_pword' id="admin_pword" value="<?php echo htmlspecialchars($admin_pword, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='admin_fname' id="admin_fname" value="<?php echo htmlspecialchars($admin_fname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='admin_lname' id="admin_lname" value="<?php echo htmlspecialchars($admin_lname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Address <span class="text-danger">*</span></td>
                    <td><input type='text' name='admin_address' id="admin_address" value="<?php echo htmlspecialchars($admin_address, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Phone Number <span class="text-danger">*</span></td>
                    <td><input type="tel" name="admin_phnumber" id="admin_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($admin_phnumber, ENT_QUOTES);  ?>" class='form-control' ></td>
                </tr>
                <tr>
                    <td>Gender <span class="text-danger">*</span></td>
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
                    <td>Date Of Birth <span class="text-danger">*</span></td>
                    <td><input type='date' name='admin_bday' id="admin_bday" value="<?php echo htmlspecialchars($admin_bday, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Position <span class="text-danger">*</span></td>
                    <td>
                        <?php 
                            $checkPositionQuery = "SELECT * FROM admin WHERE admin_email=:admin_email";
                            $checkPositionStmt = $con->prepare($checkPositionQuery);
                            $staff_email = $_SESSION['admin_email'];
                            $checkPositionStmt->bindParam(":admin_email", $staff_email);
                            $checkPositionStmt->execute();
                            $checkPositionRow = $checkPositionStmt->fetch(PDO::FETCH_ASSOC);
                            $staff_position = $checkPositionRow['admin_position'];
                            if ($staff_position == "director") { ?>
                                <div class="form-check">
                                    <label>
                                        <input type="radio" name="admin_position" class="admin_position" value="director" <?php echo htmlspecialchars($admin_position == 'director') ? 'checked' : '' ?>>
                                        Director
                                        <span class="select"></span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label>
                                        <input type="radio" name="admin_position" class="admin_position" value="manager" <?php echo htmlspecialchars($admin_position == 'manager') ? 'checked' : '' ?>>
                                        Manager
                                        <span class="select"></span>
                                    </label>
                                </div>
                        <?php } ?>
                        
                        <div class="form-check">
                            <label>
                                <input type="radio" name="admin_position" class="admin_position" value="senior" <?php echo htmlspecialchars($admin_position == 'senior') ? 'checked' : '' ?>>
                                Senior
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="admin_position" class="admin_position" value="junior" <?php echo htmlspecialchars($admin_position == 'junior') ? 'checked' : '' ?>>
                                Junior
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="admin_position" class="admin_position" value="intern" <?php echo htmlspecialchars($admin_position == 'intern') ? 'checked' : '' ?>>
                                Internship
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mt-5 mx-2' />
                <?php echo "<a href='admin_profile.php?admin_email={$staff_email}' class='actionBtn btn mt-5 mx-2'>Back</a>"; ?>
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