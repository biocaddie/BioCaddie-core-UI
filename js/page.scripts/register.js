/**
 * Created by rliu1 on 3/7/16.
 */

$(document).ready(function(){
    $("#myModal").modal('show');
});

function saveconsent($email){
    $.ajax({
        url:'ajax/studyconsent.php',
        type:'post',
        data:{'email':$email},
        success:function(data, status){
            $('#myModal').modal('toggle');
            $("#thankyouModal").modal('show');
        },
        error:function(xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
        }
    }); // end ajax call
    }
