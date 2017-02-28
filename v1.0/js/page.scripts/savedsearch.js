
var numCheckedBox = 0;
window.EnableDelete = function(val)
{
    var sbmt = document.getElementById("btn-delete");

    if (val.checked == true)
    {
        numCheckedBox++;
        sbmt.disabled = false;
    }
    else
    {
        numCheckedBox--;
        if(numCheckedBox==0){
            sbmt.disabled = true;
        }

    }
}


// delete saved searches
$(function(){
    $('#btn-delete').on('click',function(e){
        e.preventDefault();

        var val = [];
        $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
        });

        $.ajax({
            url:'ajax/deletesearch.php',
            type:'post',
            data:{'query':val},
            success:function(data, status){
                if (data.trim().substring(0,2) == "ok") {
                    $('#myModal').modal('toggle')
                    $('#myModal').on('hidden.bs.modal', function () {
                        window.location.reload();
                    })
                }else{
                    alert(data);
                }
            },
            error:function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call


    })

});