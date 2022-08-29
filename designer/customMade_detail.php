<?php
session_start();
if (!isset($_SESSION["designer_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Custom Made Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">

    <style>
        .customized_image {
            width: 30%;
            height: 30%;
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
        <div class="page-header mt-5">
            <h1 class="text-center">Custom Made Details</h1>
        </div>

        <?php
        $customized_id = isset($_GET['customized_id']) ? $_GET['customized_id'] : die('ERROR: Custom Made record not found.');

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'customizedUpdateFail') {
            echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                <div>
                    Custom made detail fail to update.
                </div>
                </div>";
        }
        if ($action == 'customizedUpdated') {
            echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                    <div>
                        Custom made detail updated successfully.
                    </div>
                </div>";
        }

        try {
            $getCustomizedQuery = "SELECT * FROM customized WHERE customized_id=:customized_id";
            $getCustomizedStmt = $con->prepare($getCustomizedQuery);
            $getCustomizedStmt->bindParam(":customized_id", $customized_id);
            $getCustomizedStmt->execute();
            $getCustomizedRow = $getCustomizedStmt->fetch(PDO::FETCH_ASSOC);

            $customized_image = $getCustomizedRow['customized_image'];
            $customized_id = $getCustomizedRow['customized_id'];
            $cus_email = $getCustomizedRow['cus_email'];
            $cus_name = $getCustomizedRow['cus_name'];
            $cus_phnumber = $getCustomizedRow['cus_phnumber'];
            $customized_detail = $getCustomizedRow['customized_detail'];
            $customized_collectdate = $getCustomizedRow['customized_collectdate'];
            $designer_email = $getCustomizedRow['designer_email'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="mx-5">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="col-3">Custom Made ID</td>
                    <td><?php echo htmlspecialchars($customized_id, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customized Image</td>
                    <td>
                        <?php
                        echo "<div class='img-block'> ";
                        if ($customized_image != "") {
                            echo "<img src= $customized_image alt='' class='customized_image'/> ";
                        } else {
                            echo "No picture uploaded.";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Customer Email</td>
                    <td><?php echo htmlspecialchars($cus_email, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customer Name</td>
                    <td><?php echo htmlspecialchars($cus_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><?php echo htmlspecialchars($cus_phnumber, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Customer Requirement</td>
                    <td><?php echo htmlspecialchars($customized_detail, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Collect Date</td>
                    <td><?php echo htmlspecialchars($customized_collectdate, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Designer Email</td>
                    <td>
                        <?php 
                            if ($designer_email == "") {
                                echo "No Designer is assigned.";
                            } else {
                                echo htmlspecialchars($designer_email, ENT_QUOTES); 
                            }
                        ?>
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center">
                <?php
                echo "<a href='customMade_update.php?customized_id=$customized_id' class='actionBtn updateBtn btn mb-3 mx-2'>Update</a>";
                
                echo "<a href='customMade_list.php' class='actionBtn btn mb-3 mx-2'>Back</a>";
                ?>
            </div>
        </div>
        
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>