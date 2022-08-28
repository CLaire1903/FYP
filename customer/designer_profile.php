<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Designer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/designer.css" rel="stylesheet">

    <style>
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'C:\xampp\htdocs\fyp\config/dbase.php';
        include 'navigationBar.php';

        $designer_email = isset($_GET['designer_email']) ? $_GET['designer_email'] : die('ERROR: Designer record not found.');

        try {
            $designerQuery = "SELECT * FROM designer WHERE designer_email = :designer_email";
            $designerStmt = $con->prepare($designerQuery);
            $designerStmt->bindParam(":designer_email", $designer_email);
            $designerStmt->execute();
            $designerRow = $designerStmt->fetch(PDO::FETCH_ASSOC);

            $designer_fname = ucfirst($designerRow['designer_fname']);
            $designer_lname = ucfirst($designerRow['designer_lname']);
            $designer_gender = $designerRow['designer_gender'];
            $designer_phnumber = $designerRow['designer_phnumber'];
            $designer_qualification = $designerRow['designer_qualification'];

        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        ?>
        <div class="DesignerDetail d-flex flex-column justify-content-center mx-5">
            <h2 class="text-center m-5">About Designer</h2>
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
                <h3>About Designer: </h3>
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
                            <th>Gender</th>
                            <td><?php echo htmlspecialchars($designer_gender, ENT_QUOTES);  ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><?php echo htmlspecialchars($designer_phnumber, ENT_QUOTES);  ?></td>
                        </tr>
                        <tr>
                            <th>Qualification</th>
                            <td><?php echo htmlspecialchars($designer_qualification, ENT_QUOTES);  ?></td>
                        </tr>
                    </thead>
                </table>

                <h3 class="mt-5">Designer's Award:</h3>
                <?php 
                    $checkAwardQuery = "SELECT * FROM award WHERE designer_email = :designer_email";
                    $checkAwardStmt = $con->prepare($checkAwardQuery);
                    $checkAwardStmt->bindParam(":designer_email", $designer_email);
                    $checkAwardStmt->execute();
                    $checkAwardRow = $checkAwardStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($checkAwardRow == 0){
                        echo "<h2>This designer does not win any awards.</h2>";
                    }else {
                        echo "<table class='table table-hover table-responsive table-bordered'>";
                            echo"<thead>";
                                echo"<tr class='tableHeader'>";
                                    echo"<th class='text-center'>Award Name</th>";
                                    echo"<th class='text-center'>Award Year</th>";
                                    echo"<th class='text-center'>Award Country</th>";
                                echo"</tr>";
                            echo"</thead>";

                            echo "<tfoot>";
                            $awardQuery = "SELECT * FROM award WHERE designer_email = :designer_email";
                            $awardStmt = $con->prepare($awardQuery);
                            $awardStmt->bindParam(":designer_email", $designer_email);
                            $awardStmt->execute();
                            while ($awardRow = $awardStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($awardRow);    
                                $award_name = $awardRow['award_name'];
                                $award_year = $awardRow['award_year'];
                                $award_country = $awardRow['award_country']; 
                                echo"<tr>";
                                    echo "<td class='text-center'>{$award_name}</td>";
                                    echo "<td class='text-center'>{$award_year}</td>";
                                    echo "<td class='text-center'>{$award_country}</td>";
                                echo "</tr>";}
                            echo "</tfoot>";
                        echo "</table>";
                    } 
                ?>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?> 
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>