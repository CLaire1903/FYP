<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Add New Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">

    <style>
        #product, #addProduct {
            font-weight: bold;
        }
    </style>
    
</head>

<body>
    <div class="container-fluid p-0">
        <?php
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'C:\xampp\htdocs\fyp\alertIcon.php';
        include 'navigationBar.php';
        ?>
        <div class="page-header mx-5">
            <h1 class="p-1 mt-5 text-center">Create New Product</h1>
            <h6 class="text-danger mt-5"> Please complete the form below with * completely. </h6>
        <?php
            if ($_POST) {
                $filename = $_FILES["product_image"]["name"];
                $tempname = $_FILES["product_image"]["tmp_name"];
                $folder = "../image/product/" . $filename;
                $isUploadOK = 1;

                try {
                    if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['category_id']) ||  empty($_POST['designer_email']) ||  empty($_POST['product_condition'])) {
                        throw new Exception("Please make sure all fields are not empty!");
                    }
                    if (!is_numeric($_POST['product_price'])) {
                        throw new Exception("Please make sure the price is a number!");
                    }
                    if ($_POST['product_price'] <= 0) {
                        throw new Exception("Please make sure the price must not be a negative value or zero!");
                    }

                    if ($filename != "") {

                        $imageFileType = strtolower(pathinfo($folder, PATHINFO_EXTENSION));
                        $check = getimagesize($tempname);
                        //make sure user uploaded image only
                        if ($check == 0) {
                            $isUploadOK = 0;
                            throw new Exception("Please upload image ONLY! (JPG, JPEG, PNG & GIF)");
                        }

                        //make sure the image is 1:1
                        list($width, $height, $type, $attr) = getimagesize($tempname);
                        if ($width != $height) {
                            $isUploadOK = 0;
                            throw new Exception("Please make sure the ratio of the photo is 1:1.");
                        }

                        //make sure the size is lower than 512KB
                        if ($_FILES["product_image"]["size"] > 512000) {
                            $isUploadOK = 0;
                            throw new Exception("Sorry, your photo is too large. Only 512KB is allowed!");
                        }

                        //check image file type
                        if (
                            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif"
                        ) {
                            $isUploadOK = 0;
                            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF photo are allowed.");
                        }
                    }

                    //prepare to insert new product into database
                    $addProductQuery = "INSERT INTO product SET product_name=:product_name, product_price=:product_price, category_id=:category_id, designer_email=:designer_email, product_condition=:product_condition";
                    $addProductStmt = $con->prepare($addProductQuery);
                    $product_name = ucfirst($_POST['product_name']);
                    $product_price = $_POST['product_price'];
                    $category_id = $_POST['category_id'];
                    $designer_email = $_POST['designer_email'];
                    $product_condition = $_POST['product_condition'];
                    $addProductStmt->bindParam(':product_name', $product_name);
                    $addProductStmt->bindParam(':product_price', $product_price);
                    $addProductStmt->bindParam(':category_id', $category_id);
                    $addProductStmt->bindParam(':designer_email', $designer_email);
                    $addProductStmt->bindParam(':product_condition', $product_condition);
                    if ($addProductStmt->execute()) {
                        //get last inserted productID
                        $A_incrementID = $con->lastInsertId();
                        $changePhotoName = explode(".", $_FILES["product_image"]["name"]);
                        $newfilename = 'ID' . $A_incrementID . '_' . round(microtime(true)) . '.' . end($changePhotoName);
                        $latest_file = "../image/product/" . $newfilename;
                        if ($folder != "") {
                            //insert photo with latest name into database
                            $insertPicQuery = "UPDATE product SET product_image=:product_image WHERE product_id = :product_id";
                            $insertPicStmt = $con->prepare($insertPicQuery);
                            $insertPicStmt->bindParam(':product_id', $A_incrementID);
                            if ($filename != "") {
                                $insertPicStmt->bindParam(':product_image', $latest_file);
                            }
                            if ($insertPicStmt->execute()) {
                                if ($isUploadOK == 0) {
                                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                        <?php echo Sorry, your file was not uploaded.?>
                                    </div>";
                                } else {
                                    (move_uploaded_file($tempname, '../image/product/' . $newfilename));
                                }
                            }
                        }
                        echo "<div class='alert alert-success d-flex align-items-center' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                            <div>
                                Product $A_incrementID was created.
                            </div>
                        </div>";
                    } else {
                        throw new Exception("Product is not created.");
                    }
                } catch (PDOException $exception) {
                    //for databae 'PDO'
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Product Image</td>
                        <td><input type='file' name='product_image' id="product_image" value="<?php echo (isset($_FILES["product"]["name"])) ? $_FILES["product"]["name"] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='product_name' id="product_name" value="<?php echo (isset($_POST['product_name'])) ? $_POST['product_name'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product Price <span class="text-danger">*</span></td>
                        <td><input type='text' name='product_price' id="product_price" value="<?php echo (isset($_POST['product_price'])) ? $_POST['product_price'] : ''; ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Category Name <span class="text-danger">*</span></td>
                        <td>
                            <select class="form-select" name="category_id" id="category_id">
                                <option value='' disabled selected>-- Select Category --</option>
                                <?php
                                $categoryIdQuery = "SELECT category_id, category_name FROM category";
                                $categoryIdStmt = $con->prepare($categoryIdQuery);
                                $categoryIdStmt->execute();
                                while ($category_id = $categoryIdStmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$category_id[category_id]'> $category_id[category_name] </option>";
                                    
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Designer Email <span class="text-danger">*</span></td>
                        <td>
                            <select class="form-select" name="designer_email" id="designer_email">
                                <option value='' disabled selected>-- Select Designer --</option>
                                <?php
                                $designerEmailQuery = "SELECT designer_email FROM designer";
                                $designerEmailStmt = $con->prepare($designerEmailQuery);
                                $designerEmailStmt->execute();
                                while ($designer_email = $designerEmailStmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value = '$designer_email[designer_email]'> $designer_email[designer_email] </option>";
                                    
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Product Condition <span class="text-danger">*</span></td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" value="available" 
                                    <?php
                                    if(isset($_POST['product_condition'])){
                                        echo $_POST['product_condition'] == "available" ? 'checked' : '';
                                    }
                                    ?>>
                                    Available
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" value="rented" 
                                    <?php
                                    if(isset($_POST['product_condition'])){
                                        echo $_POST['product_condition'] == "rented" ? 'checked' : '';
                                    }
                                    ?>>
                                    Rented
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" value="sold" 
                                    <?php
                                    if(isset($_POST['product_condition'])){
                                        echo $_POST['product_condition'] == "sold" ? 'checked' : '';
                                    }
                                    ?>>
                                    Sold
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Add Product' class='actionBtn btn mb-3 mx-2' />
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
    <script>
        function validation() {
            var name = document.getElementById("name").value;
            var name_malay = document.getElementById("name_malay").value;
            var description = document.getElementById("description").value;
            var price = document.getElementById("price").value;
            var promotion_price = document.getElementById("promotion_price").value;
            var priceValidation = /^[0-9]*[.-]?[0-9]*$/;
            var manufacture_date = document.getElementById("manufacture_date").value;
            var expired_date = document.getElementById("expired_date").value;
            var flag = false;
            var msg = "";
            if (name == "" || name_malay == "" || description == "" || price == "" || promotion_price == "" || manufacture_date == "" || expired_date == "") {
                flag = true;
                msg = msg + "Please make sure all fields are not empty! (product picture is optional)\r\n";
            }
            if (price.match(priceValidation)) {} else {
                flag = true;
                msg = msg + "Please make sure the price is a number!\r\n";
            }
            if (promotion_price.match(priceValidation)) {} else {
                flag = true;
                msg = msg + "Please make sure the promotion price is a number!\r\n";
            }
            if (parseFloat(price) <= 0 || parseFloat(promotion_price) <= 0) {
                flag = true;
                msg = msg + "Please make sure the price and promotion price must not be a negative value or zero!\r\n";
            }
            if (parseFloat(price) > 1000 || parseFloat(promotion_price) > 1000) {
                flag = true;
                msg = msg + "Please make sure the price and promotion price is not bigger than RM 1000!\r\n";
            }
            if (parseFloat(promotion_price) > parseFloat(price)) {
                flag = true;
                msg = msg + "Promotion price cannot bigger than normal price!\r\n";
            }
            if (manufacture_date > expired_date) {
                flag = true;
                msg = msg + "Please make sure expired date is late than the manufacture date!\r\n";
            }
            if (flag == true) {
                alert(msg);
                return false;
            } else {
                return true;
            }
        }
    </script>

</body>

</html>