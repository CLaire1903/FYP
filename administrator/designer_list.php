<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Designer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/list.css" rel="stylesheet">

    <style>
        #designer, #designerList {
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
            ?>

        <div class="designerList mx-5">
            <h1 class="header p-2 text-center my-5 rounded-pill">Designer List</h1>
            
            <?php 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                if ($action == 'createdFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            New designer could not created successfully.
                        </div>
                    </div>";
                }
                if ($action == 'created') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            New designer created successfully.
                        </div>
                    </div>";
                }
                if ($action == 'deletedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Designer could not deleted.
                        </div>
                    </div>";
                }
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            Designer deleted successfully.
                        </div>
                    </div>";
                }
                $where = "";
                if ($_POST) {
                    try {
                        if (empty($_POST['search'])) {
                            throw new Exception("Please insert designer email or designer name to search!");
                        }
        
                        $search = "%" . $_POST['search'] . "%";
                        $where = "WHERE designer_email LIKE :search OR designer_fname LIKE :search OR designer_lname LIKE :search";
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
                            <td class="search col-11"><input type='text' name='search' id="search" onkeyup="myFunction()" placeholder="Designer Email or Designer Name" class='form-control'></td>
                            <td class="search"><input type='submit' value='Search' id="searchBtn" class='btn' /></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="designer d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                        <?php
                            $designerQuery = "SELECT * FROM designer $where ORDER BY designer_email ASC";
                            $designerStmt = $con->prepare($designerQuery);
                            if ($_POST) $designerStmt->bindParam(':search', $search);
                            $designerStmt->execute();
                            $num = $designerStmt->rowCount();
                            if ($num > 0) { ?>
                            <thead>
                                <tr class="tableHeader">
                                    <th class="col-3 col-lg-2">Email</th>
                                    <th class="col-2">Name</th>
                                    <th class="col-2">Phone Number</th>
                                    <th class="col-2">Gender</th>
                                    <th class="col-1 col-lg-4">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                            <?php while ($designerRow = $designerStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($designerRow);
                                $designer_email = $designerRow['designer_email'];
                                $designer_fname = $designerRow['designer_fname'];
                                $designer_lname = $designerRow['designer_lname'];
                                $designer_phnumber = $designerRow['designer_phnumber'];
                                $designer_gender = $designerRow['designer_gender'];
                                echo "<tr>";
                                    echo "<td class='col-2'>{$designer_email}</td>";
                                    echo "<td>{$designer_fname} {$designer_lname}</td>";
                                    echo "<td class='col-2'>{$designer_phnumber}</td>";
                                    echo "<td class='col-2'>{$designer_gender}</td>";
                                    echo "<td>";
                                        echo "<div class='d-lg-flex justify-content-sm-center flex-row'>";
                                            echo "<a href='designer_detail.php?designer_email={$designer_email}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                            echo "<a href='designer_update.php?designer_email={$designer_email}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
                                            echo "<a href='#' onclick='delete_designer(&#39;$designer_email&#39;);' id='delete' class='listActionBtn btn m-1 m-lg-2'>Delete</a>";
                                        echo "</div>";
                                    echo "</td>";
                                echo "</tr>";
                                }
                            } else {
                                echo "<div class='alert alert-danger d-flex col-12' role='alert'>
                                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                        No designer found.
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
    function delete_designer(designer_email) {
        if (confirm('Do you want to delete this designer?')) {
            window.location = 'designer_delete.php?designer_email=' + designer_email;
        }
    }
    function validation() {
        var search = document.getElementById("search").value;
        var flag = false;
        var msg = "";
        if (search == "") {
            flag = true;
            msg = msg + "Please insert designer email or designer name to search!\r\n";
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