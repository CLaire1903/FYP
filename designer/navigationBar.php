<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid d-flex justify-content-lg-between">
        <div class="storeLogo d-none d-lg-block">
            <a href="summary.php"><img src="/fyp/image/logo/logoB.png"></a>
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="storeLogo d-lg-none ">
            <a href="summary.php"><img src="/fyp/image/logo/logoB.png"></a>
        </div>
        <div class="d-lg-none">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <div class="d-flex">
                        <div class="navIcon mx-2">
                            <a href="designer_profile.php?designer_email={$designer_email}"><img src="/fyp/image/icon/profile.png" alt="profile"></a>
                        </div>
                        <div class="navIcon mx-2">
                            <a href="designer_logout.php"><img src="/fyp/image/icon/logout.png" alt="Logout"></a>
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
                            <a id="summary" class="nav-link word" aria-current="page" href="summary.php">Summary</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li>
                            <a id="addProduct" class="nav-link word" aria-current="page" href="product_create.php">Create Product</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li>
                            <a id="productList" class="nav-link word" aria-current="page" href="product_list.php">Product List</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li>
                            <a id="customMadeList" class="nav-link word" aria-current="page" href="customMade_list.php">Custom Made List</a>
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
                            <a href="designer_profile.php?designer_email={$designer_email}"><img src="/fyp/image/icon/profile.png" alt="profile"></a>
                        </div> 
                        <div class="navIcon mx-2">
                            <a href="designer_logout.php"><img src="/fyp/image/icon/logout.png" alt="Logout"></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>