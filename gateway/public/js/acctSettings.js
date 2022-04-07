$(document).ready(function () {
    var token = document.getElementsByTagName("script")[2].classList[0];
    var editInfoFm = document.getElementById("editInfoFm");
    var changePinFm = document.getElementById("chPcFm");
    var formMessage = document.getElementById("_message");
    var formMessage2 = document.getElementById("_message2");
    var modalClBtn = document.getElementById('modalClBtn');
    var chPBtn = document.getElementById('chPassBtn');
    var sbtCPbtn = document.getElementById('sbtCPbtn');
    var aSbtns = document.getElementsByClassName('acctSettingbtns');

    // edit personal information
    editInfoFm.addEventListener("submit", (e) => {
        e.preventDefault();
        setformMessage("", "");
        act_loadingbtn();

        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            data: JSON.stringify({
                firstname: editInfoFm.elements['firstname'].value,
                lastname: editInfoFm.elements['lastname'].value,
                phone: editInfoFm.elements['phone'].value,
                businessName: editInfoFm.elements['businessName'].value
            }),
            url: "/GCB-thetaPay/gateway/client/editInfo",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                xhr.setRequestHeader('Authorization', `Bearer ${token}`);
            },
            dataType: 'json',
            success: function (res) {
                setformMessage("success", res.message);
                setTimeout(() => {
                    setformMessage("", "");
                    // location.reload();
                }, 1000)
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseJSON.error);
                setformMessage("error", XMLHttpRequest.responseJSON.error);
            }
        });
    })

    // change account pin code
    sbtCPbtn.addEventListener("click", (e) => {
        e.preventDefault();
        setformMessage2("", "");
        act_loadingbtn();

        var pinCode = document.getElementById('pinCode').value
        var verifier = document.getElementById('pinCode').value

        if (pinCode.length < 6){
            return setformMessage2("error", "Pin code length must greater the 6")
        } else if (pinCode != verifier){
            return setformMessage2("error", "Pin codes don't match")
        }else{
            $.ajax({
                type: "PUT",
                processData: false,
                contentType: "application/json; charset=utf-8",
                cache: false,
                data: JSON.stringify({
                    pinCode
                }),
                url: "/GCB-thetaPay/gateway/accounts/updatepinCode",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                },
                dataType: 'json',
                success: function (res) {
                    setformMessage2("text-success", res.message);
                    setInterval(() => {
                        setformMessage2("", "");
                        location.reload();
                    }, 1500)
                },
                error: function (XMLHttpRequest) {
                    setformMessage2("text-danger", XMLHttpRequest.responseJSON.error);
                }
            });
        }

    });

    // change password button event
    chPBtn.addEventListener("click", () => {
        setformMessage("", "");
        act_loadingbtn();

        var confirmIt = confirm("Do you really want to change your password");
        if(!confirmIt){
            stopLoading();
            return;
        }

        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            data: JSON.stringify({email: editInfoFm.elements['email'].value}),
            url: "/GCB-thetaPay/gateway/auth/resetpassword",
            dataType: 'json',
            success: function (res) {
                setformMessage("success", res.message);
                setTimeout(() => {
                    setformMessage("", "");
                    location.reload();
                }, 1000)
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseJSON.error); 
                setformMessage("error", XMLHttpRequest.responseJSON.error);
            }
        });
    });

    const setformMessage = (type, message) => {
        formMessage.setAttribute("class", type);
        formMessage.innerText = message;
        stopLoading()
    }

    const setformMessage2 = (type, message) => {
        formMessage2.setAttribute("class", type);
        formMessage2.innerText = message;
        stopLoading()
    }

    const act_loadingbtn = () => {
        modalClBtn.setAttribute("disabled", "true");
        for(let element of aSbtns){
            element.setAttribute("disabled", "true");
                element.innerHTML = `
                    <div class="spinner-grow" style="width: 2rem; height: 2rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `
        }
    }

    const stopLoading = () => {
        modalClBtn.removeAttribute("disabled", "true");
        for(let element of aSbtns){
            element.removeAttribute("disabled");
            if(element.getAttribute("id") == "submitBtn"){
                element.innerHTML = `Save Changes`
            } else if (element.getAttribute("id") == "chPassBtn") {
                element.innerHTML = `Change Password`
            }else{
                element.innerHTML = `Change Pin Code`
            }
        };
    }
});