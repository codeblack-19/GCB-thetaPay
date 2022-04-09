$(document).ready(function () {
    var logoutbtn = document.getElementById("logoutBtn");
    var token = document.getElementsByTagName("script")[2].classList[0];

    logoutbtn?.addEventListener("click", () => {

        $.ajax({
            type: "PUT",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/auth/logout",
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                xhr.setRequestHeader('Authorization', "Bearer " + token);
            },
            dataType: 'json',
            success: function (res) {
                location.reload();
            }, error: function (XMLHttpRequest){
                console.log(XMLHttpRequest.responseJSON.error);
            }
        });
    })
});