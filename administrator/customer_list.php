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
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/list.css" rel="stylesheet">

    <style>
        #customer, #customerList {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
            <?php 
                include '../config/dbase.php';
                include '../alertIcon.php';
                include 'navigationBar.php';
                $where = "";
                if ($_POST) {
                    try {
                        if (empty($_POST['search'])) {
                            throw new Exception("Please insert customer email or customer name to search!");
                        }
        
                        $search = "%" . $_POST['search'] . "%";
                        $where = "WHERE cus_email LIKE :search OR cus_fname LIKE :search OR cus_lname LIKE :search";
                    } catch (PDOException $exception) {
                        //for databae 'PDO'
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

        <div class="customerList mx-5">
            <h1 class="header p-2 text-center my-5 rounded-pill">Customer List</h1>
            <div class="mx-5">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                    <table class='search table table-hover table-responsive'>
                        <tr class='search'>
                            <td class="search col-11"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder="Customer Email or Customer Name" class='form-control'></td>
                            <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn' /></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="customerItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <?php
                    $customerQuery = "SELECT * FROM customer $where ORDER BY cus_email";
                    $customerStmt = $con->prepare($customerQuery);
                    if ($_POST) $customerStmt->bindParam(':search', $search);
                    $customerStmt->execute();
                    $num = $customerStmt->rowCount();
                    if ($num > 0) { ?>
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
                        <?php while ($customerRow = $customerStmt->fetch(PDO::FETCH_ASSOC)) {
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
                    } else {
                        echo "<div class='alert alert-danger d-flex col-12' role='alert'>
                                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                No Customer found.
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
<script>
    function validation() {
            var search = document.getElementById("search").value;
            var flag = false;
            var msg = "";
            if (search == "") {
                flag = true;
                msg = msg + "Please insert customer email or customer name to search!\r\n";
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