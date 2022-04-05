<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thetaPay - Home</title>
    <?php require_once './public/templates/inheader.php' ?>
    <link rel="stylesheet" href="./public/styles/globals.css">
    <link rel="stylesheet" href="./public/styles/clientStyles/home.css">
    <link rel="shortcut icon" href="./public/asserts/favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand me-2" href="/GCB-thetaPay/gateway/client">
                    <img
                        src="./public/asserts/gcb_pic.png"
                        height="30"
                        alt="MDB Logo"
                        loading="lazy"
                        style="margin-top: -1px;"
                    />
                    <small class="navbar-brand mb-0 h logoName">thetaPay</small>
                </a>

                <!-- Toggle button -->
                <button
                    class="navbar-toggler"
                    type="button"
                    data-mdb-toggle="collapse"
                    data-mdb-target="#navbarButtonsExample"
                    aria-controls="navbarButtonsExample"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarButtonsExample">
                    <!-- Left links -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li> -->
                    </ul>
                    <!-- Left links -->

                    <div class="d-flex align-items-center">
                        <?php 
                            if(!isset($_SESSION["user_token"])){
                                echo ' <a href="/GCB-thetaPay/gateway/client/login" class="btn btn-link px-3 me-2"> Login </a>
                                        <a href="/GCB-thetaPay/gateway/client/signup" class="btn signupbtn me-3"> Sign up</a>';
                            }else{
                                echo '<a id="logoutBtn" class="btn btn-link px-3 me-2"> logout </a>
                                    <a href="/GCB-thetaPay/gateway/client/dashboard" class="btn signupbtn me-3"> Dashboard</a>';
                            }
                        
                        ?>
                       
                    </div>
                </div>
                <!-- Collapsible wrapper -->
            </div>
            <!-- Container wrapper -->
        </nav>

        <!-- Background image -->
        <div
            class="p-5 text-center bg-image"
            style="
            background-image: url('https://www.gcbbank.com.gh/images/news/2018/GCB-GOLDEN-EAGLE-AMBASSADORS-.JPG');
            height: 400px;
            margin-top: 58px;
            "
        >
            <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-white banner-text">
                        <h1 class="mb-2">Welcome to GCB-thetaPay</h1>
                        <h4 class="mb-3">"...we occupy all angles of payment"</h4>
                        <a class="btn btn-outline-light btn-lg" href="/GCB-thetaPay/gateway/client/signup" role="button">Sign Up Now</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Background image -->
    </header>

    <!-- service -->
    <div class="container services">
        <h3 class="mb-3">Our Services</h3>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Check Account Balance</h5>
                        <p class="card-text">
                            thetapay provides a portal for clients to have access to all acccount information with ease. 
                            From top-up to withdrawal...
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Web Payment</h5>
                        <p class="card-text">
                            thetapay gives you the chance to make payment integration into your systems, to take web payment from customers.
                            e.g theta-to-theta or credit card payment.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Safe Transactions</h5>
                        <p class="card-text">
                            All transaction actions are well authenticated and also data encryption of sensitive information are implemented.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <div class="footer">
        <div class="container">
            <p>powered by GCB &copy; 2022</p>
        </div>
    </div>
</body>
<?php require_once './public/templates/infooter.php' ?>
<script src="./public/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(() => {
        var logoutbtn = document.getElementById("logoutBtn");

        logoutbtn?.addEventListener("click", () => {

            $.ajax({
                type: "PUT",
                processData: false,
                contentType: "application/json; charset=utf-8",
                cache: false,
                url: "/GCB-thetaPay/gateway/auth/logout",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                    xhr.setRequestHeader('Authorization', "Bearer <?php echo $_SESSION["user_token"] ?? ""; ?>");
                },
                dataType: 'json',
                success: function (res) {
                    location.reload();
                }, error: function (XMLHttpRequest){
                    console.log(XMLHttpRequest.responseJSON.error);
                }
            });
        })
    })
</script>
</html>