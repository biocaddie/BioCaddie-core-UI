// Create a new ClientJS object
var client = new ClientJS();

// Get the client's fingerprint id
var clientID = client.getFingerprint();

//Store in browser local storage for persistence
if (window.localStorage) {
    localStorage.setItem('utest', clientID);
}

var d = new Date();
d.setTime(d.getTime() + (28 * 24 * 60 * 60 * 1000)); //Expires in 28 days
newCookie = "utest="+clientID+"; expires="+d.toUTCString()+"; path=/"
//Write new cookie
document.cookie=newCookie;


$(document).click(function(e) {
    //do something
    var items = new Array();

    $(e.target).each(function() {
        var item = {classname: this.className, content: $(this).text()};
        items.push(item);
    });

    actionTime = Date.now();
    actionText = "";
    actionID = e.target.id;
    actionURL = document.documentURI;
    actionX = e.pageX;
    actionY = e.pageY;

    var data = {
        "cookieID" : clientID,
        "actionTime" : actionTime,
        "actionType" : "click",
        "actionID" : actionID ,
        "actionText" : actionText,
        "actionX" : actionX,
        "actionY": actionY,
        "actionURL" : actionURL,
    };

    sendToLog(data);

});

$(document).keypress(function(e) {

    actionTime = Date.now();
    actionText = e.which + "," + e.key + "," + e.charcode;
    actionID = e.target.id;
    actionURL = document.documentURI;
    var data = {
        "cookieID" : clientID,
        "actionTime" : actionTime,
        "actionType" : "keypress",
        "actionID" :actionID ,
        "actionText" : actionText,
        "actionX" : "",
        "actionY": "",
        "actionURL" : actionURL,
    };

    sendToLog(data);

});

function sendToLog(data){
    $.ajax({
        method:"POST",
        url:"ajax/usertesting.php",
        data:data,
    });
}