<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Staff Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">
    <link href="../css/profile.css" rel="stylesheet">
</head>

<body>
    <div class="container-flex">
        <?php
        include '../config/dbase.php';
        include '../alertIcon.php';
        include 'navigationBar.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        if ($action == 'detailUpdatedFail') {
            echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                <div>
                Staff detail could not updated.
                </div>
            </div>";
        }
        if ($action == 'detailUpdated') {
            echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                <div>
                    Staff detail updated successfully.
                </div>
            </div>";
        }

        $admin_email = isset($_GET['admin_email']) ? $_GET['admin_email'] : die('ERROR: Admin record not found.');
        try {
            $adminQuery = "SELECT * FROM admin WHERE admin_email = :admin_email";
            $adminStmt = $con->prepare($adminQuery);
            $adminStmt->bindParam(":admin_email", $admin_email);
            $adminStmt->execute();
            $adminRow = $adminStmt->fetch(PDO::FETCH_ASSOC);

            $admin_email = $adminRow['admin_email'];
            $admin_pword = $adminRow['admin_pword'];
            $admin_fname = ucfirst($adminRow['admin_fname']);
            $admin_lname = ucfirst($adminRow['admin_lname']);
            $admin_address = ucwords($adminRow['admin_address']);
            $admin_phnumber = $adminRow['admin_phnumber'];
            $admin_gender = $adminRow['admin_gender'];
            $admin_bday = $adminRow['admin_bday'];
            $admin_position = $adminRow['admin_position'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <div class="page-header mx-5 mt-5">
            <h1>Staff Detail</h1>
        </div>
        <div class="mx-5">
            <table class='table table-hover table-responsive table-bordered'>
                <thead>
                    <tr>
                        <th class="title col-4">Email</th>
                        <td><?php echo htmlspecialchars($admin_email, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><?php echo htmlspecialchars($admin_pword, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo htmlspecialchars($admin_fname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><?php echo htmlspecialchars($admin_lname, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr class="border">
                        <th class="d-flex align-self-center border-0">Address</th>
                        <td><?php echo htmlspecialchars($admin_address, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($admin_phnumber, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($admin_gender, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Date Of Birth</th>
                        <td><?php echo htmlspecialchars($admin_bday, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <th>Position</th>
                        <td><?php echo htmlspecialchars($admin_position, ENT_QUOTES);  ?></td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            <?php
                echo "<a href='staff_update.php?admin_email={$admin_email}' class='actionBtn btn mx-2 mt-5'>Update Profile</a>";
            ?>
            <a href='staff_list.php' class='actionBtn btn mt-5 mx-2'>Back</a>
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