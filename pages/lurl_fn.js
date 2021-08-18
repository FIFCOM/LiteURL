let $ = mdui.$;

function lurl_login() {
    let arr = {}
    arr['username'] = $("#username").val()
    arr['password'] = $("#password").val()
    let json_str = JSON.stringify(arr)
    console.log(md5(json_str))

}