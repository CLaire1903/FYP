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
                            <a id="summary" class="nav-link word" aria-current="page" href="summary.php">Summary</a>
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
                                <li><a id="addProduct" class="dropdown-item word" href="product_create.php">Create Product</a></li>
                                <li><a id="productList" class="dropdown-item word" href="product_list.php">Product List</a></li>
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
                                <li><a id="createOrder" class="dropdown-item word" href="order_create.php">Create Order</a></li>
                                <li class="d-flex">
                                    <?php 
                                        $checkNewOrderQuery = "SELECT order_status FROM orders WHERE order_status=:order_status";
                                        $checkNewOrderStmt = $con->prepare($checkNewOrderQuery);
                                        $order_status = "new order";
                                        $checkNewOrderStmt->bindParam(":order_status", $order_status);
                                        $checkNewOrderStmt->execute();
                                        $newOrder_count = $checkNewOrderStmt->rowCount();
                                        if ($newOrder_count > 0 ){
                                            echo "<a id='orderList' class='dropdown-item word' href='order_list.php'>Order List</a>";
                                            echo "<span class='lblCartCount badge badge-warning rounded-pill m-2'> $newOrder_count </span></li>";
                                        } else {
                                            echo "<a id='orderList' class='dropdown-item word' href='order_list.php'>Order List</a>";
                                        }
                                    ?>
                                </li>
                                <li class="d-flex">
                                    <?php 
                                        $checkCustomizedQuery = "SELECT designer_email FROM customized WHERE designer_email IS NULL";
                                        $checkCustomizedStmt = $con->prepare($checkCustomizedQuery);
                                        $checkCustomizedStmt->execute();
                                        $new_count = $checkCustomizedStmt->rowCount();
                                        if ($new_count > 0 ){
                                            echo "<a id='customMadeOrder' class='dropdown-item word' href='customMade_list.php'>Custom Made</a>";
                                            echo "<span class='lblCartCount badge badge-warning rounded-pill m-2'> $new_count </span></li>";
                                        } else {
                                            echo "<a id='customMadeOrder' class='dropdown-item word' href='customMade_list.php'>Custom Made</a>";
                                        }
                                    ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <?php 
                if (isset($_SESSION['admin_email'])){
                    $checkPositionQuery = "SELECT * FROM admin WHERE admin_email=:admin_email";
                    $checkPositionStmt = $con->prepare($checkPositionQuery);
                    $admin_email = $_SESSION['admin_email'];
                    $checkPositionStmt->bindParam(":admin_email", $admin_email);
                    $checkPositionStmt->execute();
                    $checkPositionRow = $checkPositionStmt->fetch(PDO::FETCH_ASSOC);
                    $admin_position = $checkPositionRow['admin_position'];
                    if ($admin_position == "director" || $admin_position == "manager") {
                        echo "<div>";
                            echo "<ul class='nav justify-content-center'>";
                                echo "<li class='nav-item dropdown'>";
                                    echo "<a id='staff' class='nav-link dropdown-toggle navbarDropdownMenuLink word' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Staff
                                    </a>";
                                    echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
                                        echo "<li><a id='addStaff' class='dropdown-item word' href='staff_create.php'>Create staff</a></li>";
                                        echo "<li><a id='staffList' class='dropdown-item word' href='staff_list.php'>Staff List</a></li>";
                                    echo "</ul>";
                                echo "</li>";
                            echo "</ul>";
                        echo "</div>";
                        echo "<div>";
                            echo "<ul class='nav justify-content-center'>";
                                echo "<li class='nav-item dropdown'>";
                                    echo "<a id='designer' class='nav-link dropdown-toggle navbarDropdownMenuLink word' role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    Designer
                                    </a>";
                                    echo "<ul class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>";
                                        echo "<li><a id='addDesigner' class='dropdown-item word' href='designer_create.php'>Create Designer</a></li>";
                                        echo "<li><a id='designerList' class='dropdown-item word' href='designer_list.php'>Designer List</a></li>";
                                    echo "</ul>";
                                echo "</li>";
                            echo "</ul>";
                        echo "</div>";
                    } 
                }
                ?>
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