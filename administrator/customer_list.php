<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        #customer, #customerList {
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

        <div class="customerList mx-5">
            <h1 class="header p-2 text-center mt-5">Customer List</h1>
            <div class="customerItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Email</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $customerQuery = "SELECT * FROM customer ORDER BY cus_email";
                            $customerStmt = $con->prepare($customerQuery);
                            $customerStmt->execute();
                            while ($customerRow = $customerStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($customerRow);
                                $cus_email = $customerRow['cus_email'];
                                $cus_fname = ucfirst($customerRow['cus_fname']);
                                $cus_lname = ucfirst($customerRow['cus_lname']);
                                $cus_address = ucwords($customerRow['cus_address']);
                                $cus_phnumber = $customerRow['cus_phnumber'];
                                echo "<tr>";
                                    echo "<td>{$cus_email}</td>";
                                    echo "<td class='col-2'>{$cus_fname} {$cus_lname}</td>";
                                    echo "<td>{$cus_address}</td>";
                                    echo "<td class='col-2'>{$cus_phnumber}</td>";
                                    echo "<td>";
                                        echo "<div class='d-lg-flex justify-content-sm-center flex-column'>";
                                        echo "<a href='customer_detail.php?cus_email={$cus_email}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                        echo "<a href='customer_update.php?cus_email={$cus_email}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
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

</body>

</html>