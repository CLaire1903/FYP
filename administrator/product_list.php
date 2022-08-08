<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/product.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        #product, #productList {
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
                include 'C:\xampp\htdocs\fyp\config/dbase.php';
                include 'C:\xampp\htdocs\fyp\alertIcon.php';
                include 'navigationBar.php';
            ?>

        <div class="productList mx-5">
            <h1 class="header p-2 text-center mt-5">Product List</h1>
            <?php 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                if ($action == 'productInStock') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Product could not be deleted as it involved in order.
                        </div>
                    </div>";
                }
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            Product deleted successfully.
                        </div>
                    </div>";
                }
            ?>
            <div class="productItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Product Image</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $productQuery = "SELECT product_id, product_image, product_name, product_price FROM product ORDER BY product_id DESC";
                            $productStmt = $con->prepare($productQuery);
                            $productStmt->execute();
                            while ($productRow = $productStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($productRow);
                                $product_id = $productRow['product_id'];
                                $product_image = $productRow['product_image'];
                                $product_name = ucwords($productRow['product_name']);
                                $product_price = sprintf('%.2f', $productRow['product_price']);
                                    echo "<tr>";
                                echo "<td><img src='$product_image' class='productImage d-flex justify-content-center rounded'></a></td>";
                                echo "<td class='col-2'>{$product_id}</td>";
                                echo "<td>{$product_name}</td>";
                                echo "<td class='col-2'>RM {$product_price}</td>";
                                echo "<td>";
                                    echo "<div class='d-lg-flex justify-content-sm-center flex-column'>";
                                    echo "<a href='product_detail.php?product_id={$product_id}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                    echo "<a href='product_update.php?productID={$product_id}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
                                    echo "<a href='#' onclick='delete_product({$product_id});' id='delete' class='listActionBtn btn m-1 m-lg-2'>Delete</a>";
                                    echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
        
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script type='text/javascript'>
    function delete_product(product_id) {
        if (confirm('Do you want to delete this product?')) {
            window.location = 'product_delete.php?product_id=' + product_id;
        }
    }
</script>
</body>

</html>