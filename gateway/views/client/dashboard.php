<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal - GCB-thetapay</title>
    <link rel="stylesheet" href="../public/styles/globals.css">
    <link rel="stylesheet" href="../public/styles/clientStyles/dashboard.css">
    <link rel="shortcut icon" href="../public/asserts/favicon.ico" type="image/x-icon">
    <?php require_once './public/templates/inheader.php' ?>
</head>
<body>
    <!-- header -->
    <?php require_once '_header.php' ?>

    <!-- content -->
    <div class="container content">
        <h1 class="heading">Dashboard</h1>

        <!-- summary  -->
        <div class="row summary">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Number of TXN.:</h5>
                        <p class="card-text" id="num_txn">
                            00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total TXN. Amt.:</h5>
                        <p class="card-text" id="txn_amt">
                            GHS 0.00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Account Balance:</h5>
                        <p class="card-text" id="balance">
                            GHS 0.00
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
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/dashboard.js" class="<?php echo $_SESSION["user_token"] ?? ""; ?>" ></script>
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