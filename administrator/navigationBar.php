<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid d-flex justify-content-lg-between">
        <div class="storeLogo d-none d-lg-block">
            <a href="home.php"><img src="/fyp/image/logo/logoB.png"></a>
        </div>
        <button class="navbar-toggler ml-auto custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="storeLogo d-lg-none ">
            <a href="index.php"><img src="/fyp/image/logo/logoB.png"></a>
        </div>
        <div class="d-lg-none">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <div class="d-flex">
                        <div class="navIcon mx-2">
                            <a href="admin_profile.php?admin_email={$admin_email}"><img src="/fyp/image/icon/profile.png" alt="profile"></a>
                        </div>
                        <div class="navIcon mx-2">
                            <a href="admin_logout.php"><img src="/fyp/image/icon/logout.png" alt="Logout"></a>
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
                            <a id="home" class="nav-link word" aria-current="page" href="home.php">Home</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="product" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Product
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="category" class="dropdown-item word" href="category.php">Category</a></li>
                                <li><a id="productList" class="dropdown-item word" href="product_list.php">Product List</a></li>
                                <li><a id="addProduct" class="dropdown-item word" href="product_addNewProduct.php">Add Product</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="customer" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Customer
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="createCus" class="dropdown-item word" href="customer.php">Create Customer</a></li>
                                <li><a id="cusList" class="dropdown-item word" href="customer_list.php">Customer List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="order" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Order
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="createOrder" class="dropdown-item word" href="order.php">Create Order</a></li>
                                <li><a id="orderList" class="dropdown-item word" href="order_list.php">Order List</a></li>
                                <li class="nav-item"><a id="customMadeOrder" class="nav-link word" aria-current="page" href="customMade.php">Custom Made Order</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item dropdown">
                            <a id="staff" class="nav-link dropdown-toggle navbarDropdownMenuLink word" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Staff 
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a id="addStaff" class="dropdown-item word" href="order.php">Add new staff</a></li>
                                <li><a id="staffList" class="dropdown-item word" href="order_list.php">Staff List</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a id="feedback" class="word nav-link" aria-current="page" href="feedback.php">Feedback Review</a>
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
                            <a href="admin_profile.php?admin_email={$admin_email}"><img src="/fyp/image/icon/profile.png" alt="profile"></a>
                        </div> 
                        <div class="navIcon mx-2">
                            <a href="admin_logout.php"><img src="/fyp/image/icon/logout.png" alt="Logout"></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>