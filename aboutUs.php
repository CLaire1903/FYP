<!DOCTYPE HTML>
<html>

<head>
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">

    <style>
        #aboutUs {
            font-weight: bold;
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
            <h1 class="text-center mt-5">About Us</h1>
            <div class="d-flex justify-content-center">
                <div class="col-10 m-5 border border-dark">
                    <p class="text-center pt-5">
                        Memorable Memories is an online formal attire store. 
                    </p>
                    <p class="text-center">
                        Started with passion and a dream to make everyone have the ability to own a wedding dress for their "big day". 
                    </p>
                    <p class="text-center">
                        Focusing in how your style insted of the price of the wedding dress. 
                    </p>
                    <p class="text-center">
                        With us, every bride is beautiful and everyone will have an unforgetable memories. 
                    </p>
                    <p class="text-center pb-5">
                        I am sure with Memorable Memories you will become the most beutiful bride in your "big day".
                    </p>
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