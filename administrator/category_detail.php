<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Category Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/product.css" rel="stylesheet">

    <style>
        #category {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'navigationBar.php';
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Category record not found.');
        ?>

        <div class="category">
            <?php
                $categoryQuery = "SELECT category_id, category_name, category_description FROM category WHERE category_id = $category_id";
                $categoryStmt = $con->prepare($categoryQuery); 
                $categoryStmt->execute();
                $categoryRow = $categoryStmt->fetch(PDO::FETCH_ASSOC);
                $category_name = ucwords($categoryRow ['category_name']);
                $category_description = $categoryRow ['category_description'];
                echo "<h1 class='text-center mt-5'>$category_name</h1>";
                echo "<div class='d-flex justify-content-center'>";
                echo "<div class='col-10 m-3'>";
                echo "<p class='text-center p-3'>$category_description</p>";
                echo "</div>";
                echo "</div>";
            ?>
            <div class="categoryItems d-flex flex-wrap justify-content-around">
                <?php
                    $categoryDetailQuery = "SELECT product_id, product_image, product_name, product_price FROM product WHERE category_id = $category_id ORDER BY product_id DESC";
                    $categoryDetailStmt = $con->prepare($categoryDetailQuery); 
                    $categoryDetailStmt->execute();
                    while ($categoryDetailRow = $categoryDetailStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($categoryDetailRow);
                        $product_id = $categoryDetailRow['product_id'];
                        $product_image = $categoryDetailRow['product_image'];
                        $product_name = ucwords($categoryDetailRow['product_name']);
                        $product_price = sprintf('%.2f', $categoryDetailRow['product_price']);

                        echo "<div class='productDisplay card col-10 col-md-5 col-lg-3 d-flex flex-column justify-content-center my-3 ms-3'>";
                            echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center rounded-top'></a>";
                            echo "<a href='#' class='productDetailName text-center text-decoration-none'>$product_name</a>";
                            echo "<a href='#' class='productDetailPrice text-center text-decoration-none pb-3 rounded-bottom'>RM $product_price</a>";
                        echo "</div>";
                    }
                ?>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-5">
            <?php
                echo "<a href='product_addNewProduct.php?category_id={$category_id}' id='create' class='actionBtn btn mx-2'>Create New Product</a>";
            ?>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>