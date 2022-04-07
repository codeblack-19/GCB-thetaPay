<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - GCB-thetapay</title>
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
        <h1 class="heading">Account Settings</h1>

        <div class="acctSettings row">
            <div class="col-lg-6 mb-3">
                <div class="card pinfo">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Personal Details</h5>
                        <p class="card-text">
                            Account Number: 
                            <span class="badge badge-warning d-inline m-2 fs-6">
                                <?php echo substr('0000000000' .$_GET['acct_data']['accountNo'], -10);  ?>
                            </span>
                        </p>
                        <form class="" id="editInfoFm">
                            <p id="_message"></p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-outline">
                                        <input type="text" id="firstname" class="form-control form-control-lg" name="firstname" value="<?php echo $_GET['acct_data']['firstname'] ?>" />
                                        <label class="form-label" for="firstname">First name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-outline">
                                        <input type="text" id="lastname" class="form-control form-control-lg" name="lastname" value="<?php echo $_GET['acct_data']['lastname'] ?>" />
                                        <label class="form-label" for="lastname">Last name</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-outline">
                                        <input type="email" id="email" class="form-control form-control-lg" name="email" value="<?php echo $_GET['acct_data']['email'] ?>" aria-label="readonly input example" readonly />
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-outline">
                                        <input type="tel" id="phone" class="form-control form-control-lg" name="phone" value="<?php echo $_GET['acct_data']['phone'] ?>" />
                                        <label class="form-label" for="phone">Phone</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-outline">
                                        <input type="text" id="businessName" maxlength="100" class="form-control form-control-lg" name="businessName" value="<?php echo $_GET['acct_data']['businessName'] ?>" />
                                        <label class="form-label" for="pinCode">Business Name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="asbtn_bx">
                                <button type="submit" id="submitBtn" class="btn acctSettingbtns">Save Changes</button>
                                <button type="button" id="chPassBtn" class="btn acctSettingbtns">Change Password</button>
                                <?php require_once '_changePin.php' ?>
                                <!-- <button type="button" id="chPinBtn" class="btn acctSettingbtns">Change Pin Code</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Integration Keys</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-outline">
                                    <textarea class="form-control bg-light bg-gradient" id="secretekey" rows="4" aria-label="readonly input example" readonly>
                                        <?php echo $_GET['acct_data']['secreteKey'] ?>
                                    </textarea>
                                    <label class="form-label" for="secretekey">Secret Key</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-outline">
                                    <textarea class="form-control bg-light bg-gradient" id="publickey" rows="4" aria-label="readonly input example" readonly>
                                        <?php echo $_GET['acct_data']['publicKey'] ?>
                                    </textarea>
                                    <label class="form-label" for="publickey">Public Key</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<?php require_once './public/templates/infooter.php'?>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/acctSettings.js" class="<?php echo $_SESSION["user_token"] ?? ""; ?>" ></script>
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
                    location.reload();
                }
            });
        })
    })
</script>
</html>