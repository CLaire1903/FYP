<?php
session_start();
if (!isset($_SESSION["designer_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/designer.css" rel="stylesheet">
    <link href='/fyp/css/update.css' rel="stylesheet">

    <style>
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'navigationBar.php';
        include 'C:\xampp\htdocs\fyp\alertIcon.php';
        $designer_email = isset($_GET['designer_email']) ? $_GET['designer_email'] : die('ERROR: designer record not found.');
        ?>
        <div class="page-header mx-5 mt-5">
            <h1>Update Profile</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>

        <div class="updateProfileDetail d-flex flex-column justify-content-center mx-5">
            <?php
                try {
                    $getDesignerQuery = "SELECT * FROM designer WHERE designer_email = :designer_email ";
                    $getDesignerStmt = $con->prepare($getDesignerQuery);
                    $getDesignerStmt->bindParam(":designer_email", $designer_email);
                    $getDesignerStmt->execute();
                    $getDesignerRow = $getDesignerStmt->fetch(PDO::FETCH_ASSOC);
                    $designer_img = $getDesignerRow['designer_image'];
                    $designer_email = $getDesignerRow['designer_email'];
                    $designer_pword = $getDesignerRow['designer_pword'];
                    $designer_fname = $getDesignerRow['designer_fname'];
                    $designer_lname= $getDesignerRow['designer_lname'];
                    $designer_gender= $getDesignerRow['designer_gender'];
                    $designer_phnumber = $getDesignerRow['designer_phnumber'];
                    $designer_qualification = $getDesignerRow['designer_qualification'];
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                if ($_POST) {
                    $file = $_FILES["designer_image"]["name"];
                    $temp = $_FILES["designer_image"]["tmp_name"];
                    $folder = "../image/designer/" . $file;
                    $default = "../image/designer/default.png";
                    $changeImageName = explode(".", $_FILES["designer_image"]["name"]);
                    $newfilename = $designer_email . '_' . round(microtime(true)) . '.' . end($changeImageName);
                    $latest_file = "../image/designer/" . $newfilename;
                    $isUploadOK = 1;

                    try {
                        if (empty($_POST['designer_pword']) || empty($_POST['designer_fname']) || empty($_POST['designer_lname']) || empty($_POST['designer_phnumber']) || empty($_POST['designer_gender'])) {
                            throw new Exception("Please make sure all fields are not empty!");
                        }

                        if (15 < strlen($_POST['designer_pword']) || strlen($_POST['designer_pword']) < 8 || !preg_match("@[0-9]@", $_POST['designer_pword']) || !preg_match("@[a-z]@", $_POST['designer_pword']) || !preg_match("@[A-Z]@", $_POST['designer_pword']) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST['designer_pword'])) {
                            throw new Exception("Password should be 8 - 15 character, contain at least a number, a special character, a <strong>SMALL</strong> letter, a<strong> CAPITAL </strong>letter");
                        }

                        if ($file != "") {

                            $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                            $check = getimagesize($temp);
                            //make sure user uploaded image only
                            if ($check == 0) {
                                $isUploadOK = 0;
                                throw new Exception("Please upload image only! (JPG, JPEG, PNG & GIF)");
                            }
    
                            //make sure the image is 1:1
                            list($width, $height, $type, $attr) = getimagesize($temp);
                            if ($width != $height) {
                                $isUploadOK = 0;
                                throw new Exception("Please make sure the ratio of the photo is 1:1!");
                            }
    
                            //make sure the size is lower than 512KB
                            if ($_FILES["designer_image"]["size"] > 512000) {
                                $isUploadOK = 0;
                                throw new Exception("Sorry, your file is too large. Only 512KB is allowed!");
                            }
    
                            //check image file type
                            if (
                                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif"
                            ) {
                                $isUploadOK = 0;
                                throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed!");
                            }
                        }
                        
                        if (isset($_POST['delete_image'])) {
                            if (unlink($designer_img)) {
                                $designer_img = $default;
                            }
                        }
        
                        if ($folder != "") {
                            if($designer_img == $default){
                                $designerImg = "designer_image=:designer_image";
                            } else {
                                if(unlink($designer_img)){
                                    $designerImg = "designer_image=:designer_image";
                                }
                            }
                        }

                        //update the customer detail into the database
                        $updateDesignerQuery = "UPDATE designer SET $designerImg, designer_pword=:designer_pword, designer_fname=:designer_fname, designer_lname=:designer_lname,designer_phnumber=:designer_phnumber , designer_gender=:designer_gender, designer_qualification=:designer_qualification WHERE designer_email = :designer_email";
                        $updateDesignerStmt = $con->prepare($updateDesignerQuery);
                        $designer_pword = htmlspecialchars(strip_tags($_POST['designer_pword']));
                        $designer_fname = htmlspecialchars(strip_tags(ucfirst($_POST['designer_fname'])));
                        $designer_lname = htmlspecialchars(strip_tags(ucfirst($_POST['designer_lname'])));
                        $designer_phnumber = htmlspecialchars(strip_tags($_POST['designer_phnumber']));
                        $designer_gender = htmlspecialchars(strip_tags($_POST['designer_gender']));
                        $designer_qualification = htmlspecialchars(strip_tags($_POST['designer_qualification']));

                        
                        if ($file != "") {
                            $designer_img = htmlspecialchars(strip_tags($latest_file));
                            $updateDesignerStmt->bindParam(':designer_image', $latest_file);
                        } else {
                            $designer_img = htmlspecialchars(strip_tags($designer_img));
                            $updateDesignerStmt->bindParam(':designer_image', $designer_img);
                        }
                        
                        $updateDesignerStmt->bindParam(':designer_email', $designer_email);
                        $updateDesignerStmt->bindParam(':designer_pword', $designer_pword);
                        $updateDesignerStmt->bindParam(':designer_fname', $designer_fname);
                        $updateDesignerStmt->bindParam(':designer_lname', $designer_lname);
                        $updateDesignerStmt->bindParam(':designer_phnumber', $designer_phnumber);
                        $updateDesignerStmt->bindParam(':designer_gender', $designer_gender);
                        $updateDesignerStmt->bindParam(':designer_qualification', $designer_qualification);

                        if ($updateDesignerStmt->execute()) {
                            if ($folder != "") {
                                if ($isUploadOK == 0) {
                                    echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                        <div>
                                            Sorry, your file was not uploaded.
                                        </div>
                                    </div>";
                                } else {
                                    move_uploaded_file($temp, '../image/designer/' . $newfilename);
                                }
                            }
                            echo "<script>window.location.href='designer_detail.php?designer_email='+ '$designer_email' + '&action=profileUpdated';</script>";
                        } else {
                            echo "<script>window.location.href='designer_detail.php?designer_email='+ '$designer_email' + '&action=profileUpdateFail';</script>";
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?designer_email={$designer_email}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
            <table class='profileDetailTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Profile Image</td>
                    <td>
                        <div>
                            <div class='img-block m-2 d-flex'> 
                                <div>
                                    <img src=<?php echo htmlspecialchars($designer_img, ENT_QUOTES); ?> alt='' class='designerImage'/>
                                </div>
                                <div  id="deleteImage" class="d-flex flex-column justify-content-between">
                                    <button type="submit" class="deleteBtn btn mx-2 p-1" name="delete_image" <?php if ($designer_img == "../image/designer/default.png"){ echo("id = delImg_btn");} ?>>x</button>
                                </div>
                            </div>
                            <?php if ($designer_img == "../image/designer/default.png"){ 
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Add Image</button>';
                            } else {
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Change Image</button>';
                            }?>
                             
                            <div id='form-popup'>
                                <div class="d-flex">
                                    <input type='file' name='designer_image' id="designer_image" class='form-control' />
                                    <button type="button" class="cancelBtn btn mx-2 p-1" onclick="closeForm()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-5">Email</td>
                    <td><?php echo htmlspecialchars($designer_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='designer_pword' id="designer_pword" value="<?php echo htmlspecialchars($designer_pword, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='designer_fname' id="designer_fname" value="<?php echo htmlspecialchars($designer_fname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='designer_lname' id="designer_lname" value="<?php echo htmlspecialchars($designer_lname, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><input type="tel" name="designer_phnumber" id="designer_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($designer_phnumber, ENT_QUOTES);  ?>" class='form-control' ></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="designer_gender" class="designer_gender" value="male" <?php echo htmlspecialchars($designer_gender == 'male') ? 'checked' : '' ?>>
                                Male
                                <span class="select"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="radio" name="designer_gender" class="designer_gender" value="female" <?php echo htmlspecialchars($designer_gender == 'female') ? 'checked' : '' ?>>
                                Female
                                <span class="select"></span>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Qualification</td>
                    <td><input type='text' name='designer_qualification' id="designer_qualification" value="<?php echo htmlspecialchars($designer_qualification, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mb-3 mx-2' />
                <a href='designer_detail.php?designer_email={$designer_email}' class='actionBtn btn mb-3 mx-2'>Back</a>
            </div>
        </form>
    </div>
            
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script>
        function openForm() {
            document.getElementById("form-popup").style.display = "block";
        }

        function closeForm() {
            document.getElementById("form-popup").style.display = "none";
        }
    </script>
</body>

</html>