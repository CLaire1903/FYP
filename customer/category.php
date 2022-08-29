<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Product Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/category.css" rel="stylesheet">

    <style>
        #category {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include '../config/dbase.php';
        include 'navigationBar.php';
        $categoryQuery = "SELECT category_id, category_image, category_name FROM category";
        $categoryStmt = $con->prepare($categoryQuery); 
        $categoryStmt->execute();
        ?>
        <div class="category">
            <h1 class="text-center my-5">Product Category</h1>
            <div class="categoryItems d-flex flex-wrap justify-content-around">
                <?php
                    while ($categoryRow = $categoryStmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($categoryRow);
                        echo "<div class='categoryDisplay card col-10 col-md-5 col-lg-3 d-flex flex-column justify-content-center my-3 ms-3 '>";
                            $category_image = $categoryRow['category_image'];
                            echo "<a href='category_detail.php?category_id={$category_id}'><img src='$category_image' class='categoryImage d-flex justify-content-center rounded-top'></a>";
                            echo "<a href='category_detail.php?category_id={$category_id}' class='categoryName text-center text-decoration-none rounded-bottom'>$category_name</a>";
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