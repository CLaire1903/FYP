<?php
session_start();
if (!isset($_SESSION["designer_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/product.css" rel="stylesheet">

    <style>
        #summary {
            font-weight: bold;
        }
        .image{
            width: 75%;
            border: none;
        }
        .quickInfo {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
        .quickInfo:hover {
            box-shadow: 0 4px 8px 0 rgb(255, 255, 255), 0 6px 20px 0 rgb(255, 255, 255);
        }
        .count{
            text-decoration: none;
        }
        .count:hover{
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include '../config/dbase.php';
        include 'navigationBar.php';
        ?>
        <div class="newArrival mx-5">
            <h1 class="header p-2 fw-bold rounded-pill text-center mt-5">NEW ARRIVAL</h1>
            <div class="newArrrivalItems d-flex flex-wrap justify-content-around mx-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Product Image</th>
                            <th>Product ID</th>
                            <th class="col-5">Product Name</th>
                            <th>Product Price</th>
                        </tr>
                    </thead>
                    <tfoot>
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
                                    echo "<tr>";
                                echo "<td><a href='product_detail.php?product_id={$na_product_id}'><img src='$na_product_image' class='productImage d-flex justify-content-center rounded'></a></td>";
                                echo "<td>{$na_product_id}</td>";
                                echo "<td>{$na_product_name}</td>";
                                echo "<td>RM {$na_product_price}</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
        <div class="mostPopular mx-5">
            <h1 class="header p-2 fw-bold rounded-pill text-center mt-5">MOST POPULAR</h1>
            <div class="mostPopularItems d-flex flex-wrap justify-content-around mx-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2 ">Product Image</th>
                            <th>Product ID</th>
                            <th class="col-5">Product Name</th>
                            <th>Product Price</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $mostPopularQuery = "SELECT p.product_id, p.product_image, p.product_name, p.product_price, SUM( od.product_selected ) AS totalOrderedQuantity
                            FROM order_detail od
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
                                echo "<tr>";
                                    echo "<td><a href='product_detail.php?product_id={$mp_product_id}'><img src='$mp_product_image' class='productImage d-flex justify-content-center rounded'></a></td>";
                                    echo "<td>{$mp_product_id}</td>";
                                    echo "<td>{$mp_product_name}</td>";
                                    echo "<td>RM {$mp_product_price}</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>