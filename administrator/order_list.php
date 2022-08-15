<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Order list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        /*can be found at navigation page*/
        #orderList {
            font-weight: bold;
            font-size: large;
        }
        .newOrder {
            color: red;
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
        <?php
        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Order was deleted.</div>";
        }?>

        <div class="orderList mx-5">
            <h1 class="header p-2 text-center mt-5">Order List</h1>
            <div class="orderItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Order ID</th>
                            <th>Order Date and Time</th>
                            <th>Customer</th>
                            <th>Order Status</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $orderQuery = "SELECT * FROM orders ORDER BY order_id DESC";
                            $orderStmt = $con->prepare($orderQuery);
                            $orderStmt->execute();
                            while ($orderRow = $orderStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($orderRow);
                                $order_id = $orderRow['order_id'];
                                $order_datentime = $orderRow['order_datentime'];
                                $cus_email = $orderRow['cus_email'];
                                $order_status = $orderRow['order_status'];
                                $order_totalamount = sprintf('%.2f', $orderRow['order_totalamount']);
                                echo "<tr>";
                                    echo "<td>{$order_id}</td>";
                                    echo "<td>{$order_datentime}</td>";
                                    echo "<td>{$cus_email}</td>";
                                    if ($order_status == "new order") {
                                        echo "<td class='newOrder'>{$order_status}</td>";
                                    } else {
                                        echo "<td>{$order_status}</td>";
                                    }
                                    
                                    echo "<td>RM {$order_totalamount}</td>";
                                    echo "<td class='col-1'>";
                                        echo "<div class='d-lg-flex justify-content-center flex-column'>";
                                        echo "<a href='order_detail.php?order_id={$order_id}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                        echo "<a href='order_update.php?order_id={$order_id}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
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
    <script type='text/javascript'>
        function delete_order(orderID) {
            if (confirm('Are you sure?')) {
                window.location = 'order_delete.php?orderID=' + orderID;
            }
        }

        function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please input order ID or customer username to search!\r\n";
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