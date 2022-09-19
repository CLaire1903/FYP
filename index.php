<!DOCTYPE HTML>
<html>

<head>
    <title>index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="css/shared.css" rel="stylesheet">
</head>

<style>
    #logo img {
        width: 300px;
        height: 130px;
    }
    .index {
        height:100%;
        width:100%;
        position:fixed;
        background-color: #ffeaea; 
        background-size: contain; 
    }
    .image{
        width: 75%;
        border: none;
    }
    .quickInfo {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .quickInfo:hover {
        box-shadow: 0 4px 8px 0 rgb(255, 255, 255), 0 6px 20px 0 rgb(255, 255, 255);
    }
</style>

<body>
    <div class="index container-fluid d-flex justify-content-center">
        <div class="d-flex justify-content-center flex-column m-5 border-3 col-11 rounded-3">
            <div class="indexDetail p-2 mx-auto">
                <div id="logo" class="d-flex justify-content-center mt-5">
                    <a><img src="image/logo/mm-logoB.png"></a>
                </div>
                <div class="d-flex justify-content-center">
                    <h1 class="m-5">Kindly choose your role</h1>
                </div>
                <div class="d-flex flex-row flex-wrap justify-content-center">
                    <div class="role card text-center p-3 mx-3 mb-5 col-5 col-md-3">
                        <a href="customer/index.php"><img class="image" src="image/icon/customer.png"></a>
                        <a class="text-decoration-none" href="customer/index.php"> <h6 class='p-2 text-dark'>Customer</h6> </a>
                    </div>
                    <div class="role card text-center p-3 mx-3 mb-5 col-5 col-md-3">
                        <a href="designer/index.php"><img class="image" src="image/icon/designer.png"></a>
                        <a class="text-decoration-none" href="designer/index.php"> <h6 class='p-2 text-dark'>Designer</h6> </a>
                    </div>
                    <div class="role card text-center p-3 mx-3 mb-5 col-5 col-md-3">
                        <a href="administrator/index.php"><img class="image" src="image/icon/staff.png"></a>
                        <a class="text-decoration-none" href="administrator/index.php"> <h6 class='p-2 text-dark'>Administrator</h6> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>