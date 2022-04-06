$(document).ready(function () {
    var token = document.getElementsByTagName("script")[2].classList[0];
    var editInfoFm = document.getElementById("editInfoFm");
    var formMessage = document.getElementById("_message");
    var sbtBtn = document.getElementById('submitBtn');
    var chPBtn = document.getElementById('chPassBtn');
    var aSbtns = document.getElementsByClassName('acctSettingbtns');

    editInfoFm?.addEventListener("submit", (e) => {
        e.preventDefault();
        setformMessage("", "");
        act_loadingbtn();

        // let email = editInfoFm.elements['email'].value;
        // let password = editInfoFm.elements['password'].value;

        // sendData(email, password);

        setInterval(() => {
            stopLoading();
        }, 2000)
    })

    const setformMessage = (type, message) => {
        formMessage.setAttribute("class", type);
        formMessage.innerText = message;
        stopLoading()
    }

    const act_loadingbtn = () => {
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
        for(let element of aSbtns){
            element.removeAttribute("disabled");
            if(element.getAttribute("id") == "submitBtn"){
                element.innerHTML = `Save Changes`
            }else{
                element.innerHTML = `Change Password`
            }
        };
    }

});