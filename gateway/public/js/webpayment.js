var formMessage = document.getElementById("_message");
var interForm = document.getElementById('interPay');
var sbtBtn = document.getElementsByClassName('submitBtn');
var cancelBtn = document.getElementsByClassName('cancelBtn');
var cardForm = document.getElementById('cardPayment');

// payment switch operation
function displayCardForm(){
    document.getElementsByClassName('choice_wp')[0].style.display = "none";
    document.getElementsByClassName('theta-to-theta')[0].style.display = "none";
    document.getElementsByClassName('cardPayment')[0].style.display = "block";
}

function displayInterForm(){
    document.getElementsByClassName('choice_wp')[0].style.display = "none";
    document.getElementsByClassName('theta-to-theta')[0].style.display = "block";
    document.getElementsByClassName('cardPayment')[0].style.display = "none";
}

function resetPage(){
    // document.getElementsByClassName('choice_wp')[0].style.display = "flex";
    // document.getElementsByClassName('theta-to-theta')[0].style.display = "none";
    // document.getElementsByClassName('cardPayment')[0].style.display = "none";

    location.reload();
}

// theta-to-theta Pay
interForm?.addEventListener('submit', function(e){
    e.preventDefault();

});