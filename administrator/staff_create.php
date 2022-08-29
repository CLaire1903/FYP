<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Add new staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
</head>

<style>
    #staff, #addStaff {
        font-weight: bold;
    }
    .contain {
        background-color: white;
    }
</style>

<body>
    <div class="container-fluid p-0">
        <?php
            include '../config/dbase.php';
            include '../alertIcon.php';
            include 'navigationBar.php';
        ?>
        <div class="mx-5">
            <div class="page-header">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-center">
                        <h2 class="my-5">Add New Staff</h2>
                    </div>
                </div>
                <h6 class="text-danger"> Please complete the form below with * completely. </h6>
            </div>
            <?php
            if ($_POST) {
                try {
                    if (empty($_POST['admin_email']) || empty($_POST['admin_pword']) || empty($_POST['admin_fname']) || empty($_POST['admin_lname']) || empty($_POST['admin_address']) || empty($_POST['admin_phnumber']) || empty($_POST['admin_gender']) || empty($_POST['admin_bday']) || empty($_POST['admin_position'])) {
                        throw new Exception("Make sure all fields are not empty");
                    }
                    
                    $checkQuery = "SELECT * FROM admin WHERE admin_email= :admin_email";
                    $checkStmt = $con->prepare($checkQuery);
                    $check_email = strtolower($_POST['admin_email']);
                    $checkStmt->bindParam(':admin_email', $check_email);
                    $checkStmt->execute();
                    $num = $checkStmt->rowCount();
                    if($num == 1){
                        throw new Exception("Email exist please try another email.");
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

                    $createStaffQuery = "INSERT INTO admin SET admin_email=:admin_email, admin_pword=:admin_pword, admin_fname=:admin_fname, admin_lname=:admin_lname, admin_address=:admin_address, admin_phnumber=:admin_phnumber, admin_gender=:admin_gender, admin_bday=:admin_bday, admin_position=:admin_position";
                    $createStaffStmt = $con->prepare($createStaffQuery);
                    $admin_email = strtolower($_POST['admin_email']);
                    $admin_pword = $_POST['admin_pword'];
                    $admin_fname = ucfirst($_POST['admin_fname']);
                    $admin_lname = ucfirst($_POST['admin_lname']);
                    $admin_address = strtolower($_POST['admin_address']);
                    $admin_phnumber = $_POST['admin_phnumber'];
                    $admin_gender = $_POST['admin_gender'];
                    $admin_bday = $_POST['admin_bday'];
                    $admin_position = $_POST['admin_position'];
                    $createStaffStmt->bindParam(':admin_email', $admin_email);
                    $createStaffStmt->bindParam(':admin_pword', $admin_pword);
                    $createStaffStmt->bindParam(':admin_fname', $admin_fname);
                    $createStaffStmt->bindParam(':admin_lname', $admin_lname);
                    $createStaffStmt->bindParam(':admin_address', $admin_address);
                    $createStaffStmt->bindParam(':admin_phnumber', $admin_phnumber);
                    $createStaffStmt->bindParam(':admin_gender', $admin_gender);
                    $createStaffStmt->bindParam(':admin_bday', $admin_bday);
                    $createStaffStmt->bindParam(':admin_position', $admin_position);
                    if ($createStaffStmt->execute()) {
                        echo "<script>window.location.href='staff_list.php?action=staffCreated';</script>";
                        } else {
                            echo "<script>window.location.href='staff_list.php?action=staffCreatedFail';</script>";
                        }
                    } catch (PDOException $exception) {
                        echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                " . $exception->getMessage() . "
                            </div>
                            </div>";
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            " . $exception->getMessage() . "
                        </div>
                        </div>";
                }
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Email <span class="text-danger">*</span></td>
                        <td><input type='text' name='admin_email' id="admin_email" value="<?php echo (isset($_POST['admin_email'])) ? $_POST['admin_email'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Password <span class="text-danger">*</span></td>
                        <td><input type='text' name='admin_pword' id="admin_pword" value="<?php echo (isset($_POST['admin_pword'])) ? $_POST['admin_pword'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='admin_fname' id="admin_fname" value="<?php echo (isset($_POST['admin_fname'])) ? $_POST['admin_fname'] : ''; ?>" class='form-control'/></td>
                    </tr>
                    <tr>
                        <td>Last Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='admin_lname' id="admin_lname" value="<?php echo (isset($_POST['admin_lname'])) ? $_POST['admin_lname'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Address <span class="text-danger">*</span></td>
                        <td><textarea type='text' name='admin_address' id="admin_address" class='form-control' rows="3"><?php echo (isset($_POST['admin_address'])) ? $_POST['admin_address'] : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Phone Number <span class="text-danger">*</span></td>
                        <td><input type="tel" name="admin_phnumber" id="admin_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['admin_phnumber'])) ? $_POST['admin_phnumber'] : ''; ?>" class='form-control' ></td>
                    </tr>
                    <tr>
                        <td>Gender <span class="text-danger">*</span></td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="admin_gender" value="male" 
                                    <?php
                                    if(isset($_POST['admin_gender'])){
                                        echo $_POST['admin_gender'] == "male" ? 'checked' : '';
                                    }
                                    ?>>
                                    Male
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="admin_gender" value="female" 
                                    <?php
                                    if(isset($_POST['admin_gender'])){
                                        echo $_POST['admin_gender'] == "female" ? 'checked' : '';
                                    }
                                    ?>>
                                    Female
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Date Of Birth <span class="text-danger">*</span></td>
                        <td><input type='date' name='admin_bday' id="admin_bday"  value="<?php echo (isset($_POST['admin_bday'])) ? $_POST['admin_bday'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Position <span class="text-danger">*</span></td>
                        <td>
                        <?php 
                            $checkPositionQuery = "SELECT * FROM admin WHERE admin_email=:admin_email";
                            $checkPositionStmt = $con->prepare($checkPositionQuery);
                            $admin_email = $_SESSION['admin_email'];
                            $checkPositionStmt->bindParam(":admin_email", $admin_email);
                            $checkPositionStmt->execute();
                            $checkPositionRow = $checkPositionStmt->fetch(PDO::FETCH_ASSOC);
                            $admin_position = $checkPositionRow['admin_position'];
                            if ($admin_position == "director") {
                                echo "<div class='form-check'>";
                                    echo "<label>";
                                        echo "<input type='radio' name='admin_position' value='director'";
                                            if(isset($_POST['admin_position'])){
                                                echo $_POST['admin_position'] == "director" ? 'checked' : '';
                                            }
                                        echo ">";
                                        echo " Director";
                                        echo "<span class='select'></span>";
                                    echo "</label>";
                                echo "</div>";
                                echo "<div class='form-check'>";
                                    echo "<label>";
                                        echo "<input type='radio' name='admin_position' value='manager'";
                                            if(isset($_POST['admin_position'])){
                                                echo $_POST['admin_position'] == "manager" ? 'checked' : '';
                                            }
                                        echo ">";
                                        echo " Manager";
                                        echo "<span class='select'></span>";
                                    echo "</label>";
                                echo "</div>";
                            }
                        ?>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="admin_position" value="senior" 
                                    <?php
                                    if(isset($_POST['admin_position'])){
                                        echo $_POST['admin_position'] == "senior" ? 'checked' : '';
                                    }
                                    ?>>
                                    Senior
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="admin_position" value="junior" 
                                    <?php
                                    if(isset($_POST['admin_position'])){
                                        echo $_POST['admin_position'] == "junior" ? 'checked' : '';
                                    }
                                    ?>>
                                    Junior
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="admin_position" value="intern" 
                                    <?php
                                    if(isset($_POST['admin_position'])){
                                        echo $_POST['admin_position'] == "intern" ? 'checked' : '';
                                    }
                                    ?>>
                                    Internship
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Register' class='actionBtn btn mt-5 mx-2'/>
                    <a href='staff_list.php' class='actionBtn btn mt-5 mx-2'>Staff List</a>
                </div>
            </form>
        </div>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    

</body>
</html>