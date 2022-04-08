$(document).ready(function () {
    var modalClBtn = document.getElementById('modalClBtn');
    var chfgPsFm = document.getElementById("chfgPsFm");
    var formMessage2 = document.getElementById("_message2");
    var sbtfgPsbtn = document.getElementById('sbtfgPsbtn');

    chfgPsFm.addEventListener("submit", (e) => {
        e.preventDefault();
        setformMessage2("", "");
        act_loadingbtn2();

        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            data: JSON.stringify({
                email: chfgPsFm.elements['fgEmail'].value
            }),
            url: "/GCB-thetaPay/gateway/auth/resetpassword",
            dataType: 'json',
            success: function (res) {
                setformMessage2("text-success text-center", res.message);
                setTimeout(() => {
                    setformMessage2("", "");
                    location.reload();
                }, 2000)
            },
            error: function (XMLHttpRequest) {
                setformMessage2("text-danger text-center", XMLHttpRequest.responseJSON.error);
            }
        });
    });

    modalClBtn.addEventListener("click", (e) => {
        setformMessage2("", "");
        chfgPsFm.reset();
    })

    const setformMessage2 = (type, message) => {
        formMessage2.setAttribute("class", type);
        formMessage2.innerText = message;
        stopLoading2()
    }

    const act_loadingbtn2 = () => {
        modalClBtn.setAttribute("disabled", "true");
        sbtfgPsbtn.setAttribute("disabled", "true");
        sbtfgPsbtn.innerHTML = `
                <div class="spinner-grow" style="width: 2rem; height: 2rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `
    }

    const stopLoading2 = () => {
        modalClBtn.removeAttribute("disabled", "false");
        sbtfgPsbtn.removeAttribute("disabled");
        sbtfgPsbtn.innerHTML = `login`
    }
});