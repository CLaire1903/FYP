<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Create FAQ
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">

    <style>
        #FAQ {
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
            <h1 class="text-center mt-5">Add FAQ</h1>
            <?php 
                if ($_POST){
                    try {
                        if (empty($_POST['faq_question']) || empty($_POST['faq_answer'])) {
                            throw new Exception("Please make sure all fields are not empty!");
                        }
                        $checkFaqQuery = "SELECT * FROM faq WHERE faq_question = :faq_question";
                        $checkFaqStmt = $con->prepare($checkFaqQuery);
                        $checkFaqStmt->bindParam(':faq_question', $_POST['faq_question']);
                        $checkFaqStmt->execute();
                        $checkFaqRow = $checkFaqStmt->fetch(PDO::FETCH_ASSOC);
                        if($checkFaqRow != 0) {
                            throw new Exception("FAQ exist!");
                        }
        
                        $faqQuery = "INSERT INTO faq SET faq_question=:faq_question, faq_answer=:faq_answer";
                        $faqStmt = $con->prepare($faqQuery);
                        $faq_question = ucfirst($_POST['faq_question']);
                        $faq_answer = ucfirst($_POST['faq_answer']);
        
                        $faqStmt->bindParam(':faq_question', $faq_question);
                        $faqStmt->bindParam(':faq_answer', $faq_answer);
                        if ($faqStmt->execute()) {
                            echo "<script>window.location.href='FAQ.php?action=added';</script>";
                        } else {
                            echo "<script>window.location.href='FAQ.php? action=addedFail';</script>";
                        }
                    } catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    } catch (Exception $exception) {
                        echo "<div class='d-flex justify-content-center'>
                            <div class='alert alert-danger d-flex align-items-center col-10 mt-5' role='alert'>
                                    <svg class='alerticon me-2' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                                    <div>
                                    " . $exception->getMessage() . "
                                    </div>
                            </div>
                        </div>";
                    } 
                }
            ?>
        
            <div class="d-flex justify-content-center">
                <div class="col-10">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Question :  <span class="text-danger">*</span></h5>
                            <td><input type='text' name='faq_question' id="faq_question" value="<?php echo (isset($_POST['faq_question'])) ? $_POST['faq_question'] : ''; ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Answer :  <span class="text-danger">*</span></h5>
                            <td><textarea type='text' name='faq_answer' id="faq_answer" class='form-control' rows="5"><?php echo (isset($_POST['faq_answer'])) ? $_POST['faq_answer'] : ''; ?></textarea></td>
                        </div>
                        <div class="button d-grid m-3 d-flex justify-content-center">
                            <button type='submit' class='actionBtn btn m-3'>Submit</button>
                            <?php echo"<a href='FAQ.php' class='actionBtn btn m-3'>Back</a>"; ?>
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