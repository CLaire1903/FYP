<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid d-flex justify-content-lg-between">
        <div class="storeLogo d-none d-lg-block">
            <a href="index.php"><img src="../image/logo/mm-logoB.png"></a>
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="storeLogo d-lg-none ">
            <a href="index.php"><img src="../image/logo/mm-logoB.png"></a>
        </div>
        <div class="d-lg-none">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                <div class="d-flex">
                        <div class="navIcon mx-2">
                            <?php
                            if (isset($_SESSION['cus_email'])){
                                $checkCartQuery = "SELECT * FROM cart WHERE cus_email = :cus_email";
                                $checkCartStmt = $con->prepare($checkCartQuery);
                                $cus_email = $_SESSION['cus_email'];
                                $checkCartStmt->bindParam(":cus_email", $cus_email);
                                $checkCartStmt->execute();
                                $cart_count = $checkCartStmt->rowCount();
                                if ($cart_count >0 ){
                                    echo "<a href='cart.php?cus_email={$cus_email}'><img src='../image/icon/cart.png' alt='cart' class='d.none'></a>";
                                    echo "<span class='lblCartCount badge badge-warning px-2 rounded-pill'> $cart_count </span>";
                                } else {
                                    echo "<a href='cart.php?cus_email={$cus_email}'><img src='../image/icon/cart.png' alt='cart' class='d.block'></a>";
                                    echo "<span class='lblCartCount badge badge-warning px-2 rounded-pill'> 0 </span>";
                                }
                            }

                            ?>
                        </div>
                        <?php 
                            if (isset($_SESSION['cus_email'])){
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_profile.php?cus_email={$cus_email}'><img src='../image/icon/profile.png' alt='profile'></a>
                                </div> ";
                        
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_logout.php'><img src='../image/icon/logout.png' alt='Logout'></a>
                                </div>";
                            } else{
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_login.php'><img src='../image/icon/login.png' alt='Login'></a>
                                </div>";
                            }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="navContent rounded-pill col-12 col-lg-8">
            <div class="collapse navbar-collapse justify-content-around" id="navbarToggle">
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="home" class="nav-link word" aria-current="page" href="index.php">Home</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="category" class="nav-link word" aria-current="page" href="category.php">Category</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="customMade" class="nav-link word" aria-current="page" href="customMade.php">Custom Made</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="contactUs" class="word nav-link" aria-current="page" href="contactUs.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div> 
        </div>
        <div>
            <ul class="nav d-none d-lg-block">
                <li class="nav-item">
                    <div class="d-flex">
                        <div class="navIcon mx-2">
                            <?php
                            if (isset($_SESSION['cus_email'])){
                                $checkCartQuery = "SELECT * FROM cart WHERE cus_email = :cus_email";
                                $checkCartStmt = $con->prepare($checkCartQuery);
                                $cus_email = $_SESSION['cus_email'];
                                $checkCartStmt->bindParam(":cus_email", $cus_email);
                                $checkCartStmt->execute();
                                $cart_count = $checkCartStmt->rowCount();
                                if ($cart_count >0 ){
                                    echo "<a href='cart.php?cus_email={$cus_email}'><img src='../image/icon/cart.png' alt='cart' class='d.none'></a>";
                                    echo "<span class='lblCartCount badge badge-warning px-2 rounded-pill'> $cart_count </span>";
                                } else {
                                    echo "<a href='cart.php?cus_email={$cus_email}'><img src='../image/icon/cart.png' alt='cart' class='d.block'></a>";
                                    echo "<span class='lblCartCount badge badge-warning px-2 rounded-pill'> 0 </span>";
                                }
                            }

                            ?>
                        </div>
                        <?php 
                            if (isset($_SESSION['cus_email'])){
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_profile.php?cus_email={$cus_email}'><img src='../image/icon/profile.png' alt='profile'></a>
                                </div> ";
                        
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_logout.php'><img src='../image/icon/logout.png' alt='Logout'></a>
                                </div>";
                            } else{
                                echo "<div class='navIcon mx-2'>
                                    <a href='customer_login.php'><img src='../image/icon/login.png' alt='Login'></a>
                                </div>";
                            }
                        ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>