<!DOCTYPE HTML>
<html>

<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/product.css" rel="stylesheet">

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
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        ?>
        <div class="newArrival">
            <h1 class="text-center mt-5">NEW ARRIVAL</h1>
            <div class="newArrrivalItems d-flex flex-wrap justify-content-around">
                <?php
                    $newArrivalQuery = "SELECT product_id, product_image, product_name, product_price FROM product ORDER BY product_id DESC LIMIT 6";
                    $newArrivalStmt = $con->prepare($newArrivalQuery);
                    $newArrivalStmt->execute();
                    while ($newArrivalRow = $newArrivalStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($newArrivalRow);
                        $na_product_id = $newArrivalRow['product_id'];
                        $na_product_image = $newArrivalRow['product_image'];
                        $na_product_name = ucwords($newArrivalRow['product_name']);
                        $na_product_price = sprintf('%.2f', $newArrivalRow['product_price']);
                        echo "<div class='productDisplay card col-10 col-md-5 col-lg-3 d-flex flex-column justify-content-center my-3 ms-3'>";
                            echo "<a href='product_detail.php?product_id={$na_product_id}'><img src='$na_product_image' class='productImage d-flex justify-content-center rounded-top'></a>";
                            echo "<a href='#' class='productDetailName text-center text-decoration-none'>$na_product_name</a>";
                            echo "<a href='#' class='productDetailPrice text-center text-decoration-none pb-3 rounded-bottom'>RM $na_product_price</a>";
                        echo "</div>";
                    }
                ?>
                </div>
            </div>
        </div>
        <div class="mostPopular">
            <h1 class="text-center mt-5">MOST POPULAR</h1>
            <div class="mostPopularItems d-flex flex-wrap justify-content-around">
                <?php
                    $mostPopularQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price, SUM( od.order_quantity ) AS totalOrderedQuantity
                    FROM orderdetail od
                    INNER JOIN product p
                    WHERE od.product_id = p.product_id
                    GROUP BY od.product_id
                    ORDER BY totalOrderedQuantity DESC
                    LIMIT 6";
                    $mostPopularStmt = $con->prepare($mostPopularQuery);
                    $mostPopularStmt->execute();
                    while ($mostPopularRow = $mostPopularStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($mostPopularRow);
                        $mp_product_id = $mostPopularRow['product_id'];
                        $mp_product_image = $mostPopularRow['product_image'];
                        $mp_product_name = ucwords($mostPopularRow['product_name']);
                        $mp_product_price = sprintf('%.2f', $mostPopularRow['product_price']);
                        echo "<div class='productDisplay card col-10 col-md-5 col-lg-3 d-flex flex-column justify-content-center my-3 ms-3'>";
                            echo "<a href='product_detail.php?product_id={$mp_product_id}'><img src='$mp_product_image' class='productImage d-flex justify-content-center rounded-top'></a>";
                            echo "<a href='#' class='productDetailName text-center text-decoration-none'>$mp_product_name</a>";
                            echo "<a href='#' class='productDetailPrice text-center text-decoration-none pb-3 rounded-bottom'>RM $mp_product_price</a>";
                        echo "</div>";
                    }
                ?>
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