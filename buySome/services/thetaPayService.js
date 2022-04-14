require('dotenv').config();
const { default : axios } = require('axios');

const refundTxn = async (txn_id) => {

    var refund = await axios({
        method: 'POST',
        url: `${process.env.GCB_THETAPAY_API}/transactions/webpayment_refund`,
        data: {
            txn_id
        }, 
        headers: {
            "Authorization": `Bearer ${process.env.ThetaPay_GATEWAY_PRIVATE_KEY}`
        }
    }).then((res) => {
        return true;
    }).catch((e) => {
        if (e.response.data) {
            return e.response.data.error;
        }else{
            return false;
        }
    })

    return refund
}

module.exports = {
    refundTxn
}