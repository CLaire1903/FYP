<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="../css/shared.css" rel="stylesheet">

    <style>
        #contactUs {
            font-weight: bold;
        }
        .contactDetail {
            color: black;
            text-decoration: none;
        }
        .contactDetail:hover {
            color: #ff7474;
            font-weight: bold;
            text-decoration: underline;
        }
        .feedback, .phone, .email, .address {
            background-color: #ffe1e1;
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
        <div class="title text-center mt-5">
            <h1>Feel free to contact us.</h1>
        </div>
        <div class="contact d-flex flex-column flex-md-row justify-content-evenly mt-5">
            <div class="phone col-11 col-md-3 p-3 mx-3 m-md-0 border rounded-3 d-flex flex-column">
                <b class="text-center">Phone Number</b>
                <a href="tel:+6012-3456789" class="contactDetail pt-2 text-center">+6012 - 345 6789</a>
                <a href="tel:+6011-23456789" class="contactDetail py-2 text-center">+6011 - 2345 6789</a>
            </div>
            <div class="email col-11 col-md-3 p-3 m-3 m-md-0 border rounded-3 d-flex flex-column">
                <b class="text-center">Email</b>
                <a href="mailto:example@gmail.com" class="contactDetail pt-2 text-center">example@gmail.com</a>
                <a href="mailto:example@hotmail.com" class="contactDetail py-2 text-center">example@hotmail.com</a>
            </div>
            <div class="address col-11 col-md-3 p-3 mx-3 m-md-0 border rounded-3 d-flex flex-column">
                <b class="text-center">Address</b>
                <p class="pt-2 mb-0 text-center">Blok B&C, Lot, 5, Seksyen 10, Jalan Bukit, Taman Bukit Mewah, 43000 Kajang, Selangor</p>
            </div>
        </div>
        <div class="feedback d-flex justify-content-center m-3 m-lg-5 border rounded-3">
            <div class="feedbackForm col-10">
                <div class="p-2 mx-auto">
                <h4 class="instruction mt-3 text-center">Kindly provide us your feedback.</h4>
                <?php
                    if ($_POST) {
                        try {
                            if (empty($_POST['cus_email']) || empty($_POST['feedback_detail']) ) {
                                throw new Exception("Make sure all fields are not empty");
                            }

                            $sendFeedbackQuery = "INSERT INTO feedback SET cus_email=:cus_email, feedback_detail=:feedback_detail";
                            $sendFeedbackStmt = $con->prepare($sendFeedbackQuery);
                            $cus_email = $_POST['cus_email'];
                            $feedback_detail = $_POST['feedback_detail'];
                            $sendFeedbackStmt->bindParam(':cus_email', $cus_email);
                            $sendFeedbackStmt->bindParam(':feedback_detail', $feedback_detail);
                            if ($sendFeedbackStmt->execute()) {
                                echo "<div class='alert alert-success my-3'>Thank you for your feedback.</div>";
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
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="dropMessage">
                            <div class="message">
                                <p>Email : </p>
                                <td><input type='text' name='cus_email' id="cus_email" value="<?php echo (isset($_POST['cus_email'])) ? $_POST['cus_email'] : ''; ?>" class='form-control' /></td>
                            </div>
                        </div>
                        <div class="dropMessage">
                            <div class="message">
                                <p class="mt-3">Feedback Message : </p>
                                <td><textarea type='text' name='feedback_detail' id="feedback_detail" class='form-control' rows="10"><?php echo (isset($_POST['feedback_detail'])) ? $_POST['feedback_detail'] : ''; ?></textarea></td>
                            </div>
                        </div>
                        <div class="button d-grid m-3 d-flex justify-content-center">
                            <button type='submit' class='actionBtn btn btn-lg'>Send Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="map m-3 m-lg-5">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.397258017919!2d101.79028701221108!3d2.9871185335820627!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdcb841d3762c7%3A0x4dc97330d731c530!2sNew%20Era%20University%20College!5e0!3m2!1sen!2smy!4v1659508363769!5m2!1sen!2smy" width="100%" height="450" frameborder="" class="rounded-3"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>