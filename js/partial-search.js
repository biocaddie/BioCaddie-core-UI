     var typeString="";
     function mysearch(type){

      switch(type){
        case 1:
        $("#query1").val($("#query").val());   
        $("#protein_form").submit();
        break;
        case 2:
        $("#query2").val($("#query").val());   
        $("#phenotype_form").submit();
        break;
        case 3:
        $("#query3").val($("#query").val()); 
        $("#gene_expression_form").submit();
        break;
        case 4:
        $("#query4").val($("#query").val()); 
        $("#sequence_form").submit();
        break;
      }
    }

    $(document).ready(function(){
      var delay = (function(){
        var timer = 0;
        return function(callback, ms){
          clearTimeout (timer);
          timer = setTimeout(callback, ms);
        };
      })();



      $(".js-ajax-php-json").keyup(function(){
        delay(function(){
          var data = {
            "action": "test",
            "query":   $("#query").val()
          };
          data = $(this).serialize() + "&" + $.param(data);

          $.ajax({
            type: "GET",
            dataType: "json",
            url: "response.php", //Relative or absolute path to response.php file
            data: data,
            success: function(data) {

              $( "#dialog" ).dialog({
                width:800, //$("#query").width/2,
                height:400,
                dialogClass: 'noTitleStuff' 
              });

              var text="<div class=\"row\"><h5>";


              var href="\"datasource.php?query=" + $("#query").val() + "\"";
              var j=1
              for(var type in data['datatypes']){
                text += "<div class=\"col-md-5\"><ul class=\"types\"><h4><a href=\"javascript:;\" onclick=\"mysearch(" + j+ ")\">" + type + " (" + data['datatypes'][type] + ')</a></h4><h5>';
                switch(type){
                  case "protein":
                  text += "<li><a class=\"repositoryLabel\" href=\"result.php?query="+$("#query").val()+"&datasource="+data['repository'][1]["id"]+"\">" + data['repository'][1]['show_name'] + ' ('+ data['repository'][1]["num"] + ')</a></li>'
                  break;
                  case "phenotype":
                  text += "<li><a class=\"repositoryLabel\" href=\"result.php?query="+$("#query").val()+"&datasource="+data['repository'][0]["id"]+"\">" + data['repository'][0]['show_name'] + ' ('+ data['repository'][0]["num"] + ')</a></li>'
                  break;
                  case "gene expression":
                  for(var i=2; i<6; i++){
                    text += "<li><a class=\"repositoryLabel\" href=\"result.php?query="+$("#query").val()+"&datasource="+data['repository'][i]["id"]+"\">" + data['repository'][i]['show_name'] + ' ('+ data['repository'][i]["num"] + ')</a></li>'
                  }
                  break;
                  case "sequence":
                  text += "<li><a class=\"repositoryLabel\" href=\"result.php?query="+$("#query").val()+"&datasource="+data['repository'][6]["id"]+"\">" + data['repository'][6]['show_name'] + ' ('+ data['repository'][6]["num"] + ')</a></li>'
                  break;
                }
                text += "</ul></div>";
                j++;
              }


              text += "</h5></div>";



              $( "#dialog" ).html(text);

              $(".ui-dialog").position({ 

                my:"left bottom",
                at:"left top",
                of: $("#query")
              });

              $("#dialog").position({ 

                my:"center bottom",
                at:"center bottom",
                of: $(".ui-dialog")
              });


              $("#query").focus();
            }
          });

     return false;

   },300);

   });
   });

