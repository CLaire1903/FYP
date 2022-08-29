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
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/product.css" rel="stylesheet">
    <link href="../css/list.css" rel="stylesheet">

    <style>
        #product, #productList {
            font-weight: bold;
        }
        .image{
            width: 75%;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
            <?php 
                include '../config/dbase.php';
                include '../alertIcon.php';
                include 'navigationBar.php';
            ?>

        <div class="productList mx-5">
            <h1 class="header p-2 text-center my-5 rounded-pill">Product List</h1>
            <?php 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                if ($action == 'productCreatedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            New product could not created successfully.
                        </div>
                    </div>";
                }
                if ($action == 'productCreated') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            New product created successfully.
                        </div>
                    </div>";
                }
                if ($action == 'productInStock') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Product could not be deleted as it is added into order / checkout / cart.
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
                $where = "";
                if ($_POST) {
                    try {
                        if (empty($_POST['search'])) {
                            throw new Exception("Please insert product ID or product name to search!");
                        }
        
                        $search = "%" . $_POST['search'] . "%";
                        $where = "WHERE product_id LIKE :search OR product_name LIKE :search";
                    } catch (PDOException $exception) {
                        echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                " . $exception->getMessage() . "
                            </div>
                        </div>";
                    } catch (Exception $exception) {
                        echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                " . $exception->getMessage() . "
                            </div>
                        </div>";
                    }
                }
            ?>
            <div class="mx-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                    <table class='search table table-hover table-responsive'>
                        <tr class='search'>
                            <td class="search col-11"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder="Product ID or Product Name" class='form-control'></td>
                            <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn' /></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="productItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <?php
                        $productQuery = "SELECT product_id, product_image, product_name, product_price FROM product $where ORDER BY product_id DESC";
                        $productStmt = $con->prepare($productQuery);
                        if ($_POST) $productStmt->bindParam(':search', $search);
                        $productStmt->execute();
                        $num = $productStmt->rowCount();
                        if ($num > 0) { ?>
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
                        <?php while ($productRow = $productStmt->fetch(PDO::FETCH_ASSOC)) {
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
                                        echo "<a href='product_update.php?product_id={$product_id}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
                                        echo "<a href='#' onclick='delete_product({$product_id});' id='delete' class='listActionBtn btn m-1 m-lg-2'>Delete</a>";
                                        echo "</div>";
                                    echo "</td>";
                                echo "</tr>";
                        }
                    } else {
                        echo "<div class='alert alert-danger d-flex col-12' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                No product found.
                            </div>
                        </div>";
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
    function validation() {
        var search = document.getElementById("search").value;
        var flag = false;
        var msg = "";
        if (search == "") {
            flag = true;
            msg = msg + "Please insert product ID or product name to search!\r\n";
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