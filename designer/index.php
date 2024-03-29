<!DOCTYPE HTML>
<html>

<head>
    <title>Designer - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
</head>

<style>
    #logo img {
        width: 300px;
        height: 130px;
    }
    .login {
        height:100%;
        width:100%;
        position:fixed;
        background-color: #ffeaea; 
        background-size: contain; 
    }
    .loginForm {
        background-color: white;
    }
    .loginBtn, .registerBtn{
        background-color: #ffeaea;
    }
    #registerAcc {
        color: #ff7474;
    }
    .registerIns {
        font-size: 13px;
    }
</style>

<body>
    <div class="login container-fluid d-flex justify-content-center">
        <div class="loginForm d-flex justify-content-center flex-column m-5 border-3 col-10 col-md-6 rounded-3">
            <?php
            session_start();
            include '../config/dbase.php';
            include '../alertIcon.php';
            if (isset($_GET['error']) && $_GET['error'] == "restrictedAccess") {
                $errorMessage = "Please login for further proceed!";
            }
            if ($_POST) {
                try {
                    $designer_email = $_POST['designer_email'];
                    $designerQuery = "SELECT * FROM designer WHERE designer_email= :designer_email";
                    $designerStmt = $con->prepare($designerQuery);
                    $designer_pword = $_POST['designer_pword'];
                    $designerStmt->bindParam(':designer_email', $designer_email);
                    $designerStmt->execute();
                    $designerRow = $designerStmt->fetch(PDO::FETCH_ASSOC);
                    if (empty($_POST['designer_email']) || empty($_POST['designer_pword'])) {
                        throw new Exception("Make sure all fields are not empty");
                    }
                    if ($designerRow['designer_email'] != $designer_email) {
                        throw new Exception("Email does not exist!");
                    }
                    if ($designerRow['designer_pword'] != $designer_pword) {
                        throw new exception("Password incorrect!");
                    }
                    $_SESSION['designer_email'] = $designerRow['designer_email'];
                    header("Location: summary.php");
                } catch (PDOException $exception) {
                    $errorMessage = $exception->getMessage();
                } catch (Exception $exception) {
                    $errorMessage = $exception->getMessage();
                }
            }
            ?>
            <div class="loginDetail p-2 mx-auto col-lg-7">
                <div id="logo" class="d-flex justify-content-center ">
                    <img src="../image/logo/mm-logoB.png"></a>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2 class="instruction mt-3 text-center">Designer Login</h2>
                    <?php
                    if (isset($errorMessage)) { ?>
                        <div class='alert alert-danger d-flex align-items-center' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                            <div>
                                <?php echo $errorMessage ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="email mt-3 input-group-lg">
                        <input type="text" class="form-control" id="designer_email" name="designer_email" placeholder="Email" value="<?php echo (isset($_POST['designer_email'])) ? $_POST['designer_email'] : ''; ?>">
                    </div>
                    <div class="password mb-2 input-group-lg">
                        <input type="password" class="form-control" id="designer_pword" name="designer_pword" placeholder="Password" value="<?php echo (isset($_POST['designer_pword'])) ? $_POST['designer_pword'] : ''; ?>">
                    </div>
                    <div class="button d-grid">
                        <button type='submit' class='loginBtn btn btn-large btn-block'>Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>