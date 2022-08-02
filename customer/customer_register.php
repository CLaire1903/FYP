<!DOCTYPE HTML>
<html>

<head>
    <title>Customer - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
</head>

<style>
    .register {
        height:100%;
        width:100%;
        background-color:#ffeaea;
    }
    .contain {
        background-color: white;
    }
</style>

<body class="register container-flex">
    <div class="container">
        <?php
        include 'C:\xampp\htdocs\fyp\alertIcon.php';
        ?>
        <div class="page-header">
            <div class="d-flex flex-column">
                <div class="storeLogo d-flex justify-content-center ">
                    <a href="index.php"><img src="image/logo/logoB.png"></a>
                </div>
                <div class="d-flex justify-content-center">
                    <h2 class="p-1">Register new account.</h2>
                </div>
            </div>
            <h6 class="text-danger"> Please complete the form below with * completely. </h6>
        </div>
        <?php
        if ($_POST) {
            include 'C:\xampp\htdocs\fyp\config/dbase.php';
            try {
                if (empty($_POST['cus_email']) || empty($_POST['cus_pword']) || empty($_POST['cus_cpword']) || empty($_POST['cus_fname']) || empty($_POST['cus_lname']) || empty($_POST['cus_address']) || empty($_POST['cus_phnumber']) || empty($_POST['cus_gender']) || empty($_POST['cus_bday'])) {
                    throw new Exception("Make sure all fields are not empty");
                }
                
                $checkQuery = "SELECT * FROM customer WHERE cus_email= :cus_email";
                $checkStmt = $con->prepare($checkQuery);
                $check_email = strtolower($_POST['cus_email']);
                $checkStmt->bindParam(':cus_email', $check_email);
                $checkStmt->execute();
                $num = $checkStmt->rowCount();
                if($num == 1){
                    throw new Exception("Email exist please try another email.");
                }

                if ($_POST['cus_pword'] != $_POST['cus_cpword']) {
                    throw new Exception("Password and confirm password are not the same.");
                }

                if (15 < strlen($_POST['cus_pword']) || strlen($_POST['cus_pword']) < 8 || !preg_match("@[0-9]@", $_POST['cus_pword']) || !preg_match("@[a-z]@", $_POST['cus_pword']) || !preg_match("@[A-Z]@", $_POST['cus_pword']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["cus_pword"])) {
                    throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                }

                $query = "INSERT INTO customer SET cus_email=:cus_email, cus_pword=:cus_pword,cus_cpword=:cus_cpword, cus_fname=:cus_fname, cus_lname=:cus_lname, cus_address=:cus_address, cus_phnumber=:cus_phnumber, cus_gender=:cus_gender, cus_bday=:cus_bday";
                $stmt = $con->prepare($query);
                $cus_email = strtolower($_POST['cus_email']);
                $cus_pword = $_POST['cus_pword'];
                $cus_cpword = $_POST['cus_cpword'];
                $cus_fname = ucfirst($_POST['cus_fname']);
                $cus_lname = ucfirst($_POST['cus_lname']);
                $cus_address = strtolower($_POST['cus_address']);
                $cus_phnumber = $_POST['cus_phnumber'];
                $cus_gender = $_POST['cus_gender'];
                $cus_bday = $_POST['cus_bday'];
                $stmt->bindParam(':cus_email', $cus_email);
                $stmt->bindParam(':cus_pword', $cus_pword);
                $stmt->bindParam(':cus_cpword', $cus_cpword);
                $stmt->bindParam(':cus_fname', $cus_fname);
                $stmt->bindParam(':cus_lname', $cus_lname);
                $stmt->bindParam(':cus_address', $cus_address);
                $stmt->bindParam(':cus_phnumber', $cus_phnumber);
                $stmt->bindParam(':cus_gender', $cus_gender);
                $stmt->bindParam(':cus_bday', $cus_bday);
                if ($stmt->execute()) {
                    header("Location: customer_registerSuccessful.php");
                    } else {
                        throw new Exception("Unable to save record.");
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
        <div class="contain p-3 rounded">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Email <span class="text-danger">*</span></td>
                        <td><input type='text' name='cus_email' id="cus_email" value="<?php echo (isset($_POST['cus_email'])) ? $_POST['cus_email'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Password <span class="text-danger">*</span></td>
                        <td><input type='text' name='cus_pword' id="cus_pword" value="<?php echo (isset($_POST['cus_pword'])) ? $_POST['cus_pword'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Confirm Password <span class="text-danger">*</span></td>
                        <td><input type='text' name='cus_cpword' id="cus_cpword" value="<?php echo (isset($_POST['cus_cpword'])) ? $_POST['cus_cpword'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='cus_fname' id="cus_fname" value="<?php echo (isset($_POST['cus_fname'])) ? $_POST['cus_fname'] : ''; ?>" class='form-control'/></td>
                    </tr>
                    <tr>
                        <td>Last Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='cus_lname' id="cus_lname" value="<?php echo (isset($_POST['cus_lname'])) ? $_POST['cus_lname'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Address <span class="text-danger">*</span></td>
                        <td><textarea type='text' name='cus_address' id="cus_address" class='form-control' rows="3"><?php echo (isset($_POST['cus_address'])) ? $_POST['cus_address'] : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Phone Number <span class="text-danger">*</span></td>
                        <td><input type="tel" name="cus_phnumber" id="cus_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['cus_phnumber'])) ? $_POST['cus_phnumber'] : ''; ?>" class='form-control' ></td>
                    </tr>
                    <tr>
                        <td>Gender <span class="text-danger">*</span></td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="cus_gender" value="male" 
                                    <?php
                                    if(isset($_POST['cus_gender'])){
                                        echo $_POST['cus_gender'] == "male" ? 'checked' : '';
                                    }
                                    ?>>
                                    Male
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="cus_gender" value="female" 
                                    <?php
                                    if(isset($_POST['cus_gender'])){
                                        echo $_POST['cus_gender'] == "female" ? 'checked' : '';
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
                        <td><input type='date' name='cus_bday' id="cus_bday"  value="<?php echo (isset($_POST['cus_bday'])) ? $_POST['cus_bday'] : ''; ?>" class='form-control' /></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Register' class='actionBtn btn mb-2 mx-2' />
                    <a href='customer_login.php' class='actionBtn btn mb-3 mx-2'>Back to Login</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        /*function validation() {
            var cus_username = document.getElementById("cus_username").value;
            var password = document.getElementById("password").value;
            var passwordValidation = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var gender = document.querySelectorAll("input[type=radio][name=gender]:checked");
            var dateOfBirth = document.getElementById("dateOfBirth").value;
            var accountStatus = document.querySelectorAll("input[type=radio][name=accountStatus]:checked");
            var flag = false;
            var msg = "";
            if (cus_username == "" || password == "" || confirmPassword == "" || firstName == "" || lastName == "" || gender.length == 0 || dateOfBirth == "" || accountStatus.length == 0) {
                flag = true;
                msg = msg + "Please make sure all fields are not empty! (profile picture is optional)\r\n";
            }
            if (cus_username.length < 6 || cus_username.length > 15 || cus_username.indexOf(' ') >= 0) {
                flag = true;
                msg = msg + "Username must be 6 - 15 characters and no space included!\r\n";
            }
            if (password != confirmPassword) {
                flag = true;
                msg = msg + "Password and confirm password are not the same!\r\n";
            }
            if (password.length < 8 || password.length > 15) {
                flag = true;
                msg = msg + "Password should be 8 - 15 character!\r\n";
            }
            if (password.match(passwordValidation)) {} else {
                flag = true;
                msg = msg + "Password should contain at least a number, a special character, a SMALL letter and a CAPITAL letter!\r\n";
            }
            var birthDate = new Date(dateOfBirth);
            var difference = Date.now() - birthDate.getTime();
            var ageDate = new Date(difference);
            var calculatedAge = Math.abs(ageDate.getUTCFullYear() - 1970);
            if (calculatedAge < 18) {
                flag = true;
                msg = msg + "User must be 18 years old and above!\r\n";
            }
            if (flag == true) {
                alert(msg);
                return false;
            } else {
                return true;
            }
        }*/
    </script>

</body>
</html>