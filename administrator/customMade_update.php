<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Update Custom Made</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href='../css/update.css' rel="stylesheet">

    <style>
        .customizedImage {
            width: 150px;
            height: 150px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include '../config/dbase.php';
        include '../alertIcon.php';
        include 'navigationBar.php';
        $customized_id = isset($_GET['customized_id']) ? $_GET['customized_id'] : die('ERROR: Custom Made record not found.');
        ?>
        <div class="page-header mx-5 mt-5">
            <h1 class="text-center mb-5">Update Custom Made Detail</h1>
            <h6 class="text-danger"> NOTE! Please refresh if you do not see any changes. </h6>
        </div>

        <div class="updateDetail d-flex flex-column justify-content-center mx-5">
            <?php
                try {
                    $getCustomizedQuery = "SELECT * FROM customized WHERE customized_id=:customized_id";
                    $getCustomizedStmt = $con->prepare($getCustomizedQuery);
                    $getCustomizedStmt->bindParam(":customized_id", $customized_id);
                    $getCustomizedStmt->execute();
                    $getCustomizedRow = $getCustomizedStmt->fetch(PDO::FETCH_ASSOC);

                    $customized_img = $getCustomizedRow['customized_image'];
                    $customized_id = $getCustomizedRow['customized_id'];
                    $cus_email = $getCustomizedRow['cus_email'];
                    $cus_name = $getCustomizedRow['cus_name'];
                    $cus_phnumber = $getCustomizedRow['cus_phnumber'];
                    $customized_detail = $getCustomizedRow['customized_detail'];
                    $customized_collectdate = $getCustomizedRow['customized_collectdate'];
                    $designer_email = $getCustomizedRow['designer_email'];
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }

                if ($_POST) {
                    $file = $_FILES["customized_image"]["name"];
                    $temp = $_FILES["customized_image"]["tmp_name"];
                    $folder = "../image/customized/" . $file;
                    $default = "../image/customized/default.jpg";
                    $changeImageName = explode(".", $_FILES["customized_image"]["name"]);
                    $newfilename = 'ID' . $customized_id . '_' . round(microtime(true)) . '.' . end($changeImageName);
                    $latest_file = "../image/customized/" . $newfilename;
                    $isUploadOK = 1;

                    try {
                        if (empty($_POST['cus_email']) || empty($_POST['cus_name']) || empty($_POST['cus_phnumber']) || empty($_POST['customized_detail']) || empty($_POST['customized_collectdate'])) {
                            throw new Exception("Make sure all fields are not empty");
                        }

                        if (empty($_POST['designer_email'])) {
                            throw new Exception("Please assign designer!");
                        }

                        if ($file != "") {

                            $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                            $check = getimagesize($temp);
                            if ($check == 0) {
                                $isUploadOK = 0;
                                throw new Exception("Please upload image only! (JPG, JPEG, PNG & GIF)");
                            }
    
                            list($width, $height, $type, $attr) = getimagesize($temp);
                            if ($width != $height) {
                                $isUploadOK = 0;
                                throw new Exception("Please make sure the ratio of the photo is 1:1!");
                            }
    
                            if ($_FILES["customized_image"]["size"] > 512000) {
                                $isUploadOK = 0;
                                throw new Exception("Sorry, your file is too large. Only 512KB is allowed!");
                            }
    
                            if (
                                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif"
                            ) {
                                $isUploadOK = 0;
                                throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed!");
                            }
                        }
                        
                        if (isset($_POST['delete_image'])) {
                            if (unlink($customized_img)) {
                                $customized_img = $default;
                            }
                        }

                        if ($file != "") {
                            unlink($customized_img);
                        }
        
                        if ($folder != "") {
                            if($customized_img == $default){
                                $customizedImg = "customized_image=:customized_image";
                            } else {
                                $customizedImg = "customized_image=:customized_image";
                            }
                        }

                        $updateCustomizedQuery = "UPDATE customized SET $customizedImg, cus_email=:cus_email, cus_name=:cus_name,cus_phnumber=:cus_phnumber, customized_detail=:customized_detail, customized_collectdate=:customized_collectdate, designer_email=:designer_email WHERE customized_id = :customized_id";
                        $updateCustomizedStmt = $con->prepare($updateCustomizedQuery);
                        $cus_email = htmlspecialchars(strip_tags(strtolower($_POST['cus_email']) ));
                        $cus_name = htmlspecialchars(strip_tags(ucfirst($_POST['cus_name'])));
                        $cus_phnumber = htmlspecialchars(strip_tags($_POST['cus_phnumber']));
                        $customized_detail = htmlspecialchars(strip_tags(ucfirst($_POST['customized_detail'])));
                        $customized_collectdate = htmlspecialchars(strip_tags($_POST['customized_collectdate']));
                        $designer_email = htmlspecialchars(strip_tags($_POST['designer_email']));

                        $updateCustomizedStmt->bindParam(':customized_id', $customized_id);
                        if ($file != "") {
                            $customized_img = htmlspecialchars(strip_tags($latest_file));
                            $updateCustomizedStmt->bindParam(':customized_image', $latest_file);
                        } else {
                            $customized_img = htmlspecialchars(strip_tags($customized_img));
                            $updateCustomizedStmt->bindParam(':customized_image', $customized_img);
                        }
                        $updateCustomizedStmt->bindParam(':cus_email', $cus_email);
                        $updateCustomizedStmt->bindParam(':cus_name', $cus_name);
                        $updateCustomizedStmt->bindParam(':cus_phnumber', $cus_phnumber);
                        $updateCustomizedStmt->bindParam(':customized_detail', $customized_detail);
                        $updateCustomizedStmt->bindParam(':customized_collectdate', $customized_collectdate);
                        $updateCustomizedStmt->bindParam(':designer_email', $designer_email);
                        if ($updateCustomizedStmt->execute()) {
                            if ($folder != "") {
                                if ($isUploadOK == 0) {
                                    echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                        <div>
                                            Sorry, your file was not uploaded.
                                        </div>
                                    </div>";
                                } else {
                                    move_uploaded_file($temp, '../image/customized/' . $newfilename);
                                }
                            }
                            echo "<script>window.location.href='customMade_detail.php?customized_id='+ '$customized_id' + '&action=customizedUpdated';</script>";
                        } else {
                            echo "<script>window.location.href='customMade_detail.php?customized_id='+ '$customized_id' + '&action=customizedUpdateFail';</script>";
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?customized_id={$customized_id}"); ?>" method="post" enctype="multipart/form-data">
            <table class='detailTable table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Product Image</td>
                    <td>
                        <div>
                            <div class='img-block m-2 d-flex'> 
                                <div>
                                    <img src=<?php echo htmlspecialchars($customized_img, ENT_QUOTES); ?> alt='' class='customizedImage'/>
                                </div>
                                <div  id="deleteImage" class="d-flex flex-column justify-content-between">
                                    <button type="submit" class="deleteBtn btn mx-2 p-1" name="delete_image" <?php if ($customized_img == "../image/customized/default.jpg"){ echo("id = delImg_btn");} ?>>x</button>
                                </div>
                            </div>
                            <?php if ($customized_img == "../image/customized/default.jpg"){ 
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Add Image</button>';
                            } else {
                                echo '<button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Change Image</button>';
                            }?>
                             
                            <div id='form-popup'>
                                <div class="d-flex">
                                    <input type='file' name='customized_image' id="customized_image" class='form-control' />
                                    <button type="button" class="cancelBtn btn mx-2 p-1" onclick="closeForm()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Customer Email <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_email' id="cus_email" value="<?php echo htmlspecialchars($cus_email, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Customer Name <span class="text-danger">*</span></td>
                    <td><input type='text' name='cus_name' id="cus_name" value="<?php echo htmlspecialchars($cus_name, ENT_QUOTES); ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Phone Number <span class="text-danger">*</span></td>
                    <td><input type="tel" name="cus_phnumber" id="cus_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo htmlspecialchars($cus_phnumber, ENT_QUOTES);  ?>" class='form-control' ></td>
                </tr>
                <tr>
                    <td>Customer Requirement <span class="text-danger">*</span></td>
                    <td><textarea type='text' name='customized_detail' id="customized_detail"  rows="5" class='form-control'><?php echo htmlspecialchars($customized_detail, ENT_QUOTES); ?></textarea></td>
                </tr>
                <tr>
                    <td>Collect Date <span class="text-danger">*</span></td>
                    <td><input type='date' name='customized_collectdate' id="customized_collectdate" value="<?php echo htmlspecialchars($customized_collectdate, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Designer Email <span class="text-danger">*</span></td>
                    <td>
                        <select class='form-select' name='designer_email'> 
                            <option value='' disabled selected>-- Designer Email --</option> 
                            <?php
                            $designerEmailQuery = "SELECT designer_email FROM designer";
                            $designerEmailStmt = $con->prepare($designerEmailQuery);
                            $designerEmailStmt->execute();
                            while ($get_designer = $designerEmailStmt->fetch(PDO::FETCH_ASSOC)) {
                                $result = $designer_email == $get_designer['designer_email'] ? 'selected' : '';
                                echo "<option value = '$get_designer[designer_email]' $result> $get_designer[designer_email] </option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <input type='submit' value='Save Changes' class='actionBtn btn mb-3 mx-2' />
                <?php echo"<a href='customMade_detail.php?customized_id={$customized_id}' class='actionBtn btn mb-3 mx-2'>Back</a>"; ?>
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