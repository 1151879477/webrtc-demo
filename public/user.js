function getUserId() {
    return localStorage.getItem('userId');
}

function wsLogin(userId, ws) {
    ws.send("user.login:" + JSON.stringify({user_id: userId}))
}


function createPeerConnection() {
    var mediaConstraints = {};
    var string = "stun:stun1.l.google.com:19302\n" +
        "stun:stun2.l.google.com:19302\n" +
        "stun:stun3.l.google.com:19302\n" +
        "stun:stun4.l.google.com:19302\n" +
        "stun:23.21.150.121\n" +
        "stun:stun01.sipphone.com\n" +
        "stun:stun.ekiga.net\n" +
        "stun:stun.fwdnet.net\n" +
        "stun:stun.ideasip.com\n" +
        "stun:stun.iptel.org\n" +
        "stun:stun.rixtelecom.se\n" +
        "stun:stun.schlund.de\n" +
        "stun:stunserver.org\n" +
        "stun:stun.softjoys.com\n" +
        "stun:stun.voiparound.com\n" +
        "stun:stun.voipbuster.com\n" +
        "stun:stun.voipstunt.com\n" +
        "stun:stun.voxgratia.org\n" +
        "stun:stun.xten.com"
    let arr = string.split("\n")
    return new RTCPeerConnection({
        iceServers: [
            {"urls": arr}
        ]
    }, mediaConstraints);
}