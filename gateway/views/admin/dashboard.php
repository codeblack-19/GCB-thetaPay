<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thetaPay Admin - Dashboard</title>
    <?php require_once './public/templates/inheader.php' ?>
    <link rel="stylesheet" href="./public/styles/globals.css">
    <link rel="stylesheet" href="./public/styles/clientStyles/dashboard.css">
    <link rel="shortcut icon" href="./public/asserts/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand me-2" href="/GCB-thetaPay/gateway/admin">
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
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client/settings">Account Settings</a>
                        </li> -->
                    </ul>

                    <!-- right links -->

                    <div class="d-flex align-items-center">
                        <?php 
                            // if(!isset($_SESSION["admin_token"])){
                            //     echo ' <a href="/GCB-thetaPay/gateway/client/login" class="btn btn-link px-3 me-2"> Login </a>
                            //             <a href="/GCB-thetaPay/gateway/client/signup" class="btn btn-warning me-3"> Sign up</a>';
                            // }else{
                            //     echo '<a href="/GCB-thetaPay/gateway/client/topup" class="btn btn-light px-3 me-3"> Top up </a>
                            //     <a id="logoutBtn" class="btn btn-dark px-3 me-2"> logout </a>';
                            // }
                        
                        ?>
                       <a id="logoutBtn" class="btn btn-dark px-3 me-2"> logout </a>
                    </div>
                </div>
                <!-- Collapsible wrapper -->
            </div>
            <!-- Container wrapper -->
        </nav>
    </header>

    <!-- content -->
    <div class="container content">
        <h1 class="heading">Dashboard</h1>

        <!-- summary  -->
        <div class="row summary">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Number of TXN.:</h5>
                        <p class="card-text" id="num_txn">
                            00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total TXN. Amt.:</h5>
                        <p class="card-text" id="txn_amt">
                            GHS 0.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Account Balance:</h5>
                        <p class="card-text" id="balance">
                            GHS 0.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">No. of Customers:</h5>
                        <p class="card-text" id="num_cus">
                            00
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- transactions -->
        <div class="txn_tb">
            <h5>Transactions</h5>
            <div class="table-responsive">
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light sticky-top">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Medium</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="_txn_tb">
                        
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</body>
<?php require_once './public/templates/infooter.php'?>
<script src="./public/js/jquery-3.6.0.min.js"></script>
<script src="./public/js/adminDashboard.js" class="<?php echo $_SESSION["admin_token"] ?? ""; ?>" ></script>
<script src="./public/js/logout.js"></script>
</html>