<!DOCTYPE HTML>
<html>

<head>
    <title>Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">

    <style>
        .detail {
            color: black;
            text-decoration: none;
        }

        .detail:hover{
            color: #ff7474;
            text-decoration: underline;
        }

        .addToCartBtn{
            background-color: #f7A8AE;
            width: 300px;
            height: 50px;
            border: none;
        }

        .addToCartBtn:hover{
            background-color: #f7A8AE;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="container-fluid p-0">
        <?php 
            include 'navigationBar.php';
            include "alertIcon.php";

            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : die('ERROR: Product record not found.');

            include 'config/dbase.php';

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'productExist') {
                $product_name = isset($_GET['product_name']) ? $_GET['product_name'] :  die('ERROR: Product name not found.');
                echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        $product_name already exist in the cart.
                    </div>
                    </div>";
            }
            if ($action == 'productAdded') {
                $product_name = isset($_GET['product_name']) ? $_GET['product_name'] :  die('ERROR: Product name not found.');
                echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            $product_name added into cart successfully.
                        </div>
                    </div>";
            }
            try {
                $productQuery = "SELECT product_image, product_name, product_price, p.category_id, p.designer_email, c.category_name 
                                FROM product p 
                                INNER JOIN category c
                                ON p.category_id = c.category_id
                                WHERE product_id = :product_id";
                $productStmt = $con->prepare($productQuery);
                $productStmt->bindParam(":product_id", $product_id);
                $productStmt->execute();
                $productRow = $productStmt->fetch(PDO::FETCH_ASSOC);

                $product_image = $productRow['product_image'];
                $product_name = $productRow['product_name'];
                $product_price = sprintf('%.2f', $productRow['product_price']);
                $category_id = $productRow['category_id'];
                $category_name = $productRow['category_name'];
                $designer_email = $productRow['designer_email'];

            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        ?>

            <div class="d-flex flex-column flex-md-row justify-content-around">
                <div class="productImageSide mx-5 mt-5 col-10 col-md-5">
                    <?php
                        $product_image = $productRow['product_image'];
                        echo "<img src='$product_image' class='productImage'>";
                    ?>
                </div>
                <div class="productDetailSide text-center mx-5 mt-5 col-10 col-md-5 d-flex flex-column align-self-center">
                    <div>
                        <h1><?php echo $product_name ?></h1>
                        <p><?php echo $product_id ?></p>
                        <h5 class="my-4 py-2">Designer - <a href='designer_detail.php?designer_email=<?php echo $designer_email ?>' class='detail'><?php echo $designer_email ?></a></h5>
                        <h3 class="my-4 py-2"><a href='category_detail.php?category_id=<?php echo $category_id ?>' class='detail text-center'><?php echo $category_name?></a></h3>
                        <h4 class="my-4 py-2">RM <?php echo $product_price?></h4>
                    </div>
                    <div class="d-flex justify-content-center">
                        <?php
                        echo "<a href='#' onclick='addToCart({$product_id});' class='addToCartBtn btn btn-lg mb-3 mx-2 d-flex justify-content-center'>Add To Cart</a>";
                        ?>
                    </div>
                </div>
            </div>

        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script type='text/javascript'>
    function addToCart(product_id) {
        if (confirm('Add this product into your shopping cart?')) {
            window.location = "process_addToCart.php?product_id=" + product_id;
        }
    }
</script>
</body>

</html>