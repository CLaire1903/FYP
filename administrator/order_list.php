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
        #order, #orderList {
            font-weight: bold;
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

        <div class="orderList mx-5">
            <h1 class="header p-2 text-center my-5 rounded-pill">Order List</h1>
            <?php $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'updateFail') {
                echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        Order fail to update.
                    </div>
                    </div>";
            }
            if ($action == 'updated') {
                echo "<div class='alert alert-success d-flex align-items-center mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            Order updated successfully.
                        </div>
                    </div>";
            }
            $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'noOrder') {
                echo "<div class='alert alert-danger d-flex align-items-center mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        Order fail to create.
                    </div>
                    </div>";
            }
            if ($action == 'ordered') {
                echo "<div class='alert alert-success d-flex align-items-center mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            New Order created.
                        </div>
                    </div>";
            }
            $where = "";
            if ($_POST) {
                try {
                    if (empty($_POST['search'])) {
                        throw new Exception("Please insert order ID or customer email to search!");
                    }
    
                    $search = "%" . $_POST['search'] . "%";
                    $where = "WHERE order_id LIKE :search OR cus_email LIKE :search";
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
                            <td class="search col-11"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder="Order ID or Customer Email" class='form-control'></td>
                            <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn' /></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="orderItems d-flex flex-wrap justify-content-around mt-5 mx-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <?php
                        $orderQuery = "SELECT * FROM orders $where ORDER BY order_id DESC";
                        $orderStmt = $con->prepare($orderQuery);
                        if ($_POST) $orderStmt->bindParam(':search', $search);
                        $orderStmt->execute();
                        $num = $orderStmt->rowCount();
                        if ($num > 0) { ?>
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
                        <?php while ($orderRow = $orderStmt->fetch(PDO::FETCH_ASSOC)) {
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
                    } else {
                        echo "<div class='alert alert-danger d-flex col-12' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                No order found.
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type='text/javascript'>
        function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please insert order ID or customer email to search!\r\n";
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