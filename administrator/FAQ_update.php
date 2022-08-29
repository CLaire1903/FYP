<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>FAQ update</title>
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
        include 'navigationBar.php';
        $faq_id = isset($_GET['faq_id']) ? $_GET['faq_id'] : die('ERROR: 
        FAQ record not found.');
        ?>
        <?php 
            try {
                $getFAQQuery = "SELECT * FROM faq WHERE faq_id = :faq_id  ";
                $getFAQStmt = $con->prepare($getFAQQuery);
                $getFAQStmt->bindParam(":faq_id", $faq_id);
                $getFAQStmt->execute();
                $getFAQRow = $getFAQStmt->fetch(PDO::FETCH_ASSOC);
                $faq_question = $getFAQRow['faq_question'];
                $faq_answer = $getFAQRow['faq_answer'];
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            if ($_POST){
                try {
                    if (empty($_POST['faq_question']) || empty($_POST['faq_answer'])) {
                        throw new Exception("Please make sure all fields are not empty!");
                    }
                    
                    $faqQuery = "UPDATE faq SET faq_question=:faq_question, faq_answer=:faq_answer WHERE faq_id=:faq_id";
                    $faqStmt = $con->prepare($faqQuery);
                    $faq_question = htmlspecialchars(strip_tags(ucfirst($_POST['faq_question'])));
                    $faq_answer = htmlspecialchars(strip_tags(ucfirst($_POST['faq_answer'])));
    
                    $faqStmt->bindParam(':faq_id', $faq_id);
                    $faqStmt->bindParam(':faq_question', $faq_question);
                    $faqStmt->bindParam(':faq_answer', $faq_answer);
                    if ($faqStmt->execute()) {
                        echo "<script>window.location.href='FAQ.php?action=updated';</script>";
                    } else {
                        echo "<script>window.location.href='FAQ.php? action=updatedFail';</script>";
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
            <h1 class="text-center mt-5">Update FAQ</h1>
            <div class="d-flex justify-content-center">
                <div class="col-10">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?faq_id={$faq_id}"); ?>" method="post">
                    <div class="message mx-3">
                            <h5 class="mt-4 mb-0">FAQ ID : </h5>
                            <td><?php echo htmlspecialchars($faq_id, ENT_QUOTES);  ?></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Question :  <span class="text-danger">*</span></h5>
                            <td><input type='text' name='faq_question' id="faq_question" value="<?php echo htmlspecialchars($faq_question, ENT_QUOTES); ?>" class='form-control' /></td>
                        </div>
                        <div class="message mx-3">
                            <h5 class="mt-4 mb-0">Answer :  <span class="text-danger">*</span></h5>
                            <td><textarea type='text' name='faq_answer' id="faq_answer" class='form-control' rows="5"><?php echo htmlspecialchars($faq_answer, ENT_QUOTES); ?></textarea></td>
                        </div>
                        <div class="button d-grid m-3 d-flex justify-content-center">
                            <button type='submit' class='actionBtn btn btn-lg m-3'>Submit</button>
                            <?php echo"<a href='FAQ.php' class='actionBtn btn btn-lg m-3'>Back</a>"; ?>
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