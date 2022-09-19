<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Add new designer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
</head>

<style>
    #designer, #addDesigner {
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
                        <h2 class="my-5">Add New Designer</h2>
                    </div>
                </div>
                <h6 class="text-danger"> Please complete the form below with * completely. </h6>
            </div>
            <?php
            if ($_POST) {
                $filename = $_FILES["designer_image"]["name"];
                $tempname = $_FILES["designer_image"]["tmp_name"];
                $folder = "../image/designer/" . $filename;
                $isUploadOK = 1;

                try {
                    if (empty($_POST['designer_email']) || empty($_POST['designer_pword']) || empty($_POST['designer_fname']) || empty($_POST['designer_lname']) || empty($_POST['designer_phnumber']) || empty($_POST['designer_gender']) || empty($_POST['designer_qualification'])) {
                        throw new Exception("Make sure all fields are not empty");
                    }

                    if ($filename != "") {

                        $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                        $check = getimagesize($tempname);
                        if ($check == 0) {
                            $isUploadOK = 0;
                            throw new Exception("Please upload image ONLY! (JPG, JPEG, PNG & GIF)");
                        }

                        list($width, $height, $type, $attr) = getimagesize($tempname);
                        if ($width != $height) {
                            $isUploadOK = 0;
                            throw new Exception("Please make sure the ratio of the photo is 1:1.");
                        }

                        if ($_FILES["designer_image"]["size"] > 512000) {
                            $isUploadOK = 0;
                            throw new Exception("Sorry, your photo is too large. Only 512KB is allowed!");
                        }

                        if (
                            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif"
                        ) {
                            $isUploadOK = 0;
                            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF photo are allowed.");
                        }
                    }
                    
                    $checkQuery = "SELECT * FROM designer WHERE designer_email= :designer_email";
                    $checkStmt = $con->prepare($checkQuery);
                    $check_email = strtolower($_POST['designer_email']);
                    $checkStmt->bindParam(':designer_email', $check_email);
                    $checkStmt->execute();
                    $num = $checkStmt->rowCount();
                    if($num == 1){
                        throw new Exception("Email exist please try another email.");
                    }

                    if (15 < strlen($_POST['designer_pword']) || strlen($_POST['designer_pword']) < 8 || !preg_match("@[0-9]@", $_POST['designer_pword']) || !preg_match("@[a-z]@", $_POST['designer_pword']) || !preg_match("@[A-Z]@", $_POST['designer_pword']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['designer_pword'])) {
                        throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                    }

                    $createDesignerQuery = "INSERT INTO designer SET designer_email=:designer_email, designer_pword=:designer_pword, designer_fname=:designer_fname, designer_lname=:designer_lname, designer_phnumber=:designer_phnumber, designer_gender=:designer_gender, designer_qualification=:designer_qualification";
                    $createDesignerStmt = $con->prepare($createDesignerQuery);
                    $designer_email = strtolower($_POST['designer_email']);
                    $designer_pword = $_POST['designer_pword'];
                    $designer_fname = ucfirst($_POST['designer_fname']);
                    $designer_lname = ucfirst($_POST['designer_lname']);
                    $designer_phnumber = $_POST['designer_phnumber'];
                    $designer_gender = $_POST['designer_gender'];
                    $designer_qualification = $_POST['designer_qualification'];
                    $createDesignerStmt->bindParam(':designer_email', $designer_email);
                    $createDesignerStmt->bindParam(':designer_pword', $designer_pword);
                    $createDesignerStmt->bindParam(':designer_fname', $designer_fname);
                    $createDesignerStmt->bindParam(':designer_lname', $designer_lname);
                    $createDesignerStmt->bindParam(':designer_phnumber', $designer_phnumber);
                    $createDesignerStmt->bindParam(':designer_gender', $designer_gender);
                    $createDesignerStmt->bindParam(':designer_qualification', $designer_qualification);
                    if ($createDesignerStmt->execute()) {
                        $changePhotoName = explode(".", $_FILES["designer_image"]["name"]);
                        $newfilename = $designer_email . '_' . round(microtime(true)) . '.' . end($changePhotoName);
                        $latest_file = "../image/designer/" . $newfilename;
                        if ($folder != "") {
                            $insertPicQuery = "UPDATE designer SET designer_image=:designer_image WHERE designer_email = :designer_email";
                            $insertPicStmt = $con->prepare($insertPicQuery);
                            $insertPicStmt->bindParam(':designer_email', $designer_email);
                            if ($filename != "") {
                                $insertPicStmt->bindParam(':designer_image', $latest_file);
                            }
                            if ($insertPicStmt->execute()) {
                                if ($isUploadOK == 0) {
                                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                        <?php echo Sorry, your file was not uploaded.?>
                                    </div>";
                                } else {
                                    (move_uploaded_file($tempname, '../image/designer/' . $newfilename));
                                }
                            }
                        }
                        echo "<script>window.location.href='designer_list.php?action=created';</script>";
                    } else {
                        echo "<script>window.location.href='designer_list.php?action=createdFail';</script>";
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
                        <td>Designer Image <span class="text-danger">*</span></td>
                        <td><input type='file' name='designer_image' id="designer_image" value="<?php echo (isset($_FILES["designer"]["name"])) ? $_FILES["designer"]["name"] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Email <span class="text-danger">*</span></td>
                        <td><input type='text' name='designer_email' id="designer_email" value="<?php echo (isset($_POST['designer_email'])) ? $_POST['designer_email'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Password <span class="text-danger">*</span></td>
                        <td><input type='text' name='designer_pword' id="designer_pword" value="<?php echo (isset($_POST['designer_pword'])) ? $_POST['designer_pword'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='designer_fname' id="designer_fname" value="<?php echo (isset($_POST['designer_fname'])) ? $_POST['designer_fname'] : ''; ?>" class='form-control'/></td>
                    </tr>
                    <tr>
                        <td>Last Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='designer_lname' id="designerlname" value="<?php echo (isset($_POST['designer_lname'])) ? $_POST['designer_lname'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Phone Number <span class="text-danger">*</span></td>
                        <td><input type="tel" name="designer_phnumber" id="designer_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['designer_phnumber'])) ? $_POST['designer_phnumber'] : ''; ?>" class='form-control' ></td>
                    </tr>
                    <tr>
                        <td>Gender <span class="text-danger">*</span></td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="designer_gender" value="male" 
                                    <?php
                                    if(isset($_POST['designer_gender'])){
                                        echo $_POST['designer_gender'] == "male" ? 'checked' : '';
                                    }
                                    ?>>
                                    Male
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="designer_gender" value="female" 
                                    <?php
                                    if(isset($_POST['designer_gender'])){
                                        echo $_POST['designer_gender'] == "female" ? 'checked' : '';
                                    }
                                    ?>>
                                    Female
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Qualification <span class="text-danger">*</span></td>
                        <td><input type='text' name='designer_qualification' id="designer_qualification"  value="<?php echo (isset($_POST['designer_qualification'])) ? $_POST['designer_qualification'] : ''; ?>" class='form-control' /></td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Create' class='actionBtn btn mt-5 mx-2'/>
                    <a href='designer_list.php' class='actionBtn btn mt-5 mx-2'>Designer List</a>
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