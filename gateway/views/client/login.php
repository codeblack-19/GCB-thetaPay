
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
    <title>Login - GCB-thetapay</title>
    <link rel="stylesheet" href="../public/styles/globals.css">
    <link rel="stylesheet" href="../public/styles/clientStyles/login.css">
    <link rel="shortcut icon" href="../public/asserts/favicon.ico" type="image/x-icon">
    <?php require_once './public/templates/inheader.php' ?>
</head>
<body>
    <main class="">
        <div class="fm_bx">
            <h3>Login to Portal</h3>
            <form id="lg_fm">
                <p id="_message"></p>
                <div class="form-outline">
                    <input type="email" id="Email" class="form-control form-control-lg" name="email" />
                    <label class="form-label" for="typeEmail">Email</label>
                </div>
                <div class="form-outline">
                    <input type="password" id="Password" class="form-control form-control-lg" name="password" />
                    <label class="form-label" for="typePassword">Password</label>
                </div>
                <button class="btn loginbtn" id="submitBtn" type="submit">
                    login
                </button>
            </form>
            <div class="othbtns">
                <!-- <a class="btn btn-link px-3 me-2">forgot password?</a> -->
                <?php require_once '_forgotpassword.php' ?>
                <a href="/GCB-thetaPay/gateway/client/signup" class="btn btn-link px-3 me-2">sign up</a>
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

        lg_fm?.addEventListener("submit", (e) => {
            e.preventDefault();
            setformMessage("","");
            act_loadingbtn();

            let email = lg_fm.elements['email'].value;
            let password = lg_fm.elements['password'].value;

           sendData(email, password);
        })

        const sendData = (email, password) => {
            $.ajax({
                type: "POST",
                processData: false,
                contentType: "application/json; charset=utf-8",
                cache: false,
                url: "/GCB-thetaPay/gateway/auth/login_ct",
                data: JSON.stringify({email, password}),
                dataType: 'json',
                success: function (res) {
                    lg_fm.reset();
                    setInterval(() => {   
                        setformMessage("success", "Authenticated successfully");
                        window.location.href = "/GCB-thetaPay/gateway/client"
                    }, 1000);
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