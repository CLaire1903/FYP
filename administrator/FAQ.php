<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Frequently Asked Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="/fyp/css/shared.css" rel="stylesheet">
    <link href="/fyp/css/list.css" rel="stylesheet">

    <style>
        #FAQ {
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
        
        <div class="faq d-flex flex-column justify-content-center">
            <h1 class="text-center mt-5">Frequently Asked Question</h1>
            <?php 
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                if ($action == 'addedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            FAQ fail to added.
                        </div>
                        </div>";
                }
                if ($action == 'added') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                            <div>
                                FAQ added successfully.
                            </div>
                        </div>";
                }
                if ($action == 'deletedFail') {
                    echo "<div class='alert alert-danger d-flex align-items-center mx-5 mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                        <div>
                            FAQ fail to delete.
                        </div>
                        </div>";
                }
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success d-flex align-items-center mx-5 mt-5' role='alert'>
                            <svg class='alerticon me-2' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg>
                            <div>
                                FAQ deleted successfully.
                            </div>
                        </div>";
                }
            ?>
            <div>
                <?php
                $getFAQ_Query = "SELECT * FROM faq";
                $getFAQ_Stmt = $con->prepare($getFAQ_Query); 
                $getFAQ_Stmt->execute();
                while ($getFAQ_Row = $getFAQ_Stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($getFAQ_Row);
                    $faq_id = $getFAQ_Row['faq_id'];
                    $faq_question = ucfirst($getFAQ_Row['faq_question']);
                    $faq_answer = ucfirst($getFAQ_Row['faq_answer']);
                    echo "<div class='d-flex border mb-5 mx-5'>";
                        echo "<div class='section col-10'>";
                            echo "<div class='question p-3 d-flex'>";
                                echo "<div class='instruction mx-3 col-2'>";
                                    echo "<h6 class='text-center'>Question</h3>";
                                echo "</div>";
                                echo "<div class='detail'>";
                                    echo "<h5 class='text-center'>$faq_question</h3>";
                                echo "</div>";
                            echo "</div>";
                            echo "<div class='answer'>";
                                echo "<div class='p-3 d-flex'>";
                                    echo "<div class='instruction mx-3 col-2'>";
                                        echo "<h6 class='text-center'>Answer</h3>";
                                    echo "</div>";
                                    echo "<div class='detail'>";
                                        echo "<h6 class='text-center'>$faq_answer</h6>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='align-self-center'>";
                            echo "<a href='#' onclick='delete_faq({$faq_id});' id='delete' class='listActionBtn btn m-1 m-lg-2'>Delete</a>";
                        echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <a href='FAQ_add.php' class='actionBtn btn mx-2'>Add FAQ</a>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script type='text/javascript'>
    function delete_faq(faq_id) {
        if (confirm('Do you want to delete this FAQ?')) {
            window.location = 'FAQ_delete.php?faq_id=' + faq_id;
        }
    }
</script>

</body>

</html>