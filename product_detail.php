<!DOCTYPE HTML>
<html>

<head>
    <title>Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">

    <style>
    </style>

</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'navigationBar.php';
        include 'config/dbase.php';
        ?>

        <?php
            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : die('ERROR: Product record not found.');

            try {
                $productQuery = "SELECT * FROM product WHERE product_id = :product_id ";
                $productStmt = $con->prepare($productQuery);
                $productStmt->bindParam(":product_id", $product_id);
                $productStmt->execute();
                $productRow = $productStmt->fetch(PDO::FETCH_ASSOC);

                $product_pic = $productRow['product_image'];
                $productID = $productRow['product_id'];
                $product_name = $productRow['product_name'];
                $product_price = $productRow['product_price'];
                $product_category = $productRow['product_category'];
                $designer_username = $productRow['desginer_username'];
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <div>
                <div class="productImageSide">
                    <?php
                        $product_image = $newArrivalRow['product_image'];
                        
                    ?>
                </div>
                <div class="productDetailSide">

                </div>
            </div>

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <th>Product ID</th>
                    <td><?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <th>Product Picture</th>
                    <td>
                        <?php
                        echo "<div class='img-block'> ";
                        if ($product_pic != "") {
                            echo "<img src= $product_pic alt='' class='product_image'/> ";
                        } else {
                            echo "No picture uploaded.";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?></td>
                </tr>
            </table>

        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>