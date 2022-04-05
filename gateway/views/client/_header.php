<header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Container wrapper -->
            <div class="container">
                <!-- Navbar brand -->
                <a class="navbar-brand me-2" href="/GCB-thetaPay/gateway/client">
                    <img
                        src="../public/asserts/gcb_pic.png"
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
                        <li class="nav-item">
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/GCB-thetaPay/gateway/client/settings">Account Settings</a>
                        </li>
                    </ul>

                    <!-- right links -->

                    <div class="d-flex align-items-center">
                        <?php 
                            if(!isset($_SESSION["user_token"])){
                                echo ' <a href="/GCB-thetaPay/gateway/client/login" class="btn btn-link px-3 me-2"> Login </a>
                                        <a href="/GCB-thetaPay/gateway/client/signup" class="btn signupbtn me-3"> Sign up</a>';
                            }else{
                                echo '<a href="/GCB-thetaPay/gateway/client/topup" class="btn btn-light px-3 me-3"> Top up </a>
                                <a id="logoutBtn" class="btn btn-dark px-3 me-2"> logout </a>';
                            }
                        
                        ?>
                       
                    </div>
                </div>
                <!-- Collapsible wrapper -->
            </div>
            <!-- Container wrapper -->
        </nav>
    </header>