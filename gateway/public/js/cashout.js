$(document).ready(function () {
    var token = document.getElementsByTagName("script")[2].classList[0];
    var tp_fm = document.getElementById("tp_fm");
    var formMessage = document.getElementById("_message");
    var sbtBtn = document.getElementById('submitBtn');
    var requestbody = {};

    tp_fm?.addEventListener("submit", (e) => {
        e.preventDefault();
        setformMessage("", "");
        act_loadingbtn();

        requestbody.bankName = tp_fm.elements['bankName'].value;
        requestbody.bankAcctNo = tp_fm.elements['bankAcctNo'].value;
        requestbody.pinCode = tp_fm.elements['pinCode'].value;
        requestbody.amount = tp_fm.elements['amount'].value;

        sendData();
    })

    const sendData = () => {
        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/client/cashout",
            data: JSON.stringify(requestbody),
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                xhr.setRequestHeader('Authorization', "Bearer " + token);
            },
            success: function (res) {
                tp_fm.reset();
                setformMessage("success", "Cashout completed successfully");
            },
            error: function (XMLHttpRequest) {
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

});