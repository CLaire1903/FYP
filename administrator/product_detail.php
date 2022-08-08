<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">

    <style>
        .product_image {
            width: 30%;
            height: 30%;
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
        <div class="page-header mt-5">
            <h1 class="text-center">Product Details</h1>
        </div>

        <?php
        $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : die('ERROR: Product record not found.');

        try {
            $getProductQuery = "SELECT product_image, product_id, product_name, product_price, p.category_id, c.category_name, designer_email, product_condition
                                FROM product p
                                INNER JOIN category c
                                ON p.category_id = c.category_id
                                WHERE product_id = :product_id ";
            $getProductStmt = $con->prepare($getProductQuery);
            $getProductStmt->bindParam(":product_id", $product_id);
            $getProductStmt->execute();
            $getProductRow = $getProductStmt->fetch(PDO::FETCH_ASSOC);

            $product_image = $getProductRow['product_image'];
            $product_id = $getProductRow['product_id'];
            $product_name = $getProductRow['product_name'];
            $product_price = $getProductRow['product_price'];
            $category_id = $getProductRow['category_id'];
            $category_name = $getProductRow['category_name'];
            $designer_email = $getProductRow['designer_email'];
            $product_condition = $getProductRow['product_condition'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="mx-5">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-3">Product ID</td>
                    <td><?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product Picture</td>
                    <td>
                        <?php
                        echo "<div class='img-block'> ";
                        if ($product_image != "") {
                            echo "<img src= $product_image alt='' class='product_image'/> ";
                        } else {
                            echo "No picture uploaded.";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Product Name</td>
                    <td><?php echo htmlspecialchars($product_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product Price</td>
                    <td><?php echo htmlspecialchars($product_price, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Category Name</td>
                    <td><?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Designer Email</td>
                    <td><?php echo htmlspecialchars($designer_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product Condition</td>
                    <td><?php echo htmlspecialchars($product_condition, ENT_QUOTES);  ?></td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <?php
                echo "<a href='product_update.php?product_id=$product_id' class='actionBtn updateBtn btn mb-3 mx-2'>Update Product</a>";
                
                echo "<a href='product_list.php' class='actionBtn btn mb-3 mx-2'>Back</a>";
                ?>
            </div>
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