<script>
    $(window).load(function(){



        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) {
                return parts.pop().split(";").shift();
            }else{
                return false;
            }
        }

        if(getCookie('survey')==false) {
            $("#feedback-alert").removeClass('hidden')
            $("#hide").removeClass('hidden')
            $("#feedback-alert").show();
            $("#hide").show();

            $("#hide").click(function () {
                $("#feedback-alert").hide();
                $("#hide").html('Take the survey');
                document.cookie =  "survey = later;";
                location.reload();
            });
        }else if(getCookie('survey')=="later")
        {
            $("#hide").show();
            $("#feedback-alert").hide();
            $("#hide").html('Take the survey');
            $("#hide").click(function(){
                window.open("questionnaire.php","_blank");
            });
        }else if(getCookie('survey')=="no"){
            $("#feedback-alert").hide();
            $("#hide").hide();
        }

        $("#close").click(function(){
            $("#feedback-alert").hide();
            $("#hide").hide();
            document.cookie =  "survey = no;";
            location.reload();
        });
    });
</script>


<div class="alert alert-info hidden" id="feedback-alert" style="margin-bottom: 0px; border: none;padding-left: 50px;padding-right: 50px">
    <i class="remove glyphicon glyphicon-remove-sign glyphicon-white pull-right" style="cursor: pointer;" id = "close"></i>
    <i>Dear <strong>DataMed</strong> user: </i> DataMed prototype(v2.0) is being developed for the NIH BD2K Data Discovery Index (DDI) by the bioCADDIE project team. DataMed, once completed, will be of use to the scientific community to allow users to search for and find data across different repositories in one space.
    We are soliciting your feedback to help us shape DataMeds' future development. Please take a moment to answer this brief <a target="_blank" href="questionnaire.php"><i><strong>Survey Form</strong></i></a>  and give us your thoughts. We believe your voice will be a critical addition to the development of the bioCADDIE prototype.
    <i>Thank you, from the bioCADDIE team</i>.


</div>
<div class="wrapper hidden" style="text-align: center;" id = "hide">
<button  style="border: none;background-color: #d9edf7;
        color: #31708f; height: 30px; border-bottom-left-radius:10px ;border-bottom-right-radius:10px ;
        background-image: linear-gradient(to bottom,#d9edf7 0,#b9def0 100%)">Ask me later</button>
    </div>

