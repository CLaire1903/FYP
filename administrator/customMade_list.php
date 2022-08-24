<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Custom Made Order list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/product.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        /*can be found at navigation page*/
        #customMadeOrder {
            font-weight: bold;
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

        <div class="customMadeList mx-5">
            <h1 class="header p-2 text-center mt-5">Custom Made Order List</h1>
            <div class="customMadeItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Image</th>
                            <th class="col-2">Custom Made ID</th>
                            <th class="col-2">Customer</th>
                            <th class="col-2">Contact</th>
                            <th class="col-2">Collect Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $customMadeQuery = "SELECT * FROM customized ORDER BY customized_id DESC";
                            $customMadeStmt = $con->prepare($customMadeQuery);
                            $customMadeStmt->execute();
                            while ($customMadeRow = $customMadeStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($customMadeRow);
                                $customized_image = $customMadeRow['customized_image'];
                                $customized_id = $customMadeRow['customized_id'];
                                $cus_name = $customMadeRow['cus_name'];
                                $cus_phnumber = $customMadeRow['cus_phnumber'];
                                $customized_collectdate = $customMadeRow['customized_collectdate'];
                                echo "<tr>";
                                    echo "<td><img src='$customized_image' class='productImage d-flex justify-content-center rounded'></a></td>";
                                    echo "<td>{$customized_id}</td>";
                                    echo "<td>{$cus_name}</td>";
                                    echo "<td>{$cus_phnumber}</td>";
                                    echo "<td>{$customized_collectdate}</td>";
                                    echo "<td class='col-1'>";
                                        echo "<div class='d-lg-flex justify-content-center flex-column'>";
                                        echo "<a href='customMade_detail.php?customized_id={$customized_id}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                        echo "<a href='customMade_update.php?customized_id={$customized_id}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>