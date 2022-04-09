$(document).ready(() => {

    var token = document.getElementsByTagName("script")[2].classList[0];
    var num_txn = document.getElementById("num_txn");
    var txn_amt = document.getElementById("txn_amt");
    var balance = document.getElementById("balance");
    var num_cus = document.getElementById("num_cus");
    var txnTable = document.getElementById("_txn_tb");

    const getSummary = () => {
        $.ajax({
            type: "GET",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/admin/getSummary",
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                xhr.setRequestHeader('Authorization', "Bearer " + token);
            },
            success: function (response) {
                num_txn.innerText = `${response.txn_num}`;
                num_cus.innerText = `${response.num_cus}`;
                txn_amt.innerText = `GHS ${parseFloat(response.txn_amt).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")}`
                balance.innerText = `GHS ${parseFloat(response.balance).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")}`
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseJSON.error);
            }
        });
    }

    const getTransaction = () => {
        $.ajax({
            type: "GET",
            processData: false,
            contentType: "application/json; charset=utf-8",
            cache: false,
            url: "/GCB-thetaPay/gateway/admin/getTxns",
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                xhr.setRequestHeader('Authorization', "Bearer " + token);
            },
            success: function (response) {
                response.sort((a, b) => (new Date(b.createdAt)) - (new Date(a.createdAt)));
                response.forEach((data, key) => {
                    $(txnTable).append(
                        `<tr>
                            <td>${key + 1}</td>
                            <td>${data.firstname} ${data.lastname}</td>
                            <td>${data.type}</td>
                            <td>${data.currency}</td>
                            <td>${parseFloat(data.amount).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")}</td>
                            <td>
                                <span class="badge 
                                    ${data.status == "success" ? "badge-success" : "" }
                                    ${data.status == "failed" ? "badge-danger" : "" }
                                    ${data.status == "pending" ? "badge-warning" : "" } 
                                    rounded-pill d-inline"
                                >
                                    ${data.status.toUpperCase()}
                                </span>
                            </td>
                            <td>${data.description}</td>
                            <td>${data.medium.toUpperCase()}</td>
                            <td>${new Date(data.createdAt).toUTCString()}</td>
                        </tr>`
                    )
                });
            },
            error: function (XMLHttpRequest) {
                console.log(XMLHttpRequest.responseJSON.error);
            }
        });
    }

    // get summary info
    getSummary();

    // get transactions
    getTransaction();
})