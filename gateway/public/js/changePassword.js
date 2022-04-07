$(document).ready(() => {

    var formbody = document.getElementById("chPass");
    var formMessage = document.getElementById("_message");
    var sbtBtn = document.getElementById('submitBtn');
    var requestData = {email: "", password: "", signature: ""};

    formbody?.addEventListener('submit', function (e) {
        e.preventDefault();
        sbtBtn.style.display = "none";

        let mainPass = formbody.elements['password'].value;
        let verifyPass = formbody.elements['verifyPassword'].value;

        if (mainPass == '') {
            return setformMessage("error", "Password is required");
        } else if (mainPass.length < 6) {
            return setformMessage("error", "Password must be more than 6 characters");
        } else if (mainPass != verifyPass) {
            return setformMessage("error", "Passwords do not match")
        } else {
            setformMessage("", "");
            setgetFormdata();
        }
    })

    const setformMessage = (type, message) => {
        formMessage.setAttribute("class", type);
        formMessage.innerText = message;
        sbtBtn.style.display = "block";
    }

    const setgetFormdata = () => {
        var urlParams = new URLSearchParams(window.location.search);
        requestData.email = urlParams.get('email');
        requestData.password = formbody.elements['password'].value;
        requestData.signature = urlParams.get('signature');
        submitData();
    }

    const submitData = () => {

        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/auth/changepassword",
            data: JSON.stringify(requestData),
            dataType: 'json',
            success: function () {
                formbody.reset();
                document.getElementsByTagName("main")[0].innerHTML = `
                    <div class="verify_notice">
                        <h3>Password Changed Succesfully</h3>
                        <p class="check"><span>&#10003;</span></p>
                        <a href="/GCB-thetaPay/gateway/client">
                            <button>Go To Portal</button>
                        </a>
                    </div>
                `
            }, error: function (XMLHttpRequest){
                setformMessage("error", XMLHttpRequest.responseJSON.error)
            }
        });
    }
})