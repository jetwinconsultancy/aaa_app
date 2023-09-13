/* Add here all your JS customizations */

function checkbox(x) {
    return '<center><input class="checkbox multi-select" type="checkbox" name="val[]" value="' + x + '" /></center>';
}

function user_statusx(x) {
    var y = x.value.split("__");
    return y[0] == 1 ?
    '<a href="'+site.base_url+'auth/deactivate/'+ y[1] +'" data-toggle="modal" data-target="#myModal"><span class="label label-success"><i class="fa fa-check"></i> '+lang['active']+'</span></a>' :
    '<a href="'+site.base_url+'auth/activate/'+ y[1] +'"><span class="label label-danger"><i class="fa fa-times"></i> '+lang['inactive']+'</span><a/>';
}

function user_status(x) {
	// console.log(x);
    var y = x.split("__");
	// alert(x);
    return y[0] == 1 ?
    '<span class="label label-success"><i class="fa fa-check"></i> Active</span>' :
    '<span class="label label-danger"><i class="fa fa-times"></i> Inactive</span>';
}

//Artemis encrypt/decrypt
var CryptoJSAesJson = {
    stringify: function (cipherParams) { // create json object with ciphertext 
        var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};// optionally add iv and salt 
        if (cipherParams.iv) j.iv = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString(); // stringify json object 
        return JSON.stringify(j);
    },
    parse: function (jsonStr) { // parse json string 
        var j = JSON.parse(jsonStr);// extract ciphertext from json object, and create cipher params object
        var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
        if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
        if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
        return cipherParams;
    }
}