<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Homework - Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href='/fyp/css/shared.css' rel="stylesheet">
    <link href='/fyp/css/update.css' rel="stylesheet">

    <style>
        .product_image {
            width:200px; 
            height:200px;
        }
        #form-popup {
            display: none;
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

        <div class="mx-5">
            <div class="page-header">
                <h1 class="text-center mt-5">Update Product</h1>
            </div>
            <?php
            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : die('ERROR: Product record not found.');
            try {
                //get the detail of the product from products table in database
                $getProductQuery = "SELECT * FROM product WHERE product_id = :product_id  ";
                $getProductStmt = $con->prepare($getProductQuery);
                $getProductStmt->bindParam(":product_id", $product_id);
                $getProductStmt->execute();
                $getProductRow = $getProductStmt->fetch(PDO::FETCH_ASSOC);
                $product_id = $getProductRow['product_id'];
                $product_image = $getProductRow['product_image'];
                $product_name = $getProductRow['product_name'];
                $product_price = $getProductRow['product_price'];
                $category_id = $getProductRow['category_id'];
                $designer_email = $getProductRow['designer_email'];
                $product_condition = $getProductRow['product_condition'];
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            if ($_POST) {

                $file = $_FILES["product_image"]["name"];
                $temp = $_FILES["product_image"]["tmp_name"];
                $folder = "../image/product/" . $file;
                $default = "../image/product/default.jpg";
                $changeImageName = explode(".", $_FILES["product_image"]["name"]);
                $newfilename = 'ID' . $product_id . '_' . round(microtime(true)) . '.' . end($changeImageName);
                $latest_file = "../image/product/" . $newfilename;
                $isUploadOK = 1;

                if ($file != "") {
                    unlink($product_image);
                }

                try {
                    //check all input field is not empty except image field
                    if (empty($_POST['product_name']) || empty($_POST['product_price']) || empty($_POST['category_id']) || empty($_POST['designer_email']) || empty($_POST['product_condition'])) {
                        throw new Exception("Please make sure all fields are not empty!");
                    }
                    //make sure the price and promo price is number
                    if (!is_numeric($_POST['product_price'])) {
                        throw new Exception("Please make sure the price is a number");
                    }
                    if ($_POST['product_price'] <= 0) {
                        throw new Exception("Please make sure the price must not be a negative value or zero!");
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
                        if ($_FILES["product_image"]["size"] > 512000) {
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

                    //update the selected productID's detail
                    $updateProductQuery = "UPDATE product SET product_image=:product_image, product_name=:product_name, product_price=:product_price, category_id=:category_id, designer_email=:designer_email, product_condition=:product_condition WHERE product_id = :product_id";
                    $updateProductStmt = $con->prepare($updateProductQuery);
                    $product_name = htmlspecialchars(strip_tags(ucfirst($_POST['product_name'])));
                    $product_price = htmlspecialchars(strip_tags(ucfirst($_POST['product_price'])));
                    $category_id = htmlspecialchars(strip_tags(ucfirst($_POST['category_id'])));
                    $designer_email = htmlspecialchars(strip_tags($_POST['designer_email']));
                    $product_condition = htmlspecialchars(strip_tags($_POST['product_condition']));

                    $updateProductStmt->bindParam(':product_id', $product_id);
                    if ($file != "") {
                        $product_image = htmlspecialchars(strip_tags($latest_file));
                        $updateProductStmt->bindParam(':product_image', $latest_file);
                    } else {
                        $product_image = htmlspecialchars(strip_tags($product_image));
                        $updateProductStmt->bindParam(':product_image', $product_image);
                    }
                    $updateProductStmt->bindParam(':product_name', $product_name);
                    $updateProductStmt->bindParam(':product_price', $product_price);
                    $updateProductStmt->bindParam(':category_id', $category_id);
                    $updateProductStmt->bindParam(':designer_email', $designer_email);
                    $updateProductStmt->bindParam(':product_condition', $product_condition);
                    if ($updateProductStmt->execute()) {
                        if ($folder != "") {
                            if ($isUploadOK == 0) {
                                echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                        Sorry, your file was not uploaded.
                                    </div>
                                </div>";
                            } else {
                                move_uploaded_file($temp, '../image/product/' . $newfilename);
                            }
                        }
                        echo "<div class='alert alert-success d-flex align-items-center mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                            <div>
                                Product $product_id was updated.
                            </div>
                        </div>";
                    } else {
                        echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                Unable to update product $product_id. Please try again.
                            </div>
                        </div>";
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            " . $exception->getMessage() . "
                        </div>
                    </div>";
                }
            } ?>
            
            <h6 class="text-danger mt-5"> NOTE! Please refresh if you do not see any changes. </h6>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?product_id={$product_id}"); ?>" onsubmit="return validation()" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td class="col-5">Product ID</td>
                        <td><?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <td>Product Image</td>
                        <td>
                            <div>
                                <div class='img-block m-2 d-flex'> 
                                    <div id="productImage">
                                        <img src=<?php echo htmlspecialchars($product_image, ENT_QUOTES); ?> alt='' class='product_image'/>
                                    </div>
                                    <div  id="deleteImage" class="d-flex flex-column justify-content-between">
                                    </div>
                                </div>
                                <button type="button" class="changePic btn m-2 p-1" onclick="openForm()">Change Image</button>
                                
                                <div id='form-popup'>
                                    <div class="d-flex">
                                        <input type='file' name='product_image' id="product_image" class='form-control' />
                                        <button type="button" class="cancelBtn btn mx-2 p-1" onclick="closeForm()">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Product Name <span class="text-danger">*</span></td>
                        <td><input type='text' name='product_name' id="product_name" value="<?php echo htmlspecialchars($product_name, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product Price <span class="text-danger">*</span></td>
                        <td><input type='text' name='product_price' id="product_price" value="<?php echo htmlspecialchars($product_price, ENT_QUOTES); ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product Category <span class="text-danger">*</span></td>
                        <td>
                            <select class='form-select' name='category_id'> 
                                <option value='' disabled selected>-- Category --</option> 
                                <?php
                                $categoryIdQuery = "SELECT category_id, category_name FROM category";
                                $categoryIdStmt = $con->prepare($categoryIdQuery);
                                $categoryIdStmt->execute();
                                while ($get_category = $categoryIdStmt->fetch(PDO::FETCH_ASSOC)) {
                                    $result = $category_id == $get_category['category_id'] ? 'selected' : '';
                                    echo "<option value = '$get_category[category_id]' $result> $get_category[category_name] </option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Product's Designer <span class="text-danger">*</span></td>
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
                    <tr>
                        <td>Product Condition</td>
                        <td>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" class="product_condition" value="available" <?php echo ($product_condition == 'available') ? 'checked' : '' ?>>
                                    Available
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" class="product_condition" value="rented" <?php echo ($product_condition == 'rented') ? 'checked' : '' ?>>
                                    rented
                                    <span class="select"></span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label>
                                    <input type="radio" name="product_condition" class="product_condition" value="sold" <?php echo ($product_condition == 'sold') ? 'checked' : '' ?>>
                                    Sold
                                    <span class="select"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="d-flex justify-content-center">
                    <input type='submit' value='Update'class='actionBtn btn mb-3 mx-2' />
                    <a href='product_list.php' class='actionBtn btn mb-3 mx-2'>Back to Product List</a>
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

        function openForm() {
            document.getElementById("form-popup").style.display = "block";
        }

        function closeForm() {
            document.getElementById("form-popup").style.display = "none";
        }
    </script>

</body>

</html>