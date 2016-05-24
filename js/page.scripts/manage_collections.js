
// For collections
var numCheckedBox = 0;
window.EnableDelete = function(val)
{
    var sbmt = document.getElementById("btn-delete-collection");
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

$(function(){
    $('#btn-delete-collection').on('click',function(e){
        e.preventDefault();

        var val = [];
        $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
            alert(val[i]);
        });

        $.ajax({
            url:'ajax/deletecollections.php',
            type:'post',
            data:{'query':val},
            success:function(data, status){

                $('#myModal').modal('toggle')
                $('#myModal').on('hidden.bs.modal', function () {
                    window.location.reload();
                })
            },
            error:function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call


    });


    // For collection items
    var numCheckedBox2 = 0;
    window.EnableDeleteItem = function(val)
    {
        var sbmt = document.getElementById("btn-delete-items");
        if (val.checked == true)
        {
            numCheckedBox2++;
            sbmt.disabled = false;
        }
        else
        {
            numCheckedBox2--;
            if(numCheckedBox2==0){
                sbmt.disabled = true;
            }

        }
    }

    $('#btn-delete-items').on('click',function(e){
        e.preventDefault();
        var val = [];
        $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
        });

        $.ajax({
            url:'ajax/deletecollectionitems.php',
            type:'post',
            data:{'query':val},
            success:function(data, status){

                $('#myModal').modal('toggle')
                $('#myModal').on('hidden.bs.modal', function () {
                    window.location.reload();
                })
            },
            error:function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        }); // end ajax call


    })

});