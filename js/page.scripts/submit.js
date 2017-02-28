/**
 * Created by xchen2 on 4/19/16.
 */

function showinputbox(that){
    if (that.value == "Other"){
        document.getElementById("otherDatatype").style.display = "block";
    } else {
        document.getElementById("otherDatatype").style.display = "none";
    }
}

function showinputbox_identifiers(){
    if (document.getElementById("other_id_choose").checked){
        document.getElementById("other_identifiers").style.display = "block";
    } else {
        document.getElementById("other_identifiers").style.display = "none";
    }
}

function showinputbox_metadata(that){
    if (that.value == "Yes"){
        document.getElementById("metadata_format").style.display = "block";
    } else {
        document.getElementById("metadata_format").style.display = "none";
    }
}

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
});

function refreshCaptcha()
{
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}