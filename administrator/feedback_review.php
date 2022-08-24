<?php
session_start();
if (!isset($_SESSION["admin_email"])) {
    header("Location: index.php?error=restrictedAccess");
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Feedback Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        #feedback {
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

        <div class="feedbackList mx-5">
            <h1 class="header p-2 text-center mt-5">Feedback Review</h1>
            <div class="feedbackItems d-flex flex-wrap justify-content-around mx-5 mt-5">
                <table class='table table-hover table-responsive table-bordered text-center'>
                    <thead>
                        <tr class="tableHeader">
                            <th class="col-3 col-lg-2">Feedback ID</th>
                            <th>Customer Email</th>
                            <th class="col-8">Feedback</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <?php
                            $feedbackQuery = "SELECT * FROM feedback ORDER BY feedback_id DESC";
                            $feedbackStmt = $con->prepare($feedbackQuery);
                            $feedbackStmt->execute();
                            while ($feedbackRow = $feedbackStmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($feedbackRow);
                                $feedback_id = $feedbackRow['feedback_id'];
                                $cus_email = $feedbackRow['cus_email'];
                                $feedback_detail = ucwords($feedbackRow['feedback_detail']);
                                echo "<tr>";
                                    echo "<td class='col-2'>{$feedback_id}</td>";
                                    echo "<td>{$cus_email}</td>";
                                    echo "<td class='col-2'>RM {$feedback_detail}</td>";
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
    function delete_product(product_id) {
        if (confirm('Do you want to delete this product?')) {
            window.location = 'product_delete.php?product_id=' + product_id;
        }
    }
</script>
</body>

</html>