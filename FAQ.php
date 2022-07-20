<!DOCTYPE HTML>
<html>

<head>
    <title>Frequently Asked Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
    <link href="css/product.css" rel="stylesheet">

    <style>
        #FAQ {
            font-weight: bold;
        }
        .section:hover {
            box-shadow: 0px 0px 8px 8px rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0.19);
        }
        .answer {
            display: none;
        }
        .question:hover + .answer {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php 
        include 'navigationBar.php';
        include 'config/dbase.php';
        ?>
        <div class="aboutUs d-flex flex-column justify-content-center">
            <h1 class="text-center my-5">Frequently Asked Question</h1>
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
                    echo "<div class='section border mb-5 mx-5'>";
                        echo "<div class='question m-3 d-flex'>";
                            echo "<div class='instruction mx-3 col-2'>";
                                echo "<h6 class='text-center'>Question</h3>";
                            echo "</div>";
                            echo "<div class='detail'>";
                                echo "<h5 class='text-center'>$faq_question</h3>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='answer'>";
                            echo "<div class='m-3 d-flex'>";
                                echo "<div class='instruction mx-3 col-2'>";
                                    echo "<h6 class='text-center'>Answer</h3>";
                                echo "</div>";
                                echo "<div class='detail'>";
                                    echo "<h6 class='text-center'>$faq_answer</h6>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <?php
        include 'footer.php';
        ?>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>

</html>