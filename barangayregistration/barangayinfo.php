<html>
  <head>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <style type="text/css">
      .row{
        margin:10px;
      }
      a{
        color: blue;
        cursor: pointer;
      }
      .headerRow{
        padding-top:15px;
      }
      .labelHeader{
        font-weight: 600;
        font-size: 13px;
      }
      #loading{
         position:fixed; left:0px; top:0px; width:100%; height:100%; z-index:9999; background:url('../img/loader.gif') 50% 50% no-repeat rgba(249,249,249,0.8); /* http://www.animatedimages.org/data/media/679/animated-taxi-image-0004.gif */
      }
    </style>
  </head>
  <!---------  import save barangay api ------------>
  <?php include_once("savebarangayinfo.php"); ?>
  <body>
    <div class="container-fluid">
      <form method="post" action="" onsubmit="$('#loading').show();">
        <div id="loading" style="display:none;"></div>      
        <?php 
          $query = " SELECT * FROM barangay_inhabitants_head WHERE `user_id`='$userId'";
          $data=$obj->MySQLSelect($query);
        ?>
        <input type="hidden" id="id" name="id" value="<?=$data[0]['id']?>">
        <div class="row">
          <div class="col-sm-12">
            <center>
              <h1>Registry of Barangay Inhabitants</h1>
            </center>
          </div>
        </div>
        <div id="accordion" class="">
          <?php  if($_SESSION['msg']=="success"){  ?>
            <div class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              Thank you.   Your household information was successfully submitted.  For any questions please contact support@aiocity.com 
            </div>
        
          <? $_SESSION['msg']=""; } ?>

          <div class="panel-group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" href="JavaScript:Void(0);">Head of the Family</a>
                </h4>
              </div>

              <div class="head collapse show" id="collapse1" >
                
                <!----------- importing barangay head form --------->
                <? 
               
                include_once('common_barangay_head_form.php'); 
                
                ?>  
              </div>

            </div>
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" href="#collapse2">Family Members Information</a>
              </h4>
            </div>
            <div class="FamilyMembersInformation" id="collapse2"   data-parent="#accordion">
               
              <!----------- importing barangay member form ---------> 
              <?php 
               
                
                
                
                

              include_once("common_barangay_member_form.php"); 
              

              ?>  
            </div>
            <div class="row">
              <div class="col-sm-12">
                <br>
                <input style="float: right;" onclick="validate()" type="button" name="add more" value="Add More" class="btn btn-xs btn-primary" id="addmore">
                <br>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <input type="checkbox" name="termandconditions" required> <label>I agree to the <a href="tc.php?userId=<?=$userId?>">Terms and Conditions</a></label> 
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div style="float: left; width: 50%"> 
                  <Center> 
                    <input type="submit" style="width:50%" onclick="validate()" class="btn btn-primary" name="submit" value="Submit">
                  </Center>
                </div>
                <div style="float: right; width: 50%"> 
                  <Center> 
                    <input type="button" style="width:50%" onclick="document.location.reload(true)" class="btn btn-warning" name="submit" value="Cancel">
                  </Center>
                </div>
              </div>
            </div>
            <br>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
<script type="text/javascript">
function  validate(){
  $("div").removeClass("collapse");
}
function deleteMember(e){
  debugger
    Swal.fire({
    title: 'Are you sure you want to delete this Family Member Information?',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.value) {
      $(e).parent(".deleteDiv").parent(".row").parent(".member").remove();
      var MemberSeniorCitizen = 0;
      var MemberIsStudentEnrolled = 0;
      var memberWhereAcquired=0;
      var memberMembership=0;
      var memberHealthBenefits=0;
      for (var j =0;j< $(".Member").length; j++) 
      {

        $(".MemberSeniorCitizen").eq(MemberSeniorCitizen).attr("name","MemberSeniorCitizen_"+j);MemberSeniorCitizen++;
        $(".MemberSeniorCitizen").eq(MemberSeniorCitizen).attr("name","MemberSeniorCitizen_"+j);MemberSeniorCitizen++;

        $(".MemberIsStudentEnrolled").eq(MemberIsStudentEnrolled).attr("name","MemberIsStudentEnrolled_"+j);MemberIsStudentEnrolled++;
        $(".MemberIsStudentEnrolled").eq(MemberIsStudentEnrolled).attr("name","MemberIsStudentEnrolled_"+j);MemberIsStudentEnrolled++;

        $(".MemberWhereAcquired").eq(memberWhereAcquired).attr("name","MemberWhereAcquired_"+j);memberWhereAcquired++;
        $(".MemberWhereAcquired").eq(memberWhereAcquired).attr("name","MemberWhereAcquired_"+j);memberWhereAcquired++;

        $(".MemberMembership").eq(memberMembership).attr("name","MemberMembership_"+j);memberMembership++;
        $(".MemberMembership").eq(memberMembership).attr("name","MemberMembership_"+j);memberMembership++;
        $(".MemberMembership").eq(memberMembership).attr("name","MemberMembership_"+j);memberMembership++;
        $(".MemberMembership").eq(memberMembership).attr("name","MemberMembership_"+j);memberMembership++;
        $(".MemberMembership").eq(memberMembership).attr("name","MemberMembership_"+j);memberMembership++;


        $(".MemberHealthBenefits").eq(memberHealthBenefits).attr("name","MemberHealthBenefits_"+j);memberHealthBenefits++;
        $(".MemberHealthBenefits").eq(memberHealthBenefits).attr("name","MemberHealthBenefits_"+j);memberHealthBenefits++;
        $(".MemberHealthBenefits").eq(memberHealthBenefits).attr("name","MemberHealthBenefits_"+j);memberHealthBenefits++;
        $(".MemberHealthBenefits").eq(memberHealthBenefits).attr("name","MemberHealthBenefits_"+j);memberHealthBenefits++;
      }

       Swal.fire(
        'Deleted!',
        '',
        'success'
      )
    }
  })
}
  
$(document).ready(function(){
  var member=$(".Member").eq(0).html();
  $("#addmore").click(function(){
    debugger
      var i=$(".Member").length;
      var 
          membertemp = $(member).find(".MemberSeniorCitizen").attr("name","MemberSeniorCitizen_"+i).prevObject;
          membertemp = $(member).find(".MemberIsStudentEnrolled").attr("name","MemberIsStudentEnrolled_"+i).prevObject;
          membertemp= $(member).find(".MemberWhereAcquired").attr("name","MemberWhereAcquired_"+i).prevObject;
          membertemp= $(membertemp).find(".MemberMembership").attr("name","MemberMembership_"+i).prevObject;
          membertemp=   $(membertemp).find(".MemberHealthBenefits").attr("name","MemberHealthBenefits_"+i).prevObject;
          membertemp = $(membertemp).find(".GenerateBarangayIDNumber").attr("id","GenerateBarangayIDNumber"+i).prevObject;
          membertemp=   $(membertemp).find("input[type=text]").val("").prevObject;
          membertemp=   $(membertemp).find("input[type=email]").val("").prevObject;
          membertemp=   $(membertemp).find("input[type=number]").val("").prevObject;
          membertemp=   $(membertemp).find("option").removeAttr("selected").prevObject;
          membertemp=   $(membertemp).find("input[type=radio]").removeAttr("checked").prevObject;
      var div=document.createElement('div');
      div.classList.add("Member");
      //div.innerHTML =member;
      membertemp.each(function(){
              div.append(this);
      });
      
      $(".FamilyMembersInformation").append(div);
      var v = $("#GenerateBarangayIDNumber").val();
      $("#GenerateBarangayIDNumber"+i).val(v+"_"+makeid(5));

      setTimeout(function(){    
        $(".datepicker").datepicker(
          { 
            changeMonth: true,
            changeYear: true,
            endDate: "today",
            maxDate: new Date() 
          }); }, 3000);
  });

  $("#btn-generate-tag-number").click(function(){
    generateTag();
  });

  $("#btn-generate-barangay-id").click(function(){
    generateId();
  });
  $(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    endDate: "today",
    maxDate: new Date()
  });
  function generateId(){
    $.ajax({
      type: 'GET',
      url: "generateId.php?type=id",
      success: function(resultData) { 
        $("#GenerateBarangayIDNumber").val(resultData);
        $("#GenerateBarangayIDNumber0").val(resultData+"_"+makeid(5));
        },error:function (err)
        {
          alert(err);
        }
    });
  }

  function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  function generateTag(){
    $.ajax({
      type: 'GET',
      url: "generateId.php?type=tag",
      success: function(resultData) { 
        $("#GenerateTagNumber").val(resultData);
        },error:function (err)
        {
          alert(err);
        }
    });
  }

  // $(".HealthBenefits").change(function(){
  //   debugger
  //   if($(this).val().toLowerCase()=="none")
  //     $("#OtherHealthBenefits").css("display","");
  //   else
  //     $("#OtherHealthBenefits").css("display","none");
  // });

  // $(".Membership").change(function(){
  //   debugger
  //   if($(this).val().toLowerCase()=="other")
  //     $("#OtherMembership").css("display","");
  //   else
  //     $("#OtherMembership").css("display","none");
  // });

  // $(".MemberHealthBenefits").change(function(){
  //   debugger
  //   if($(this).val().toLowerCase()=="other health insurance")
  //    $(this).parent("div").parent("div").next("div").find(".OtherMemberHealthBenefits").css("display","");
  //   else
  //    $(this).parent("div").parent("div").next("div").find(".OtherMemberHealthBenefits").css("display","none");
  // });


  if($("#id").val()==""){
    generateId();
    generateTag();
  }else{
    if($("#GenerateBarangayIDNumber0").val()==""){
      var resultData = $("#GenerateBarangayIDNumber").val();
      $("#GenerateBarangayIDNumber0").val(resultData+"_"+makeid(5));
    }
  }
});
</script>