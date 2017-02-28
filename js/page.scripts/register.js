/**
 * Created by rliu1 on 3/7/16.
 */

$(document).ready(function(){
    $("#myModal").modal('show');
});

function saveconsent($email){
    $("#myModal").modal('hide');
    $.ajax({
        url:'ajax/studyconsent.php',
        type:'post',
        data:{'email':$email},
        success:function(data, status){

            console.log(data.replace(/ /g,''));

            if(data.replace(/\s/g, "") =="ok"){
                $("#thankyouModal").modal('show');
                $("#myModal").modal('hide');
            }else{
                alert(data);
                $("#myModal").modal('hide');
            }

        },
        error:function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call
    }
