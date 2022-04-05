<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up - GCB-thetapay</title>
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
        <!-- <h1 class="heading">Top up</h1> -->

        <div class="_tp_holder">
            <div class="_tp_fm_bx">
                <h3>Top up via Credit Card</h3>
                <form id="tp_fm">
                    <p id="_message"></p>
                    <div class="form-outline">
                        <input type="text" id="name" class="form-control form-control-lg" name="name" />
                        <label class="form-label" for="name">Card Holder</label>
                    </div>
                    <div class="form-outline">
                        <input type="number" id="cardnumber" maxlength="16" class="form-control form-control-lg" name="cardnumber" />
                        <label class="form-label" for="cardnumber">Card Number</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Expiration (mm/yy)</span>
                        <input type="number" name="expiry_mm" maxlength="2" aria-label="mm" class="form-control form-control-lg" placeholder="MM" />
                        <input type="number" name="expiry_yy" maxlength="2" aria-label="yy" class="form-control form-control-lg" placeholder="YY" />
                    </div>
                    <div class="form-outline">
                        <input type="number" id="securitycode" maxlength="3" class="form-control form-control-lg" name="securitycode" />
                        <label class="form-label" for="securitycode">CVV</label>
                    </div>
                    <div class="form-outline">
                        <input type="number" id="amount" maxlength="16" class="form-control form-control-lg" name="amount" />
                        <label class="form-label" for="amount">Amount</label>
                    </div>
                    <button class="btn tpbtn" id="submitBtn" type="submit">
                        Top Up
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php require_once './public/templates/infooter.php'?>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/topup.js" class="<?php echo $_SESSION["user_token"] ?? ""; ?>" ></script>
</html>