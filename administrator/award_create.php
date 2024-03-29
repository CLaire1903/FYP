<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Award</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">

    <style>
        #designer, #award {
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
        <div class="faq d-flex flex-column justify-content-center">
            <h1 class="text-center mt-5">Add Award</h1>
            <div class="d-flex justify-content-center">
                <div class="col-10">
                    <?php 
                        if ($_POST){
                            try {
                                if (empty($_POST['designer_email']) || empty($_POST['award_name']) || empty($_POST['award_year']) || empty($_POST['award_country'])) {
                                    throw new Exception("Please make sure all fields are not empty!");
                                }
                
                                $awardQuery = "INSERT INTO award SET designer_email=:designer_email, award_name=:award_name, award_year=:award_year, award_country=:award_country";
                                $awardStmt = $con->prepare($awardQuery);
                                $designer_email = ucfirst($_POST['designer_email']);
                                $award_name = ucfirst($_POST['award_name']);
                                $award_year = ucfirst($_POST['award_year']);
                                $award_country = ucfirst($_POST['award_country']);
                
                                $awardStmt->bindParam(':designer_email', $designer_email);
                                $awardStmt->bindParam(':award_name', $award_name);
                                $awardStmt->bindParam(':award_year', $award_year);
                                $awardStmt->bindParam(':award_country', $award_country);
                                if ($awardStmt->execute()) {
                                    echo "<script>window.location.href='designer_detail.php?designer_email='+ '$designer_email' + '&action=awardAdded';</script>";
                                } else {
                                    echo "<script>window.location.href='designer_detail.php?designer_email='+ '$designer_email' + '&action=awardAddedFail';</script>";
                                }
                            } catch (PDOException $exception) {
                                die('ERROR: ' . $exception->getMessage());
                            } catch (Exception $exception) {
                                echo "<div class='alert alert-danger d-flex align-items-center my-5' role='alert'>
                                        <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                        <div>
                                        " . $exception->getMessage() . "
                                        </div>
                                    </div>";
                            } 
                        }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Designer :  <span class="text-danger">*</span></h5>
                            <td class="border-0">
                                <div>
                                    <?php 
                                        if (isset($_GET['designer_email'])) {
                                            $get_email = isset($_GET['designer_email']) ? $_GET['designer_email'] : die('ERROR: Order record not found.');
                                            $designerQuery = "SELECT designer_email FROM designer WHERE designer_email=:designer_email";
                                            $designerStmt = $con->prepare($designerQuery);
                                            $designerStmt->bindParam(":designer_email", $get_email);
                                            $designerStmt->execute();
                                            while ($designerRow = $designerStmt->fetch(PDO::FETCH_ASSOC)) {
                                                $designer_email = $designerRow['designer_email'];
                                                $designer_email = htmlspecialchars($designer_email, ENT_QUOTES);
                                                echo "<select class='form-select' name='designer_email' id='designer_email'>";
                                                echo "<option value='' disabled selected>-- Select Designer --</option>";
                                                $selectDesignerQuery = 'SELECT designer_email FROM designer';
                                                $selectDesignerStmt = $con->prepare($selectDesignerQuery);
                                                $selectDesignerStmt->execute();
                                                while ($get_designer = $selectDesignerStmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $result = $designer_email == $get_designer['designer_email'] ? 'selected' : '';
                                                    echo "<option value = '$get_designer[designer_email]' $result> $get_designer[designer_email] </option>";
                                                }
                                                ?>
                                            </select>
                                        <?php } 
                                        } else { ?>
                                            <select class="form-select" name="designer_email" id="designer_email">
                                                <option value='' disabled selected>-- Select Designer --</option>
                                                <?php
                                                $selectDesignerQuery = "SELECT designer_email FROM designer";
                                                $selectDesignerStmt = $con->prepare($selectDesignerQuery);
                                                $selectDesignerStmt->execute();
                                                while ($designer_email = $selectDesignerStmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option value = '$designer_email[designer_email]'> $designer_email[designer_email] </option>";
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                </div>
                            </td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Name :  <span class="text-danger">*</span></h5>
                            <td><input type='text' name='award_name' id="award_name" value="<?php echo (isset($_POST['award_name'])) ? $_POST['award_name'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Year :  <span class="text-danger">*</span></h5>
                            <td><input type='text' name='award_year' id="award_year" value="<?php echo (isset($_POST['award_year'])) ? $_POST['award_year'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Country :  <span class="text-danger">*</span></h5>
                            <td><input type='text' name='award_country' id="award_country" value="<?php echo (isset($_POST['award_country'])) ? $_POST['award_country'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="button d-grid m-3 d-flex justify-content-center">
                            <button type='submit' class='actionBtn btn m-3'>Submit</button>
                        </div>
                    </form>
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