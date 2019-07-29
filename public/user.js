function getUserId() {
    return localStorage.getItem('userId');
}

function wsLogin(userId, ws) {
    ws.send("user.login:" + JSON.stringify({user_id: userId}))
}