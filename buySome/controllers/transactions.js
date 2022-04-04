const Transactions = require('../models/transactions.js');

exports.webpaymenthook = () => {
    return async (req, res) => {
        const {
            txn_id,
            status
        } = req.body;

        try{
            if( !txn_id || !status){
                return res.status(400).json({
                    error: 'invalid data to webHook'
                })
            }

            await Transactions.create({
                id: txn_id, status
            }).then(() => {
                return res.json({
                    message: "success"
                })
            }).catch((e) => {
                logError(e)
                return res.status(500).json({
                    error: e.message
                })
            })

        } catch(e){
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}

// transaction by id
exports.txnById = () => {
    return async (req, res) => {
        const {id} = req.params;

        try {
            const txn = await Transactions.findByPk(id);
            return res.json(txn ? txn : {})
        } catch (error) {
            logError(e)
            return res.status(500).json({
                error: e.message
            })
        }
    }
}