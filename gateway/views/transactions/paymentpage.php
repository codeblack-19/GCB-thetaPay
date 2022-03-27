<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thetaPay - Payment Portal</title>
    <?php require_once './public/templates/inheader.php' ?>
    <link rel="stylesheet" href="../public/styles/webpayment.css">
</head>
<body>
    <?php require_once './public/templates/header1.php' ?>
    <main class="content">
        <div class="choice_bx">
            <h4>Finish Payment via: <span id="_pmethod"></span> </h4>
            <div class="choice_wp">
                <div class="choice" id="thetaTotheta" onclick="displayInterForm()">
                    <img src="../public/asserts/favicon.ico" />
                    <p>theta-to-theta</p>
                </div>
                <div class="choice" id="bank_card" onclick="displayCardForm()">
                    <img src="../public/asserts/visa_n.png" />
                    <p>Credit Cards</p>
                </div>
            </div>

            <div class="theta-to-theta">
                <p id="_message"></p>
                <form id="interPay" method="post" action="paymentpage.php">
                    <input type="number" autocomplete="off" name="accountNo" placeholder="Account Number" />
                    <input type="password" autocomplete="off" name="pinCode" placeholder="Pin Code" />
                    <div class="btns">
                        <button type="submit" class="submitBtn">Submit</button>
                        <button type="button" class="cancelBtn" onclick="resetPage()">Reset</button>
                    </div>
                </form>
            </div>

            <div class="cardPayment">
                <p id="_message"></p>
                <form id="cardPayment" method="post" action="paymentpage.php">
                    <div class="field-container">
                        <label for="name">Name</label>
                        <input id="name" maxlength="20" type="text" placeholder="e.g John Doe">
                    </div>
                    <div class="field-container">
                        <label for="cardnumber">Card Number</label>
                        <input id="cardnumber" type="number" pattern="[0-9]*" inputmode="numeric" maxlength="16" placeholder="XXXX XXXX XXXX XXXX">
                    </div>
                    <div class="field-container flexdt">
                        <label for="expirationdate">Expiration (mm/yy)</label>
                        <input id="expiry_mm" type="text" pattern="[0-9]*" maxlength="2" inputmode="numeric" placeholder="MM">
                        <input id="expiry_yy" type="text" pattern="[0-9]*" maxlength="2" inputmode="numeric" placeholder="YY">
                    </div>
                    <div class="field-container">
                        <label for="securitycode">CVV</label>
                        <input id="securitycode" type="text" pattern="[0-9]*" maxlength="4" inputmode="numeric" placeholder="XXXX">
                    </div>
                    <div class="btns">
                        <button type="button" class="submitBtn">Submit</button>
                        <button type="button" class="cancelBtn" onclick="resetPage()">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/webpayment.js"></script>
</html>