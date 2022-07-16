<!DOCTYPE HTML>
<html>

<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">

    <style>
        #home {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'navigationBar.php';
        include 'config/dbase.php';
        $newArrivalQuery = "SELECT product_id, product_image, product_name, product_price FROM product ORDER BY product_id DESC LIMIT 6";
        $newArrivalStmt = $con->prepare($newArrivalQuery);
        $newArrivalStmt->execute();
        ?>
        <div class="newArrival">
            <h1 class="text-center mt-5">NEW ARRIVAL</h1>
            <div class="newArrrivalItems d-flex flex-wrap justify-content-around">
                <?php
                    while ($newArrivalRow = $newArrivalStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($newArrivalRow);
                        echo "<div class='productDisplay card col-10 col-md-5 col-lg-3 d-flex flex-column justify-content-center my-3 ms-3'>";
                            $product_image = $newArrivalRow['product_image'];
                            echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center'></a>";
                            echo "<a href='#' class='productDetailName text-center text-decoration-none'>$product_name</a>";
                            $product_price = sprintf('%.2f', $newArrivalRow['product_price']);
                            echo "<a href='#' class='productDetailPrice text-center text-decoration-none pb-3'>RM $product_price</a>";
                        echo "</div>";
                    }
                ?>
                </div>
            </div>
        </div>
        <div class="mostPopular">
            <h1 class="text-center mt-5">MOST POPULAR</h1>
            <div class="newArrrivalItems d-flex flex-wrap justify-content-around">
                <?php
                    /*while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<div class='productDisplay card col-3 d-flex flex-column justify-content-center my-3 ms-3'>";
                            $product_image = $row['product_image'];
                            echo "<a href='product_detail.php?product_id={$product_id}'><img src='$product_image' class='productImage d-flex justify-content-center'></a>";
                            echo "<a href='#' class='productDetailName text-center text-decoration-none'>$product_name</a>";
                            $product_price = sprintf('%.2f', $row['product_price']);
                            echo "<a href='#' class='productDetailPrice text-center text-decoration-none pb-3'>RM $product_price</a>";
                        echo "</div>";
                    }
                */?>
                </div>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>