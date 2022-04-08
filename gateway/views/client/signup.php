
<?php 
    session_start(); 
    
    if(isset($_SESSION["user_token"])){
        header('location: /GCB-thetaPay/gateway/client');
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp - GCB-thetapay</title>
    <link rel="stylesheet" href="../public/styles/globals.css">
    <link rel="stylesheet" href="../public/styles/clientStyles/login.css">
    <link rel="shortcut icon" href="../public/asserts/favicon.ico" type="image/x-icon">
    <?php require_once './public/templates/inheader.php' ?>
</head>
<body>
    <main class="">
        <div class="fm_bx">
            <h3>Signup to thetapay</h3>
            <form id="lg_fm">
                <p id="_message"></p>
                <div class="form-outline">
                    <input type="text" id="firstname" class="form-control" name="firstname" />
                    <label class="form-label" for="firstname">First name</label>
                </div>
                <div class="form-outline">
                    <input type="text" id="lastname" class="form-control" name="lastname" />
                    <label class="form-label" for="lastname">Last name</label>
                </div>
                <div class="form-outline">
                    <input type="email" id="Email" class="form-control" name="email" />
                    <label class="form-label" for="Email">Email</label>
                </div>
                <div class="form-outline">
                    <input type="password" id="Password" class="form-control" name="password" />
                    <label class="form-label" for="Password">Password</label>
                </div>
                <div class="form-outline">
                    <input type="password" id="pinCode" class="form-control" name="pinCode" />
                    <label class="form-label" for="pinCode">Pin Code</label>
                </div>
                <div class="form-outline">
                    <input type="tel" id="phone" class="form-control" name="phone" />
                    <label class="form-label" for="phone">Phone</label>
                </div>
                <div class="form-outline">
                    <input type="text" id="businessName" class="form-control" name="businessName" />
                    <label class="form-label" for="businessName">Business name</label>
                </div>
                <button class="btn loginbtn" id="submitBtn" type="submit">
                    Sign up
                </button>
            </form>
            <div class="othbtns">
                <?php require_once '_forgotpassword.php' ?>
                <a href="/GCB-thetaPay/gateway/client/login" class="btn btn-link px-3 me-2">login</a>
                <a href="/GCB-thetaPay/gateway/client" class="btn btn-link px-3 me-2">Back to Home</a>
            </div>
        </div>
    </main>
</body>
<?php require_once './public/templates/infooter.php' ?>
<script src="../public/js/jquery-3.6.0.min.js"></script>
<script src="../public/js/forgotpassword.js"></script>
<script>
    $(document).ready(() => {
        var lg_fm = document.getElementById("lg_fm");
        var formMessage = document.getElementById("_message");
        var sbtBtn = document.getElementById('submitBtn');
        var requestbody = {}

        lg_fm?.addEventListener("submit", (e) => {
            e.preventDefault();
            setformMessage("","");
            act_loadingbtn();

            requestbody.email = lg_fm.elements['email'].value;
            requestbody.password = lg_fm.elements['password'].value;
            requestbody.firstname = lg_fm.elements['firstname'].value;
            requestbody.lastname = lg_fm.elements['lastname'].value;
            requestbody.pinCode = lg_fm.elements['pinCode'].value;
            requestbody.businessName = lg_fm.elements['businessName'].value;
            requestbody.phone = lg_fm.elements['phone'].value;

           sendData();
        })

        const sendData = () => {
            $.ajax({
                type: "POST",
                processData: false,
                contentType: "application/json; charset=utf-8",
                cache: false,
                url: "/GCB-thetaPay/gateway/auth/signup",
                data: JSON.stringify(requestbody),
                dataType: 'json',
                success: function (res) {
                    lg_fm.reset();
                    setformMessage("success", "SignUp successfull, visit your email to verify your account");
                }, error: function (XMLHttpRequest){
                    setformMessage("error", XMLHttpRequest.responseJSON.error)
                }
            });
        }

        const setformMessage = (type, message) => {
            formMessage.setAttribute("class", type);
            formMessage.innerText = message;
            stopLoading()
        }

        const act_loadingbtn = () => {
            sbtBtn.setAttribute("disabled", "true");
            sbtBtn.innerHTML = `
                <div class="spinner-grow" style="width: 2rem; height: 2rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `
        }

        const stopLoading = () => {
            sbtBtn.removeAttribute("disabled");
            sbtBtn.innerHTML = `login`
        }
    })
</script>
</html>