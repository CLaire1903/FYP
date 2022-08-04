<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Custom Made</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">

    <style>
        #customMade{
            font-weight: bold;
        }
        .customMadeForm {
            background-color: #ffe1e1;
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
        <div class="aboutUs d-flex flex-column justify-content-center">
            <h1 class="text-center mt-5">Custom Made Form</h1>
            <div class="customMadeForm d-flex justify-content-center mt-5 mx-5 border rounded-3">
                <div class="customizedForm col-10">
                    <div class="p-2 mx-auto">
                        <h4 class="instruction mt-3 text-center">Please fill in the details. </h4>
                        <?php
                        if ($_POST) {
                            try {
                                if (empty($_POST['cus_email']) || empty($_POST['cus_name']) || empty($_POST['cus_phnumber']) || empty($_POST['customized_detail']) || empty($_POST['customized_collectdate'])) {
                                    throw new Exception("Make sure all fields are not empty");
                                }

                                $today = strtotime(date("Y-m-d"));
                                $customized_collectDate = strtotime($_POST['customized_collectdate']);
                                $year1 = date('Y', $today);
                                $year2 = date('Y', $customized_collectDate);
                                $month1 = date('m', $today);
                                $month2 = date('m', $customized_collectDate);
                                $checkTimeRange = (($year2 - $year1) * 12) + ($month2 - $month1);
                                if ($checkTimeRange < 6) {
                                    throw new Exception("Custom made gown time range must be more than 6 month.");
                                }

                                $customizedQuery = "INSERT INTO customized SET cus_email=:cus_email, cus_name=:cus_name,cus_phnumber=:cus_phnumber, customized_detail=:customized_detail, customized_collectdate=:customized_collectdate";
                                $customizedStmt = $con->prepare($customizedQuery);
                                $cus_email = strtolower($_POST['cus_email']);
                                $cus_name = $_POST['cus_name'];
                                $cus_phnumber = $_POST['cus_phnumber'];
                                $customized_detail = $_POST['customized_detail'];
                                $customized_collectdate = $_POST['customized_collectdate'];
                                $customizedStmt->bindParam(':cus_email', $cus_email);
                                $customizedStmt->bindParam(':cus_name', $cus_name);
                                $customizedStmt->bindParam(':cus_phnumber', $cus_phnumber);
                                $customizedStmt->bindParam(':customized_detail', $customized_detail);
                                $customizedStmt->bindParam(':customized_collectdate', $customized_collectdate);
                                if ($customizedStmt->execute()) {
                                        echo "<div class='alert alert-success d-flex align-items-center' role='alert'>
                                        <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                                        <div>
                                            Custom made order sent successfully.
                                        </div>
                                        </div>";
                                    } else {
                                        throw new Exception("Unable to save record.");
                                    }
                                } catch (PDOException $exception) {
                                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                        " . $exception->getMessage() . "
                                    </div>
                                    </div>";
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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validation()" method="post">
                            <div class="message">
                                <h5 class="mt-4 mb-0">Email : </h5>
                                <td><input type='text' name='cus_email' id="cus_email" value="<?php echo (isset($_POST['cus_email'])) ? $_POST['cus_email'] : ''; ?>" class='form-control' /></td>
                            </div>
                            <div class="message">
                                <h5 class="mt-4 mb-0">Name : </h5>
                                <td><input type='text' name='cus_name' id="cus_name" value="<?php echo (isset($_POST['cus_name'])) ? $_POST['cus_name'] : ''; ?>" class='form-control'></td>
                            </div>
                            <div class="message">
                                <h5 class="mt-4 mb-0">Phone Number : </h5>
                                <td><input type="tel" name="cus_phnumber" id="cus_phnumber" placeholder="012-3456789 or 011-23456789" pattern="[0-9]{3}-[0-9]{7,8}" value="<?php echo (isset($_POST['cus_phnumber'])) ? $_POST['cus_phnumber'] : ''; ?>" class='form-control' ></td>
                            </div>
                            <div class="message">
                                <h5 class="mt-4 mb-0">Customized Details : </h5>
                                <td><textarea type='text' name='customized_detail' id="customized_detail" class='form-control' rows="5"><?php echo (isset($_POST['customized_detail'])) ? $_POST['customized_detail'] : ''; ?></textarea></td>
                            </div>
                            <div class="message">
                                <h5 class="mt-4 mb-0">Date To Collect : </h5>
                                <td><input type='date' name='customized_collectdate' id="customized_collectdate"  value="<?php echo (isset($_POST['customized_collectdate'])) ? $_POST['customized_collectdate'] : ''; ?>" class='form-control' /></td>
                            </div>
                            <div class="button d-grid m-3 d-flex justify-content-center">
                                <button type='submit' class='actionBtn btn btn-lg m-3'>Submit</button>
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