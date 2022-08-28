<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Designer Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/designer.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">
</head>

<body>
    <div class="container-flex">
        <?php
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'C:\xampp\htdocs\fyp\alertIcon.php';
        include 'navigationBar.php';

        $get_designer = isset($_GET['designer_email']) ? $_GET['designer_email'] : die('ERROR: designer record not found.');

        $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'profileUpdateFail') {
                echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        Profile fail to update.
                    </div>
                    </div>";
            }
            if ($action == 'profileUpdated') {
                echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                        <div>
                            Profile updated successfully.
                        </div>
                    </div>";
            }
            if ($action == 'awardAddedFail') {
                echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        New award could not added.
                    </div>
                </div>";
            }   
            if ($action == 'awardAdded') {
                echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                    <div>
                        New award added successfully.
                    </div>
                </div>";
            }
            if ($action == 'DeletedFail') {
                echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>
                        Award could not delete.
                    </div>
                </div>";
            }   
            if ($action == 'deleted') {
                echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                    <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                    <div>
                        Award deleted successfully.
                    </div>
                </div>";
            }

        try {
            $designerQuery = "SELECT * FROM designer WHERE designer_email = :designer_email";
            $designerStmt = $con->prepare($designerQuery);
            $designerStmt->bindParam(":designer_email", $get_designer);
            $designerStmt->execute();
            $designerRow = $designerStmt->fetch(PDO::FETCH_ASSOC);

            $designer_image = $designerRow['designer_image'];
            $designer_email = $designerRow['designer_email'];
            $designer_fname = ucfirst($designerRow['designer_fname']);
            $designer_lname = ucfirst($designerRow['designer_lname']);
            $designer_phnumber = $designerRow['designer_phnumber'];
            $designer_gender = $designerRow['designer_gender'];
            $designer_qualification = $designerRow['designer_qualification'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="page-header mx-5 mt-5">
            <h1 class="text-center">Designer Detail</h1>
        </div>
        <div class='img-block d-flex justify-content-center mb-5'> 
                <?php $designer_image = $designerRow['designer_image'];
                if ($designer_image != "") {
                    echo "<img src= $designer_image alt='' class='designerImage image-responsive rounded-circle'/> ";
                } else {
                    echo "No picture uploaded.";
                }
                ?>
            </div>
        <div class="mx-5">
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <th class="title col-4">Email</th>
                        <td><?php echo htmlspecialchars($designer_email, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo htmlspecialchars($designer_fname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?php echo htmlspecialchars($designer_lname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($designer_phnumber, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($designer_gender, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Qualification</th>
                        <td><?php echo htmlspecialchars($designer_qualification, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
        </div>

        <div>
            <h1 class="mx-5 mt-5 text-center">Designer Awards</h1>
            <?php 
                $checkAwardQuery = "SELECT * FROM award WHERE designer_email = :designer_email";
                $checkAwardStmt = $con->prepare($checkAwardQuery);
                $checkAwardStmt->bindParam(":designer_email", $get_designer);
                $checkAwardStmt->execute();
                $checkAwardRow = $checkAwardStmt->fetch(PDO::FETCH_ASSOC);
                if ($checkAwardRow == 0){
                    echo "<h2 class='mx-5 mt-3'>Sorry, you do not win any award currently.</h2>";
                }else {
                    echo "<div class='mx-5'>";
                        echo "<table class='table table-hover table-responsive table-bordered text-center'>";
                            echo "<thead>";
                                echo "<tr class='tableHeader'>";
                                    echo "<th>Name</th>";
                                    echo "<th>Year</th>";
                                    echo "<th>Country</th>";
                                    echo "<th class='col-1'>Action</th>";
                                echo "</tr>";
                            echo "</thead>";

                        $awardQuery = "SELECT * FROM award WHERE designer_email = :designer_email ORDER BY award_year DESC ";
                        $awardStmt = $con->prepare($awardQuery);
                        $awardStmt->bindParam(":designer_email", $get_designer);
                        $awardStmt->execute();
                        while ($awardRow = $awardStmt->fetch(PDO::FETCH_ASSOC)) {  
                            extract($awardRow);  
                            $award_name = $awardRow['award_name'];
                            $award_year = $awardRow['award_year'];
                            $award_country = $awardRow['award_country'];
                            echo "<tr>";
                                echo "<td>{$award_name}</td>";
                                echo "<td>{$award_year}</td>";
                                echo "<td>{$award_country}</td>";
                                echo "<td><a href='#' onclick='delete_award(&#39;$designer_email&#39;, &#39;$award_name&#39;, &#39;$award_year&#39;);' id='delete' class='listActionBtn btn m-1'>Delete</a></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    echo "</div>";
                }
            ?>
        </div>
        <div class="d-flex justify-content-center">
            <?php
                echo "<a href='designer_update.php?designer_email={$get_designer}' class='actionBtn btn mx-2 mt-5'>Update Profile</a>";
                echo "<a href='award_create.php?designer_email={$get_designer}' class='actionBtn btn mx-2 mt-5'>Update Award</a>";
                echo "<a href='designer_list.php' class='actionBtn btn mx-2 mt-5'>Back</a>";
            ?>
        </div>
        <div class="footer bg-dark">
            <?php
            include 'footer.php';
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type='text/javascript'>
    function delete_award(designer_email, award_name, award_year) {
        if (confirm('Do you want to delete this award?')) {
            window.location = 'award_delete.php?designer_email=' + designer_email + "&award_name=" + award_name + "&award_year=" + award_year;
        }
    }
    </script>
</body>

</html>