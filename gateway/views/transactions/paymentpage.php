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
            <div class="selections">
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
            </div>
        </div>
    </main>
</body>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/webpayment.js"></script>
</html>