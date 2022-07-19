<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid d-flex justify-content-lg-between">
        <div class="storeLogo d-none d-lg-block">
            <a href="index.php"><img src="image/logo/logoB.png"></a>
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="storeLogo d-lg-none ">
            <a href="index.php"><img src="image/logo/logoB.png"></a>
        </div>
        <div class="d-lg-none">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                <div class="d-flex">
                        <div class="navIcon mx-2">
                            <a href="#"><img src="image/icon/cart.png" alt="cart"></a>
                        </div>
                        <div class="navIcon mx-2">
                            <a href="#"><img src="image/icon/profile.png" alt="profile"></a>
                        </div>
                        <div class="navIcon mx-2">
                            <a href="customer_logout.php"><img src="image/icon/logout.png" alt="Logout"></a>
                        </div>
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
                            <a id="customMade" class="nav-link word" aria-current="page" href="#">Custom Made</a>
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
                            <a href="#"><img src="image/icon/cart.png" alt="cart"></a>
                        </div>
                        <div class="navIcon mx-2">
                            <a href="customer_profile.php?cus_username={$cus_username}"><img src="image/icon/profile.png" alt="profile"></a>
                        </div> 
                        <div class="navIcon mx-2">
                            <a href="customer_logout.php"><img src="image/icon/logout.png" alt="Logout"></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>