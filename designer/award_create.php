<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create Award</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">

    <style>
        #designer, #award {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'C:\xampp\htdocs\fyp\config\dbase.php';
        include 'navigationBar.php';
        $designer_email = isset($_GET['designer_email']) ? $_GET['designer_email'] : die('ERROR: designer record not found.');
        ?>
        <?php 
            if ($_POST){
                try {
                    if (empty($_POST['award_name']) || empty($_POST['award_year']) || empty($_POST['award_country'])) {
                        throw new Exception("Please make sure all fields are not empty!");
                    }
    
                    $awardQuery = "INSERT INTO award SET designer_email=:designer_email, award_name=:award_name, award_year=:award_year, award_country=:award_country";
                    $awardStmt = $con->prepare($awardQuery);
                    $award_name = ucfirst($_POST['award_name']);
                    $award_year = ucfirst($_POST['award_year']);
                    $award_country = ucfirst($_POST['award_country']);
    
                    $awardStmt->bindParam(':designer_email', $designer_email);
                    $awardStmt->bindParam(':award_name', $award_name);
                    $awardStmt->bindParam(':award_year', $award_year);
                    $awardStmt->bindParam(':award_country', $award_country);
                    if ($awardStmt->execute()) {
                        echo "<script>window.location.href='designer_profile.php?designer_email='+ '$designer_email' + '&action=awardAdded';</script>";
                    } else {
                        echo "<script>window.location.href='designer_profile.php?designer_email='+ '$designer_email' + '&action=awardAddedFail';</script>";
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                } catch (Exception $exception) {
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                            " . $exception->getMessage() . "
                            </div>
                        </div>";
                } 
            }
        ?>
        <div class="faq d-flex flex-column justify-content-center">
            <h1 class="text-center mt-5">Add Award</h1>
            <div class="d-flex justify-content-center">
                <div class="col-10">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?designer_email={$designer_email}"); ?>" method="post">
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Name : </h5>
                            <td><input type='text' name='award_name' id="award_name" value="<?php echo (isset($_POST['award_name'])) ? $_POST['award_name'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Year : </h5>
                            <td><input type='text' name='award_year' id="award_year" value="<?php echo (isset($_POST['award_year'])) ? $_POST['award_year'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Award Country : </h5>
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