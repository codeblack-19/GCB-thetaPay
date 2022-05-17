var formMessage, interForm, sbtBtn, cancelBtn, cardForm, loader;
var FmBody = {};


// display credit card pay form
function displayCardForm(){
    document.getElementsByClassName('selections')[0].innerHTML = `
        <div class="cardPayment">
                <p id="_message"></p>
                <form id="cardPayment" method="post" action="">
                    <div class="field-container">
                        <label for="name">Name</label>
                        <input id="name" maxlength="20" type="text" placeholder="e.g John Doe">
                    </div>
                    <div class="field-container">
                        <label for="cardnumber">Card Number</label>
                        <input id="cardnumber" type="number" pattern="[0-9]*" inputmode="numeric" maxlength="16" placeholder="XXXX XXXX XXXX XXXX">
                    </div>
                    <div class="field-container flexdt">
                        <label for="expirationdate">Expiration (mm/yy)</label>
                        <input id="expiry_mm" type="text" pattern="[0-9]*" maxlength="2" inputmode="numeric" placeholder="MM">
                        <input id="expiry_yy" type="text" pattern="[0-9]*" maxlength="2" inputmode="numeric" placeholder="YY">
                    </div>
                    <div class="field-container">
                        <label for="securitycode">CVV</label>
                        <input id="securitycode" type="text" pattern="[0-9]*" maxlength="4" inputmode="numeric" placeholder="XXX">
                    </div>
                    <div class="btns">
                        <button type="submit" id="submitBtn">Submit</button>
                        <button type="button" id="cancelBtn" onclick="resetPage()">Reset</button>
                        <button class="btn btn-primary" type="button" disabled id="loader">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Processing...
                        </button>
                    </div>
                </form>
            </div>
    `
    cardForm = document.getElementById('cardPayment');
    setElements();
    cardForm.addEventListener('submit', (e) => submitCreditCard(e));
}

// display interpay form
function displayInterForm(){
    document.getElementsByClassName('selections')[0].innerHTML = `
        <div class="theta-to-theta">
                <p id="_message"></p>
                <form id="interPay" method="post" action="">
                    <input type="number" autocomplete="off" name="accountNo" maxlenth="10" placeholder="Account Number" />
                    <input type="password" autocomplete="off" name="pinCode" maxlength="6" placeholder="Pin Code" />
                    <div class="btns">
                        <button type="submit" id="submitBtn">Submit</button>
                        <button type="button" id="cancelBtn" onclick="resetPage()">Reset</button>
                        <button class="btn btn-primary" type="button" disabled id="loader">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Processing...
                        </button>
                    </div>
                </form>
            </div>
    `
    interForm = document.getElementById('interPay');
    setElements();
    interForm.addEventListener('submit', (e) => submitInterForm(e));
}

// set dom element to variables
function setElements(){
    formMessage = document.getElementById("_message");
    sbtBtn = document.getElementById('submitBtn');
    cancelBtn = document.getElementById('cancelBtn');
    loader = document.getElementById('loader');
}

// reset payment page
function resetPage(){
    location.reload();
}

// set form message => error & success
const setformMessage = (type, message) => {
    formMessage.setAttribute("class", type);
    formMessage.innerText = message;
    loader.style.display = "none";
    sbtBtn.style.display = "block";
    cancelBtn.style.display = "block";
}

// theta-to-theta Pay
function submitInterForm(e){
    e.preventDefault();
    sbtBtn.style.display = "none";
    cancelBtn.style.display = "none";
    loader.style.display = "block";


    FmBody['accountNo'] = interForm.elements['accountNo'].value;
    FmBody['pinCode'] = interForm.elements['pinCode'].value;
    FmBody['signature'] = new URLSearchParams(window.location.search).get('signature');
    FmBody['_id'] = new URLSearchParams(window.location.search).get('_id');

    if(FmBody.accountNo == ""){
        return setformMessage("error", "Account Number is required");
    }else if(FmBody.pinCode == ""){
        return setformMessage("error", "Pin Code is required");
    }else if(FmBody.pinCode.length == 0){
        return setformMessage("error", "Pin Code must be 6 digits");
    }else{
        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/transactions/intertransfer",
            data: JSON.stringify(FmBody),
            dataType: 'json',
            success: function () {
                interForm.reset();
                document.getElementsByTagName("main")[0].innerHTML = `
                    <div class="verify_notice">
                        <h3>Transaction Completed Succesfully</h3>
                        <p class="check"><span>&#10003;</span></p>
                        <a href="/GCB-thetaPay/gateway/client">
                            <button>Go To Portal</button>
                        </a>
                    </div>
                `
                setTimeout(() => {
                    window.close();
                }, 10000);
            }, error: function (XMLHttpRequest){
                setformMessage("error", XMLHttpRequest.responseJSON.error);
            }
        });
    }
};

// credit card payment
function submitCreditCard(e){
    e.preventDefault();
    sbtBtn.style.display = "none";
    cancelBtn.style.display = "none";
    loader.style.display = "block";

    FmBody['name'] = cardForm.elements['name'].value;
    FmBody['cardNo'] = cardForm.elements['cardnumber'].value;
    FmBody['expiry_mm'] = cardForm.elements['expiry_mm'].value;
    FmBody['expiry_yy'] = cardForm.elements['expiry_yy'].value;
    FmBody['cvv'] = cardForm.elements['securitycode'].value;
    FmBody['signature'] = new URLSearchParams(window.location.search).get('signature');
    FmBody['_id'] = new URLSearchParams(window.location.search).get('_id');

    if(FmBody.name == ""){
        return setformMessage("error", "Card holder name is required");
    }else if(FmBody.cardNo == ""){
        return setformMessage("error", "Card number is required");
    }else if(FmBody.cardNo.length != 16){
        return setformMessage("error", "Card number must be 16 digits");
    }else if(FmBody.expiry_mm == ""){
        return setformMessage("error", "Invalid Expiration month")
    }else if(FmBody.expiry_yy == ""){
        return setformMessage("error", "Expiration year required");
    }else if(FmBody.expiry_mm.length > 2 || FmBody.expiry_yy.length > 2){
        return setformMessage("error", "Invalid Expiration month or year");
    }else if(FmBody.cvv == ""){
        return setformMessage("error", "CVV is required");
    }else if(FmBody.cvv.length != 3){
        return setformMessage("error", "Invalid cvv number");
    }else{
        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/transactions/cardpayment",
            data: JSON.stringify(FmBody),
            dataType: 'json',
            success: function () {
                cardForm.reset();
                document.getElementsByTagName("main")[0].innerHTML = `
                    <div class="verify_notice">
                        <h3>Transaction Completed Succesfully</h3>
                        <p class="check"><span>&#10003;</span></p>
                        <a href="/GCB-thetaPay/gateway/client">
                            <button>Go To Portal</button>
                        </a>
                    </div>
                `
                setTimeout(() => {
                    window.close();
                }, 10000);
            },
            error: function (XMLHttpRequest) {
                setformMessage("error", XMLHttpRequest.responseJSON.error);
            }
        });
    }
}