const fs = require('fs');

//log all errors into log file
function logError(error) {
    var head = "\n\n" + "Error [" + new Date().toLocaleString() + "]";
    var msg = head + "\n \t - " + error;

    fs.appendFile('log.txt', msg, function (err) {
        if (err) {
           throw err;
        }
    })
}

module.exports = {
    logError
}