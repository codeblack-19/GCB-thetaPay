<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashout - GCB-thetapay</title>
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
            <div class="_tp_fm_bx _chout_bx">
                <h3>Cashout</h3>
                <form id="tp_fm">
                    <p id="_message"></p>
                    <div class="selectBox">
                        <select name="bankName">
                            <option value="">Bank Name</option>
                            <option value="GCB">Ghana Commercial Bank</option>
                            <option value="ECOBANK">Eco Bank</option>
                            <option value="ACCESS BANK">Access Bank</option>
                            <option value="CALBANK">Cal Bank</option>
                        </select>
                    </div>
                    <div class="form-outline">
                        <input type="number" id="bankAcctNo" maxlength="16" class="form-control form-control-lg" name="bankAcctNo" />
                        <label class="form-label" for="bankAcctNo">Bank Account Number</label>
                    </div>
                    <div class="form-outline">
                        <input type="number" id="amount" maxlength="16" class="form-control form-control-lg" name="amount" />
                        <label class="form-label" for="amount">Amount</label>
                    </div>
                    <div class="form-outline">
                        <input type="number" id="pinCode" maxlength="3" class="form-control form-control-lg" name="pinCode" />
                        <label class="form-label" for="pinCode">Pin Code</label>
                    </div>
                    <button class="btn tpbtn" id="submitBtn" type="submit">
                        Cashout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
<?php require_once './public/templates/infooter.php'?>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/cashout.js" class="<?php echo $_SESSION["user_token"] ?? ""; ?>" ></script>
</html>