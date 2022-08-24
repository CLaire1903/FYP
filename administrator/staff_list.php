<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        #staff, #staffList {
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

        <div class="staffList mx-5">
            <h1 class="header p-2 text-center mt-5">Staff List</h1>
            <?php 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                if ($action == 'updateOwn') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Please update your detail at the profile page.
                        </div>
                    </div>";
                }  
                if ($action == 'staffCreatedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            New staff could not created.
                        </div>
                    </div>";
                }   
                if ($action == 'staffCreated') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            New staff created successfully.
                        </div>
                    </div>";
                }
                if ($action == 'director') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Sorry, you cannot delete the director.
                        </div>
                    </div>";
                }
                if ($action == 'deletedOwn') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Sorry, you cannot delete yourself.
                        </div>
                    </div>";
                }
                if ($action == 'deletedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            Staff could not deleted.
                        </div>
                    </div>";
                }
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            Staff deleted successfully.
                        </div>
                    </div>";
                }
            ?>
            <div class="staff d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Email</th>
                            <th class="col-2">Name</th>
                            <th class="col-2">Address</th>
                            <th class="col-2">Gender</th>
                            <th class="col-1 col-lg-4">Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $staffQuery = "SELECT * FROM admin ORDER BY admin_email ASC";
                            $staffStmt = $con->prepare($staffQuery);
                            $staffStmt->execute();
                            while ($staffRow = $staffStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($staffRow);
                                $admin_email = $staffRow['admin_email'];
                                $admin_fname = $staffRow['admin_fname'];
                                $admin_lname = $staffRow['admin_lname'];
                                $admin_address = $staffRow['admin_address'];
                                $admin_gender = $staffRow['admin_gender'];
                                echo "<tr>";
                                    echo "<td class='col-2'>{$admin_email}</td>";
                                    echo "<td>{$admin_fname} {$admin_lname}</td>";
                                    echo "<td class='col-2'>{$admin_address}</td>";
                                    echo "<td class='col-2'>{$admin_gender}</td>";
                                    echo "<td>";
                                        echo "<div class='d-lg-flex justify-content-sm-center flex-row'>";
                                            echo "<a href='staff_detail.php?admin_email={$admin_email}' id='detail' class='listActionBtn btn m-1 m-lg-2'>Detail</a>";
                                            echo "<a href='staff_update.php?admin_email={$admin_email}' id='update' class='listActionBtn btn m-1 m-lg-2'>Update</a>";
                                            echo "<a href='#' onclick='delete_staff(&#39;$admin_email&#39;);' id='delete' class='listActionBtn btn m-1 m-lg-2'>Delete</a>";
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
    function delete_staff(admin_email) {
        if (confirm('Do you want to delete this staff?')) {
            window.location = 'staff_delete.php?admin_email=' + admin_email;
        }
    }
</script>
</body>

</html>