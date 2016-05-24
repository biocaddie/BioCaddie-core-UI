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