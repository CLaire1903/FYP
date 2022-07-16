<?php
session_start();
if (!isset($_SESSION["cus_username"])) {
    header("Location: customer_login.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/profile.css" rel="stylesheet">
</head>

<body>
    <div class="container-flex">
        <?php
        include 'navigationBar.php';
        ?>
        <div class="page-header m-3">
            <h1>My Profile</h1>
        </div>

        <?php
        include 'config/dbase.php';

        try {
            $customerQuery = "SELECT * FROM customer WHERE cus_username = :cus_username";
            $customerStmt = $con->prepare($customerQuery);
            $customerStmt->bindParam(":cus_username", $_SESSION['cus_username']);
            $customerStmt->execute();
            $customerRow = $customerStmt->fetch(PDO::FETCH_ASSOC);

            $cus_username = $customerRow['cus_username'];
            $cus_fname = ucfirst($customerRow['cus_fname']);
            $cus_lname = ucfirst($customerRow['cus_lname']);
            $cus_address = ucwords($customerRow['cus_address']);
            $cus_gender = $customerRow['cus_gender'];
            $cus_bday = $customerRow['cus_bday'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="m-3">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td class="title col-4">Username</td>
                    <td><?php echo htmlspecialchars($cus_username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><?php echo htmlspecialchars($cus_fname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo htmlspecialchars($cus_lname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?php echo htmlspecialchars($cus_address, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($cus_gender, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td><?php echo htmlspecialchars($cus_bday, ENT_QUOTES);  ?></td>
                </tr>
            </table>
        </div>

        <div>
            <h1 class="m-3">Order History</h1>
        </div>
        
        <div class="d-flex justify-content-center">
            <?php
            echo "<a href='customer_updateProfile.php?cus_username={$cus_username}' class='actionBtn updateBtn btn mx-2 mt-3'>Update Profile</a>";
            ?>
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