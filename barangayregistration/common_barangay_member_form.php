<?


  $z=0;
  $query = " SELECT * FROM barangay_inhabitants_member WHERE `head_id` = '".$data[0]['id']."'";  
  $data=$obj->MySQLSelect($query);
  $count= count($data)>0 ? count($data):1;
  for ($i=0; $i <$count; $i++) { 
?>
  <div class="Member">
    <br><hr>
    <div class="row">
      <div class="col-sm-12 deleteDiv">
        <input type="button" style="float: right;" onclick="deleteMember(this)" class="btn btn-sm btn-danger delete"  name="delete" value="Delete Family Member" id="delete">
      </div>
    </div>
    <div class="row headerRow">
      <div class="col-sm-12">
        <label>Barangay ID Number </label>
      </div>
    </div>
<div class="row">
<div class="col-sm-12">
<input class="GenerateBarangayIDNumber form-control" type="text" readonly  placeholder="Barangay ID Number" id="GenerateBarangayIDNumber<?=$i?>"  value="<?=$data[$i]['gen_barangay_ID']?>"  required class="form-control" name="GenerateBarangayIDNumber[]">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>Name of Member of Family</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Surname</label>
<input type="text" placeholder="Surname" required value="<?=$data[$i]['sur_name']?>"  class="form-control" name="MemberSurname[]" id="MemberSurname">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name</label>
<input type="text" placeholder="Name" required value="<?=$data[$i]['name']?>"   class="form-control" name="MemberName[]" id="MemberName">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Middle Initial</label>
<input type="text" placeholder="Middle Initial"  maxlength="50" value="<?=$data[$i]['middle_name']?>" class="form-control" name="MemberMiddleInitial[]" id="MemberMiddleInitial">
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Relationship to the Head of the Family</label>
<select class="form-control" name="MemberRelationship[]">
<!-- <option value="">Relationship</option> -->
          <option value="Head"  <?php if($data[$i]['ralationship']=="Head") echo 'selected';?>>Head</option>
          <option value="Spouse"  <?php if($data[$i]['ralationship']=="Spouse") echo 'selected';?>>Spouse</option>
          <option value="Son"  <?php if($data[$i]['ralationship']=="Son") echo 'selected';?>>Son</option>
          <option value="Daughter"  <?php if($data[$i]['ralationship']=="Daughter") echo 'selected';?>>Daughter</option>
          <option value="Stepson"  <?php if($data[$i]['ralationship']=="Stepson") echo 'selected';?>>Stepson</option>
          <option value="Stepdaughter"  <?php if($data[$i]['ralationship']=="Stepdaughter") echo 'selected';?>>Stepdaughter</option>
          <option value="Son-in-Law"  <?php if($data[$i]['ralationship']=="Son-in-Law") echo 'selected';?>>Son-in-Law</option>
          <option value="Daughter-in-Law"  <?php if($data[$i]['ralationship']=="Daughter-in-Law") echo 'selected';?>>Daughter-in-Law</option>
          <option value="Grandson"  <?php if($data[$i]['ralationship']=="Grandson") echo 'selected';?>>Grandson</option>
          <option value="Granddaughter"  <?php if($data[$i]['ralationship']=="Granddaughter") echo 'selected';?>>Granddaughter</option>
          <option value="Father"  <?php if($data[$i]['ralationship']=="Father") echo 'selected';?>>Father</option>
          <option value="Mother"  <?php if($data[$i]['ralationship']=="Mother") echo 'selected';?>>Mother</option>

          <option value="Brother"  <?php if($data[$i]['ralationship']=="Brother") echo 'selected';?>>Brother</option>
          <option value="Sister"  <?php if($data[$i]['ralationship']=="Sister") echo 'selected';?>>Sister</option>
          <option value="Uncle"  <?php if($data[$i]['ralationship']=="Uncle") echo 'selected';?>>Uncle</option>
          <option value="Aunt"  <?php if($data[$i]['ralationship']=="Aunt") echo 'selected';?>>Aunt</option>
          <option value="Nephew"  <?php if($data[$i]['ralationship']=="Nephew") echo 'selected';?>>Nephew</option>
          <option value="Niece"  <?php if($data[$i]['ralationship']=="Niece") echo 'selected';?>>Niece</option>
          <option value="Other Relative"  <?php if($data[$i]['ralationship']=="Other Relative") echo 'selected';?>>Other Relative</option>
          <option value="Boarder"  <?php if($data[$i]['ralationship']=="Boarder") echo 'selected';?>>Boarder</option>
          <option value="Domestic Helper"  <?php if($data[$i]['ralationship']=="Domestic Helper") echo 'selected';?>>Domestic Helper</option>
          <option value="Family Driver"  <?php if($data[$i]['ralationship']=="Family Driver") echo 'selected';?>>Family Driver</option>
          <option value="No Relation"  <?php if($data[$i]['ralationship']=="No Relation") echo 'selected';?>>No Relation</option>
          <option value="Grandmother"  <?php if($data[$i]['ralationship']=="Grandmother") echo 'selected';?>>Grandmother</option>
          <option value="Grandfather"  <?php if($data[$i]['ralationship']=="Grandfather") echo 'selected';?>>Grandfather</option>

</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Enter Date of Birth (MM/DD/YY)</label>
<input type="text" onkeydown="return false" style="background-color: white" placeholder="Date of Birth (MM/DD/YY)" value="<?=$data[$i]['date_of_birth']?>"  required class="form-control datepicker birth_datepicker" name="MemberDateofBirth[]" id="MemberDateofBirth<?=$z;?>">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Place of Birth</label>
<input type="text" placeholder="Place of Birth" value="<?=$data[$i]['place_of_birth']?>"  maxlength="255"  class="form-control" name="member_place_of_birth[]" id="member_place_of_birth">
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Civil Status</label>
<select class="form-control" name="MemberCivilStatus[]">
<!-- <option value="">Civil Status</option> -->
<option value="Single"  <?php if($data[$i]['civil_status']=="Single") echo 'selected';?>>Single</option>
<option value="Married" <?php if($data[$i]['civil_status']=="Married") echo 'selected';?>>Married</option>
<option value="Living-in" <?php if($data[$i]['civil_status']=="Living-in") echo 'selected';?>>Living-in</option>
<option value="Separated"  <?php if($data[$i]['civil_status']=="Separated") echo 'selected';?>>Separated</option>
<option value="Widow"  <?php if($data[$i]['civil_status']=="Widow") echo 'selected';?>>Widow</option>
<option value="Partner"  <?php if($data[$i]['civil_status']=="Partner") echo 'selected';?>>Partner</option>
<option value="Divorced"  <?php if($data[$i]['civil_status']=="Divorced") echo 'selected';?>>Divorced</option>
<option value="Unknown"  <?php if($data[$i]['civil_status']=="Unknown") echo 'selected';?>>Unknown</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Sex</label>
<select class="form-control" name="MemberSex[]">
<!-- <option value="">Sex</option> -->
<option value="Male"  <?php if($data[$i]['sex']=="Male") echo 'selected';?>>Male </option>
<option value="Female"  <?php if($data[$i]['sex']=="Female") echo 'selected';?>>Female</option>
<option value="SOGIE"  <?php if($data[$i]['sex']=="SOGIE") echo 'selected';?>>SOGIE</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Citizenship</label>
<select class="form-control" name="member_citizenship[]">
<!-- <option value="">Sex</option> -->
<option value="Filipino"  <?php if($data[$i]['citizenship']=="Filipino") echo 'selected';?>>Filipino </option>
<option value="Non-Filipino"  <?php if($data[$i]['citizenship']=="Non-Filipino") echo 'selected';?>>Non-Filipino</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Nationality if Non-Filipino</label>
<input type="text" placeholder="Nationality if Non-Filipino" maxlength="255" value="<?=$data[$i]['nationality_if_non_filipino']?>"  class="form-control" name="member_nationality_if_non_filipino[]" id="member_nationality_if_non_filipino">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Ethnicity (Tagalog/Bicolona/Bisaya etc)</label>
<input type="text" placeholder="Ethnicity (Tagalog/Bicolona/Bisaya etc)" maxlength="255" value="<?=$data[$i]['ethnicity']?>"  class="form-control" name="member_ethnicity[]" id="member_ethnicity">
</div>
</div>


<div class="row headerRow">
<div class="col-sm-12">
<label>Contact Information</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Landline</label>
<input type="text" placeholder="Landline" maxlength="15" value="<?=$data[$i]['landline']?>"  class="form-control" name="MemberLandline[]" id="MemberLandline">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Mobile</label>
<input type="text" placeholder="Mobile"  maxlength="15" value="<?=$data[$i]['mobile']?>"  class="form-control" name="MemberMobile[]" id="Mobile">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Email Address</label>
<input type="email" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?=$data[$i]['email']?>"  class="form-control" name="MemberEmailAddress[]" id="MemberEmailAddress">
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Status of Residency</label>
<select class="form-control" name="MemberStatusofResidency[]">
<!-- <option value="">Status of Residency</option> -->
<option value="Voter"  <?php if($data[$i]['status_residency']=="Voter") echo 'selected';?>>Voter  </option>
<option value="Non-Voter"  <?php if($data[$i]['status_residency']=="Non-Voter") echo 'selected';?>>Non-Voter</option>
<option value="Registering"  <?php if($data[$i]['status_residency']=="Registering") echo 'selected';?>>Registering</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Special Disability or Senior Citizen</label>
<select class="form-control" name="MemberSpecialDisabilityorSeniorCitizen[]">
<!-- <option value="">Special Disability or Senior Citizen</option> -->
<option value="Special Child"  <?php if($data[$i]['special_disability_or']=="Special Child") echo 'selected';?>>Special Child  </option>
<option value="With Foot Disability"  <?php if($data[$i]['special_disability_or']=="With Foot Disability") echo 'selected';?>>With Foot Disability</option>
<option value="PWD Person with Disability"  <?php if($data[$i]['special_disability_or']=="PWD Person with Disability") echo 'selected';?>>PWD Person with Disability</option>
<option value="Not Applicable" <?php if($data[$i]['special_disability_or']=="Not Applicable" || empty($data[$i]['special_disability_or'])) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Disability</label>
<input type="text" placeholder="Specify Disability"  value="<?=$data[$i]['specify_disability']?>"  class="form-control" name="member_specify_disability[]" id="member_specify_disability">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Senior Citizen</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">




<input type="radio"  value="yes"  <?php if($data[$i]['senior_citizen']=="yes") echo 'checked';?> class="MemberSeniorCitizen"  name="member_senior_citizen_<?=$z;?>" id="member_senior_citizen_yes">   <label> Yes </label>
<input type="radio" value="no"  <?php if($data[$i]['senior_citizen']=="no") echo 'checked';?>  class="MemberSeniorCitizen"   name="member_senior_citizen_<?=$z;?>" id="member_senior_citizen_no">  <label> No  </label>
<br>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Solo Parent</label>
<select class="form-control" name="member_solo_parent[]">
<!-- <option value="">Special Disability or Senior Citizen</option> -->
<option value="Registered Solo Parent"  <?php if($data[$i]['solo_parent']=="Registered Solo Parent") echo 'selected';?>>Registered Solo Parent  </option>
<option value="Non-Solo Parent"  <?php if($data[$i]['solo_parent']=="Non-Solo Parent") echo 'selected';?>>Non-Solo Parent  </option>
<option value="Unregistered Solo Parent"  <?php if($data[$i]['solo_parent']=="Unregistered Solo Parent") echo 'selected';?>>Unregistered Solo Parent  </option>
<option value="Not Applicable"  <?php if($data[$i]['solo_parent']=="Not Applicable") echo 'selected';?>>Not Applicable  </option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Religion</label>
<select class="form-control" name="MemberReligion[]">
<!-- <option value="">Religion</option> -->
<option value="Catholic" <?php if($data[$i]['religion']=="Catholic") echo 'selected';?>>Catholic</option>
<option value="Iglesia ni Kristo" <?php if($data[$i]['religion']=="Iglesia ni Kristo") echo 'selected';?>>Iglesia ni Kristo</option>
<option value="Ang Dating Daan" <?php if($data[$i]['religion']=="Ang Dating Daan") echo 'selected';?>>Ang Dating Daan</option>
<option value="Born Again Christian" <?php if($data[$i]['religion']=="Born Again Christian") echo 'selected';?>>Born Again Christian</option>
<option value="Jehova’s Witness" <?php if($data[$i]['religion']=="Jehova’s Witness") echo 'selected';?>>Jehova’s Witness</option>
<option value="Aglipay" <?php if($data[$i]['religion']=="Aglipay") echo 'selected';?>>Aglipay</option>
<option value="7th Day Adventist" <?php if($data[$i]['religion']=="7th Day Adventist") echo 'selected';?>>7th Day Adventist</option>
<option value="Methodist" <?php if($data[$i]['religion']=="Methodist") echo 'selected';?>>Methodist</option>
<option value="Muslim" <?php if($data[$i]['religion']=="Muslim") echo 'selected';?>>Muslim</option>
<option value="Hindu" <?php if($data[$i]['religion']=="Hindu") echo 'selected';?>>Hindu</option>
<option value="Budhist" <?php if($data[$i]['religion']=="Budhist") echo 'selected';?>>Budhist </option>
<option value="Others" <?php if($data[$i]['religion']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">LGBTI</label>
<select class="form-control" name="MemberLGBTI[]">
<!-- <option value="">LGBTI</option> -->
<option value="Lesbian" <?php if($data[$i]['LGBTI']=="Lesbian") echo 'selected';?>>Lesbian</option>
<option value="Gay" <?php if($data[$i]['LGBTI']=="Gay") echo 'selected';?>>Gay</option>
<option value="Bisexual" <?php if($data[$i]['LGBTI']=="Bisexual") echo 'selected';?>>Bisexual</option>
<option value="Transgender" <?php if($data[$i]['LGBTI']=="Transgender") echo 'selected';?>>Transgender</option>
<option value="Intersex" <?php if($data[$i]['LGBTI']=="Intersex") echo 'selected';?>>Intersex</option>
<option value="Not Applicable" <?php if($data[$i]['LGBTI']=="Not Applicable" || empty($data[$i]['LGBTI'])) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Status of Work</label>
<select class="form-control" name="MemberStatusofWork[]">
<!-- <option value="">Status of Work</option> -->

<option value="Permanent"  <?php if($data[$i]['work_status']=="Permanent") echo 'selected';?>>Permanent</option>
<option value="Casual"  <?php if($data[$i]['work_status']=="Casual") echo 'selected';?>>Casual</option>
<option value="Contractual"  <?php if($data[$i]['work_status']=="Contractual") echo 'selected';?>>Contractual</option>
<option value="Part Time"  <?php if($data[$i]['work_status']=="Part Time") echo 'selected';?>>Part Time</option>
<option value="Sole Proprietorship"  <?php if($data[$i]['work_status']=="Sole Proprietorship") echo 'selected';?>>Sole Proprietorship</option>
<option value="Partnership"  <?php if($data[$i]['work_status']=="Partnership") echo 'selected';?>>Partnership</option>
<option value="Corporation"  <?php if($data[$i]['work_status']=="Corporation") echo 'selected';?>>Corporation</option>
<option value="Not Applicable"  <?php if($data[$i]['work_status']=="Not Applicable" || empty($data[$i]['work_status'])) echo 'selected';?>>Not Applicable</option>


</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Type of Residence</label>
<select class="form-control" name="member_type_of_residence[]">
<option value="Non-Migrant"  <?php if($data[$i]['type_of_residence']=="Non-Migrant") echo 'selected';?>>Non-Migrant</option>
<option value="Migrant"  <?php if($data[$i]['type_of_residence']=="Migrant") echo 'selected';?>>Migrant</option>
<option value="Transient"  <?php if($data[$i]['type_of_residence']=="Transient") echo 'selected';?>>Transient</option>

</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Date of Transfer to Barangay</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Date of Transfer to Barangay (MM/DD/YY)"  value="<?=$data[$i]['date_of_transfer_to_barangay']?>"   class="form-control datepicker transfer_datepicker" name="member_date_of_transfer_to_barangay[]" id="member_date_of_transfer_to_barangay<?=$z;?>">
</div>
</div>




<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Reason for Leaving the Place</label>



<br><input type="checkbox" value="Lack of Employment" class="member_reason_for_leaving_place" name="member_reason_for_leaving_place[][]"  <?php if( strpos($data[$i]['reason_for_leaving_place'], 'Lack of Employment') !== false ) echo 'checked';?>>&nbsp;Lack of Employment <br>
<input type="checkbox" value="Perception of Better Income in other Place" class="member_reason_for_leaving_place" name="member_reason_for_leaving_place[][]"   <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Perception of Better Income in other Place") !== false ) echo 'checked';?>>&nbsp;Perception of Better Income in other Place <br>
 <input type="checkbox" name="member_reason_for_leaving_place[]" value="Schooling"  <?php if(strpos($data[$i]['reason_for_leaving_place'] ,"Schooling") !== false ) echo 'checked';?>>&nbsp;Schooling<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Presence of relatives and friends in other place"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Presence of relatives and friends in other place") !== false ) echo 'checked';?>>&nbsp;Presence of relatives and friends in other place<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Employment/Job Relocation"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Employment/Job Relocation") !== false ) echo 'checked';?>>&nbsp;Employment/Job Relocation<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Disaster Related Relocation"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Disaster Related Relocation") !== false ) echo 'checked';?>>&nbsp;Disaster Related Relocation<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Retirement"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Retirement") !== false ) echo 'checked';?>>&nbsp;Retirement<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="To live with Parents"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "To live with Parents") !== false ) echo 'checked';?>>&nbsp;To live with Parents<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="To live with children"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "To live with children") !== false ) echo 'checked';?>>&nbsp;To live with children<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Marriage"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Marriage") !== false ) echo 'checked';?>>&nbsp;Marriage<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Annulment/Divorce/Separation"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Annulment/Divorce/Separation") !== false ) echo 'checked';?>>&nbsp;Annulment/Divorce/Separation<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Community Related Reason"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Community Related Reason") !== false ) echo 'checked';?>>&nbsp;Community Related Reason<br>
<input type="checkbox" name="member_reason_for_leaving_place[]" value="Not Applicable"  <?php if(strpos($data[$i]['reason_for_leaving_place'] , "Not Applicable") !== false ) echo 'checked';?>>&nbsp;Not Applicable<br> 


</div>
</div> -->



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Plan to Return to Previous Barangay</label>
<select class="form-control" name="member_plan_to_return_previous_barangay[]">
<option value="yes"  <?php if($data[$i]['plan_to_return_previous_barangay']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[$i]['plan_to_return_previous_barangay']=="no") echo 'selected';?>>No</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Plan to Return to Previous Barangay – When?</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Plan to Return to Previous Barangay – When?"  value="<?=$data[$i]['plan_to_return_previous_barangay_when']?>"   class="form-control datepicker previous_datepicker" name="member_plan_to_return_previous_barangay_when[]" id="member_plan_to_return_previous_barangay_when<?=$z;?>">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Intent/Duration of stay in Barangay</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Plan to Return to Previous Barangay – When?"  value="<?=$data[$i]['intent_duration_stay_barangay']?>"   class="form-control datepicker intent_datepicker" name="member_intent_duration_stay_barangay[]" id="member_intent_duration_stay_barangay<?=$z;?>">
</div>
</div>




<div class="row headerRow">
<div class="col-sm-12">
<label>Place of Work</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">a. Barangay Name</label>
<input type="text"   value="<?=$data[$i]['place_of_work_barangay_name']?>"  placeholder="Barangay Name" class="form-control" name="member_place_of_work_barangay_name[]" id="member_place_of_work_barangay_name">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. City/Municipality </label>
<input type="text"   value="<?=$data[$i]['place_of_work_city_municipality']?>"  placeholder="City/Municipality" class="form-control" name="member_place_of_work_city_municipality[]" id="member_place_of_work_city_municipality">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. Province </label>
<input type="text"   value="<?=$data[$i]['place_of_work_province']?>"  placeholder="Province" class="form-control" name="member_place_of_work_province[]" id="member_place_of_work_province">
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Occupation</label>
<select class="form-control" name="MemberOccupation[]">
<!-- <option value="">Occupation</option> -->
<option value="Employee"  <?php if($data[$i]['occupation']=="Employee") echo 'selected';?>>Employee</option>
<option value="Business-Registered"  <?php if($data[$i]['occupation']=="Business-Registered") echo 'selected';?>>Business-Registered</option>
<option value="Business-Not Registered"  <?php if($data[$i]['occupation']=="Business-Not Registered") echo 'selected';?>>Business-Not Registered</option>
<option value="Unemployed"  <?php if($data[$i]['occupation']=="Unemployed") echo 'selected';?>>Unemployed</option>
<option value="Student"  <?php if($data[$i]['occupation']=="Student") echo 'selected';?>>Student</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">If Student</label>
<select class="form-control" name="MemberIfStudent[]">
<!-- <option value="">If Student</option> -->
<option value="Private School" <?php if($data[$i]['if_student']=="Private School") echo 'selected';?>>Private School</option>
<option value="Public School" <?php if($data[$i]['if_student']=="Public School") echo 'selected';?>>Public School</option>
<option value="Not Applicable" <?php if($data[$i]['if_student']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label>Enrolled? (Y/N)</label><br>
<input type="radio"  value="yes"   <?php if($data[$i]['is_student_enrolled']=="yes") echo 'checked';?> class="MemberIsStudentEnrolled"  name="member_is_student_enrolled_<?=$z;?>" id="member_is_student_enrolled_yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[$i]['is_student_enrolled']=="no") echo 'checked';?>  class="MemberIsStudentEnrolled" name="member_is_student_enrolled_<?=$z;?>" id="member_is_student_enrolled_no">  <label>No</label>

</div>
</div>





<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Nature of Work</label>
<select class="form-control" name="MemberNatureofWork[]">
<!-- <option value="">Nature of Work</option> -->
<option value="Professional" <?php if($data[$i]['work_nature']=="Professional") echo 'selected';?>>Professional</option>
<option value="Not Professional" <?php if($data[$i]['work_nature']=="Not Professional") echo 'selected';?>>Not Professional</option>
<option value="Informal Sector" <?php if($data[$i]['work_nature']=="Informal Sector") echo 'selected';?>>Informal Sector</option>
</select>
</div>
</div>
<div class="row headerRow">
<div class="col-sm-12">
<label>Name of Profession</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name (eg. Doctor,Dentist,Engineer,etc)</label>
<input type="text" placeholder="Name (eg. Doctor,Dentist,Engineer,etc)" value="<?=$data[$i]['profession_name']?>"  class="form-control" name="MemberProfessionName[]" id="MemberProfessionName">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Industry (Medical,IT,Engineering, etc)</label>
<input type="text" placeholder="Industry (Medical,IT,Engineering, etc)" maxlength="60" value="<?=$data[$i]['profession_industry']?>"  class="form-control" name="MemberProfessionIndustry[]" id="MemberProfessionIndustry">
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Working Sector</label>
<select class="form-control" name="MemberWorkingSector[]">
<!-- <option value="">Working Sector</option> -->
<option value="Private" <?php if($data[$i]['work_sector']=="Private") echo 'selected';?>>Private</option>
<option value="Public (Government)"  <?php if($data[$i]['work_sector']=="Public (Government)") echo 'selected';?>>Public (Government)</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Have a Valid CTC?</label>
<select class="form-control" name="member_have_a_valid_ctc[]">
<!-- <option value="">Working Sector</option> -->
<option value="yes" <?php if($data[$i]['have_a_valid_ctc']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[$i]['have_a_valid_ctc']=="no") echo 'selected';?>>No</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Is CTC issued in Barangay?</label>
<select class="form-control" name="member_ctc_issued_barangay[]">
<!-- <option value="">Working Sector</option> -->
<option value="yes" <?php if($data[$i]['ctc_issued_barangay']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[$i]['ctc_issued_barangay']=="no") echo 'selected';?>>No</option>
</select>
</div>
</div>
<div class="row headerRow">
<div class="col-sm-12">
<label>Health Benefits</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio"   value="Philhealth"  <?php if($data[$i]['helth_benefit']=="Philhealth") echo 'checked';?>  class="MemberHealthBenefits"  name="MemberHealthBenefits_<?=$z;?>" id="MemberPhilhealthYes">  <label> Philhealth </label>


<input type="radio"   value="HMO"  <?php if($data[$i]['helth_benefit']=="HMO") echo 'checked';?>   class="MemberHealthBenefits"   name="MemberHealthBenefits_<?=$z;?>" id="MemberHMOYes">  <label> HMO  </label>

<br>
<input type="radio"   value="Other Health Insurance"  <?php if($data[$i]['helth_benefit']=="Other Health Insurance") echo 'checked';?>   class="MemberHealthBenefits"   name="MemberHealthBenefits_<?=$z;?>" id="MemberOtherHealthInsuranceYes">  <label> Other Health Insurance </label>
 
<input type="radio"   value="None"  <?php if($data[$i]['helth_benefit']=="None") echo 'checked';?>   class="MemberHealthBenefits"   name="MemberHealthBenefits_<?=$z;?>" id="MemberNoneYes">  <label> None </label>
<input type="text" hidden value="<?=$data[$i]['helth_benefit_other']?>"  placeholder="Other Health Benefits" class="form-control OtherMemberHealthBenefits" name="MemberOtherHealthBenefits[]" id="MemberOtherHealthBenefits">
</div>
</div>



<div class="row headerRow">
<div class="col-sm-12">
<label>Membership or Affiliation with a Financial Institution</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio" value="Pagibig Fund (HDMF)"   <?php if($data[$i]['membership_or_affiliation']=="Pagibig Fund (HDMF)") echo 'checked';?>  class="MemberMembership"   name="MemberMembership_<?=$z;?>" id="PagibigFundYes">  <label> Pagibig Fund (HDMF)    </label>


<input type="radio" value="SSS"   <?php if($data[$i]['membership_or_affiliation']=="SSS") echo 'checked';?>  class="MemberMembership" name="MemberMembership_<?=$z;?>" id="SSSYes">  <label>  SSS    </label>


<input type="radio" value="GSIS"   <?php if($data[$i]['membership_or_affiliation']=="GSIS") echo 'checked';?>  class="MemberMembership"  name="MemberMembership_<?=$z;?>" id="GSISYes">  <label>GSIS </label>

<br>
<input type="radio" value="Cooperative"   <?php if($data[$i]['membership_or_affiliation']=="Cooperative") echo 'checked';?>  class="MemberMembership"  name="MemberMembership_<?=$z;?>" id="CooperativeYes">  <label>Cooperative </label>


<input type="radio" value="Other"   <?php if($data[$i]['membership_or_affiliation']=="Other") echo 'checked';?>  class="MemberMembership" name="MemberMembership_<?=$z;?>" id="PagibigFundYes">  <label> Other    </label>

</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">If Others, Specify</label>
<input type="text"  value="<?=$data[$i]['membership_or_affiliation_other']?>" maxlength="100" placeholder="If Others, Specify" class="form-control" name="MemberOtherMembership[]" id="MemberOtherMembership">
</div>
</div>




<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Place of Delivery</label>
<select class="form-control" name="member_place_of_delivery[]">
<!-- <option value="">Educational Attainment</option> -->

<option value="Public Hospital"  <?php if($data[$i]['place_of_delivery']=="Public Hospital") echo 'selected';?>>Public Hospital</option>
<option value="Private Hospital"  <?php if($data[$i]['place_of_delivery']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Lying-in Clinic"  <?php if($data[$i]['place_of_delivery']=="Lying-in Clinic") echo 'selected';?>>Lying-in Clinic</option>
<option value="Home"  <?php if($data[$i]['place_of_delivery']=="Home") echo 'selected';?>>Home</option>
<option value="Others"  <?php if($data[$i]['place_of_delivery']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[$i]['place_of_delivery']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[$i]['place_of_delivery_specify_other']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="member_place_of_delivery_specify_other[]" id="member_place_of_delivery_specify_other">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Person who assisted in the delivery</label>
<select class="form-control" name="member_person_assisted_delivery[]">
<!-- <option value="">Educational Attainment</option> -->


<option value="Doctor"  <?php if($data[$i]['person_assisted_delivery']=="Doctor") echo 'selected';?>>Doctor</option>
<option value="Nurse"  <?php if($data[$i]['person_assisted_delivery']=="Nurse") echo 'selected';?>>Nurse</option>
<option value="Midwife"  <?php if($data[$i]['person_assisted_delivery']=="Midwife") echo 'selected';?>>Midwife</option>
<option value="Hilot"  <?php if($data[$i]['person_assisted_delivery']=="Hilot") echo 'selected';?>>Hilot</option>
<option value="Others"  <?php if($data[$i]['person_assisted_delivery']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[$i]['person_assisted_delivery_specify_other']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="member_person_assisted_delivery_specify_other[]" id="member_person_assisted_delivery_specify_other">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Last Vaccine Received</label>
<input type="text" placeholder="Last Vaccine Received"  value="<?=$data[$i]['last_vaccine_received']?>"  class="form-control" name="member_last_vaccine_received[]" id="member_last_vaccine_received">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">No. of Pregnancies(For Mothers)</label>
<input type="text" placeholder="No. of Pregnancies(For Mothers)"  value="<?=$data[$i]['no_of_pregnancies_for_mothers']?>"  class="form-control" name="member_no_of_pregnancies_for_mothers[]" id="member_no_of_pregnancies_for_mothers">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">No. of Children Living(For Mothers)</label>
<input type="text" placeholder="No. of Children Living(For Mothers)"  value="<?=$data[$i]['no_of_children_living_for_mothers']?>"  class="form-control" name="member_no_of_children_living_for_mothers[]" id="member_no_of_children_living_for_mothers">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Family Planning Method</label>
<select class="form-control" name="member_family_planning_method[]">
<!-- <option value="">Educational Attainment</option> -->

<option value="Ligation"  <?php if($data[$i]['family_planning_method']=="Ligation") echo 'selected';?>>Ligation</option>
<option value="Vasectomy"  <?php if($data[$i]['family_planning_method']=="Vasectomy") echo 'selected';?>>Vasectomy</option>
<option value="IUD"  <?php if($data[$i]['family_planning_method']=="IUD") echo 'selected';?>>IUD</option>
<option value="Injectables"  <?php if($data[$i]['family_planning_method']=="Injectables") echo 'selected';?>>Injectables</option>
<option value="Implant"  <?php if($data[$i]['family_planning_method']=="Implant") echo 'selected';?>>Implant</option>
<option value="Pill"  <?php if($data[$i]['family_planning_method']=="Pill") echo 'selected';?>>Pill</option>
<option value="Condom"  <?php if($data[$i]['family_planning_method']=="Condom") echo 'selected';?>>Condom</option>
<option value="Natural"  <?php if($data[$i]['family_planning_method']=="Natural") echo 'selected';?>>Natural</option>
<option value="LAM"  <?php if($data[$i]['family_planning_method']=="LAM") echo 'selected';?>>LAM</option>
<option value="Traditional"  <?php if($data[$i]['family_planning_method']=="Traditional") echo 'selected';?>>Traditional</option>
<option value="None"  <?php if($data[$i]['family_planning_method']=="None") echo 'selected';?>>None</option>
<option value="Not Applicable"  <?php if($data[$i]['family_planning_method']=="Not Applicable") echo 'selected';?>>Not Applicable</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Source of Family Planning Method</label>
<select class="form-control" name="member_source_family_planning_method[]">
<!-- <option value="">Educational Attainment</option> -->

<option value="Government Hospital"  <?php if($data[$i]['source_family_planning_method']=="Government Hospital") echo 'selected';?>>Government Hospital</option>
<option value="RHU/Health Center"  <?php if($data[$i]['source_family_planning_method']=="RHU/Health Center") echo 'selected';?>>RHU/Health Center</option>
<option value="Barangay Health Station"  <?php if($data[$i]['source_family_planning_method']=="Barangay Health Station") echo 'selected';?>>Barangay Health Station</option>
<option value="Private Hospital"  <?php if($data[$i]['source_family_planning_method']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Pharmacy"  <?php if($data[$i]['source_family_planning_method']=="Pharmacy") echo 'selected';?>>Pharmacy</option>
<option value="Others"  <?php if($data[$i]['source_family_planning_method']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[$i]['source_family_planning_method']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[$i]['source_family_planning_method_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="member_source_family_planning_method_others[]" id="member_source_family_planning_method_others">
</div>
</div>




<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Intention to use Family Planning Method</label>
<select class="form-control" name="member_intension_use_family_planning[]">
<!-- <option value="">Educational Attainment</option> -->
<option value="yes"  <?php if($data[$i]['intension_use_family_planning']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[$i]['intension_use_family_planning']=="no") echo 'selected';?>>No</option>
<option value="Not Applicable"  <?php if($data[$i]['intension_use_family_planning']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Reason</label>
  <input type="text"  value="<?=$data[$i]['intension_use_family_planning_reasons']?>" maxlength="255" placeholder="Specify Reason" class="form-control" name="member_intension_use_family_planning_reasons[]" id="member_intension_use_family_planning_reasons">
</div>
</div>




<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Philhealth Status</label>
<select class="form-control" name="member_philhealth_status[]">
<!-- <option value="">Educational Attainment</option> -->
<option value="Paying Member"  <?php if($data[$i]['philhealth_status']=="Paying Member") echo 'selected';?>>Paying Member</option>
<option value="Dependent of Paying Member"  <?php if($data[$i]['philhealth_status']=="Dependent of Paying Member") echo 'selected';?>>Dependent of Paying Member</option>
<option value="Indigent Member"  <?php if($data[$i]['philhealth_status']=="Indigent Member") echo 'selected';?>>Indigent Member</option>
<option value="Dependent of Indigent"  <?php if($data[$i]['philhealth_status']=="Dependent of Indigent") echo 'selected';?>>Dependent of Indigent</option>
<option value="Not Applicable"  <?php if($data[$i]['philhealth_status']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Health Facility Visited in Last 12 Months</label>
<select class="form-control" name="member_health_facility_visited_12_months[]">
<!-- <option value="">Educational Attainment</option> -->
<option value="Government Hospital"  <?php if($data[$i]['health_facility_visited_12_months']=="Government Hospital") echo 'selected';?>>Government Hospital</option>
<option value="RHU/Health Center"  <?php if($data[$i]['health_facility_visited_12_months']=="RHU/Health Center") echo 'selected';?>>RHU/Health Center</option>
<option value="Barangay Health Station"  <?php if($data[$i]['health_facility_visited_12_months']=="Barangay Health Station") echo 'selected';?>>Barangay Health Station</option>
<option value="Private Hospital"  <?php if($data[$i]['health_facility_visited_12_months']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Private Clinic"  <?php if($data[$i]['health_facility_visited_12_months']=="Private Clinic") echo 'selected';?>>Private Clinic</option>
<option value="Pharmacy"  <?php if($data[$i]['health_facility_visited_12_months']=="Pharmacy") echo 'selected';?>>Pharmacy</option>
<option value="Hilot/Herbalist"  <?php if($data[$i]['health_facility_visited_12_months']=="Hilot/Herbalist") echo 'selected';?>>Hilot/Herbalist</option>
<option value="Others"  <?php if($data[$i]['health_facility_visited_12_months']=="Others") echo 'selected';?>>Others</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
  <input type="text"  value="<?=$data[$i]['health_facility_visited_12_months_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="member_health_facility_visited_12_months_others[]" id="member_health_facility_visited_12_months_others">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Reason for Visiting Health Facility</label>
<select class="form-control" name="member_reason_visiting_health_facility[]">
<!-- <option value="">Educational Attainment</option> -->
<option value="Sick/Injured"  <?php if($data[$i]['reason_visiting_health_facility']=="Sick/Injured") echo 'selected';?>>Sick/Injured</option>
<option value="Prenatal/Postnatal"  <?php if($data[$i]['reason_visiting_health_facility']=="Prenatal/Postnatal") echo 'selected';?>>Prenatal/Postnatal</option>
<option value="Gave Birth"  <?php if($data[$i]['reason_visiting_health_facility']=="Gave Birth") echo 'selected';?>>Gave Birth</option>
<option value="Dental"  <?php if($data[$i]['reason_visiting_health_facility']=="Dental") echo 'selected';?>>Dental</option>
<option value="Medical Checkup"  <?php if($data[$i]['reason_visiting_health_facility']=="Medical Checkup") echo 'selected';?>>Medical Checkup</option>
<option value="Medical Requirement"  <?php if($data[$i]['reason_visiting_health_facility']=="Medical Requirement") echo 'selected';?>>Medical Requirement</option>
<option value="NHTS/CCT/4Ps requirement"  <?php if($data[$i]['reason_visiting_health_facility']=="NHTS/CCT/4Ps requirement") echo 'selected';?>>NHTS/CCT/4Ps requirement</option>
<option value="Others"  <?php if($data[$i]['reason_visiting_health_facility']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
  <input type="text"  value="<?=$data[$i]['reason_visiting_health_facility_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="member_reason_visiting_health_facility_others[]" id="member_reason_visiting_health_facility_others">
</div>
</div>



<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Educational Attainment</label>
<select class="form-control" name="MemberEducationalAttainment[]">
<!-- <option value="">Educational Attainment</option> -->

<option value="No Education"  <?php if($data[$i]['educational_attainment']=="No Education") echo 'selected';?>>No Education</option>
<option value="Pre-school"  <?php if($data[$i]['educational_attainment']=="Pre-school") echo 'selected';?>>Pre-school</option>
<option value="Elementary Level"  <?php if($data[$i]['educational_attainment']=="Elementary Level") echo 'selected';?>>Elementary Level</option>
<option value="Elementary graduate"  <?php if($data[$i]['educational_attainment']=="Elementary graduate") echo 'selected';?>>Elementary graduate</option>
<option value="High school level"  <?php if($data[$i]['educational_attainment']=="High school level") echo 'selected';?>>High school level</option>
<option value="High school graduate"  <?php if($data[$i]['educational_attainment']=="High school graduate") echo 'selected';?>>High school graduate</option>
<option value="Junior HS"  <?php if($data[$i]['educational_attainment']=="Junior HS") echo 'selected';?>>Junior HS</option>
<option value="Junior HS graduate"  <?php if($data[$i]['educational_attainment']=="Junior HS graduate") echo 'selected';?>>Junior HS graduate</option>

<option value="Senior HS graduate"  <?php if($data[$i]['educational_attainment']=="Senior HS graduate") echo 'selected';?>>Senior HS graduate</option>
<option value="Vocational/Tech"  <?php if($data[$i]['educational_attainment']=="Vocational/Tech") echo 'selected';?>>Vocational/Tech</option>
<option value="College level"  <?php if($data[$i]['educational_attainment']=="College level") echo 'selected';?>>College level</option>
<option value="College graduate"  <?php if($data[$i]['educational_attainment']=="College graduate") echo 'selected';?>>College graduate</option>
<option value="Post-graduate"  <?php if($data[$i]['educational_attainment']=="Post-graduate") echo 'selected';?>>Post-graduate</option>


</select>
</div>
</div>

<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Place of School</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">a. Barangay Name</label>  
<input type="text"   value="<?=$data[$i]['place_of_school_barangay_name']?>"  placeholder="Barangay Name" class="form-control" name="member_place_of_school_barangay_name[]" id="member_place_of_school_barangay_name">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. City/Municipality</label>  
<input type="text"   value="<?=$data[$i]['place_of_school_city']?>"  placeholder="City/Municipality" class="form-control" name="member_place_of_school_city[]" id="member_place_of_school_city">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. Province</label>  
<input type="text"   value="<?=$data[$i]['place_of_school_province']?>"  placeholder="Province" class="form-control" name="member_place_of_school_province[]" id="member_place_of_school_province">
</div>
</div>



<div class="row headerRow">
<div class="col-sm-12">
<label>Skills and Certification </label>
</div>
</div>
<!-- <div class="row">
<div class="col-sm-12">
<input type="text" placeholder="Name of Certification" class="form-control" name="MemberNameofCertification[]" id="MemberNameofCertification">
</div>
</div> -->
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name of Certification</label>
<input type="text" value="<?=$data[$i]['skill_certification_name']?>"  placeholder="Name of Certification" class="form-control" name="MemberNameofCertification[]" id="MemberNameofCertification">
</div>
</div>
<div class="row headerRow">
<div class="col-sm-12">
<label>Where Acquired </label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio" value="Tesda" <?php if($data[$i]['acquired']=="Tesda") echo 'checked';?>   class="MemberWhereAcquired"  name="MemberWhereAcquired_<?=$z;?>" id="MemberTesdaYes">  <label> Tesda </label>


<input type="radio" value="Private Institution" <?php if($data[$i]['acquired']=="Private Institution") echo 'checked';?>  class="MemberWhereAcquired"   name="MemberWhereAcquired_<?=$z;?>" id="MemberPrivateInstitutionYes">  <label>Private Institution  </label>

</div>
</div>
<div class="row headerRow">
<div class="col-sm-12">
<label class="labelHeader">Name of Skills</label>
<input type="text" value="<?=$data[$i]['skill_name']?>" placeholder="Name of Skills" class="form-control" name="MemberNameofSkills[]" id="MemberNameofSkills">
</div>
</div>


<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Skills Development Training of Interest</label>
<select class="form-control" name="member_skill_development_training_interest[]">
<!-- <option value="">Status of Work</option> -->
<option value="Refrigeration and Airconditioning"  <?php if($data[$i]['skill_development_training_interest']=="Refrigeration and Airconditioning") echo 'selected';?>>Refrigeration and Airconditioning</option>
<option value="Automotive/Heavy Equipment Servicing"  <?php if($data[$i]['skill_development_training_interest']=="Automotive/Heavy Equipment Servicing") echo 'selected';?>>Automotive/Heavy Equipment Servicing</option>
<option value="Metal Worker"  <?php if($data[$i]['skill_development_training_interest']=="Metal Worker") echo 'selected';?>>Metal Worker</option>
<option value="Building Wiring Installation"  <?php if($data[$i]['skill_development_training_interest']=="Building Wiring Installation") echo 'selected';?>>Building Wiring Installation</option>
<option value="Heavy Equipment Operation"  <?php if($data[$i]['skill_development_training_interest']=="Heavy Equipment Operation") echo 'selected';?>>Heavy Equipment Operation</option>
<option value="Plumbing"  <?php if($data[$i]['skill_development_training_interest']=="Plumbing") echo 'selected';?>>Plumbing</option>
<option value="Welding"  <?php if($data[$i]['skill_development_training_interest']=="Welding") echo 'selected';?>>Welding</option>
<option value="Carpentry"  <?php if($data[$i]['skill_development_training_interest']=="Carpentry") echo 'selected';?>>Carpentry</option>
<option value="Baking"  <?php if($data[$i]['skill_development_training_interest']=="Baking") echo 'selected';?>>Baking</option>
<option value="Dressmaking"  <?php if($data[$i]['skill_development_training_interest']=="Dressmaking") echo 'selected';?>>Dressmaking</option>
<option value="Linguist"  <?php if($data[$i]['skill_development_training_interest']=="Linguist") echo 'selected';?>>Linguist</option>
<option value="Computer Graphics"  <?php if($data[$i]['skill_development_training_interest']=="Computer Graphics") echo 'selected';?>>Computer Graphics</option>
<option value="Painting"  <?php if($data[$i]['skill_development_training_interest']=="Painting") echo 'selected';?>>Painting</option>
<option value="Beauty Care"  <?php if($data[$i]['skill_development_training_interest']=="Beauty Care") echo 'selected';?>>Beauty Care</option>
<option value="Commercial Cooking"  <?php if($data[$i]['skill_development_training_interest']=="Commercial Cooking") echo 'selected';?>>Commercial Cooking</option>
<option value="House Keeping"  <?php if($data[$i]['skill_development_training_interest']=="House Keeping") echo 'selected';?>>House Keeping</option>
<option value="Massage Therapy"  <?php if($data[$i]['skill_development_training_interest']=="Massage Therapy") echo 'selected';?>>Massage Therapy</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[$i]['skill_development_training_interest_others']?>"  placeholder="Specify Others" class="form-control" name="member_skill_development_training_interest_others[]" id="member_skill_development_training_interest_others">
</div>
</div>



<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Present Skills</label>
<select class="form-control" name="member_present_skills[]">
<!-- <option value="">Status of Work</option> -->
<option value="Refrigeration and Airconditioning"  <?php if($data[$i]['present_skills']=="Refrigeration and Airconditioning") echo 'selected';?>>Refrigeration and Airconditioning</option>
<option value="Automotive/Heavy Equipment Servicing"  <?php if($data[$i]['present_skills']=="Automotive/Heavy Equipment Servicing") echo 'selected';?>>Automotive/Heavy Equipment Servicing</option>
<option value="Metal Worker"  <?php if($data[$i]['present_skills']=="Metal Worker") echo 'selected';?>>Metal Worker</option>
<option value="Building Wiring Installation"  <?php if($data[$i]['present_skills']=="Building Wiring Installation") echo 'selected';?>>Building Wiring Installation</option>
<option value="Heavy Equipment Operation"  <?php if($data[$i]['present_skills']=="Heavy Equipment Operation") echo 'selected';?>>Heavy Equipment Operation</option>
<option value="Plumbing"  <?php if($data[$i]['present_skills']=="Plumbing") echo 'selected';?>>Plumbing</option>
<option value="Welding"  <?php if($data[$i]['present_skills']=="Welding") echo 'selected';?>>Welding</option>
<option value="Carpentry"  <?php if($data[$i]['present_skills']=="Carpentry") echo 'selected';?>>Carpentry</option>
<option value="Baking"  <?php if($data[$i]['present_skills']=="Baking") echo 'selected';?>>Baking</option>
<option value="Dressmaking"  <?php if($data[$i]['present_skills']=="Dressmaking") echo 'selected';?>>Dressmaking</option>
<option value="Linguist"  <?php if($data[$i]['present_skills']=="Linguist") echo 'selected';?>>Linguist</option>
<option value="Computer Graphics"  <?php if($data[$i]['present_skills']=="Computer Graphics") echo 'selected';?>>Computer Graphics</option>
<option value="Painting"  <?php if($data[$i]['present_skills']=="Painting") echo 'selected';?>>Painting</option>
<option value="Beauty Care"  <?php if($data[$i]['present_skills']=="Beauty Care") echo 'selected';?>>Beauty Care</option>
<option value="Commercial Cooking"  <?php if($data[$i]['present_skills']=="Commercial Cooking") echo 'selected';?>>Commercial Cooking</option>
<option value="House Keeping"  <?php if($data[$i]['present_skills']=="House Keeping") echo 'selected';?>>House Keeping</option>
<option value="Massage Therapy"  <?php if($data[$i]['present_skills']=="Massage Therapy") echo 'selected';?>>Massage Therapy</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[$i]['present_skills_others']?>"  placeholder="Specify Others" class="form-control" name="member_present_skills_others[]" id="member_present_skills_others">
</div>
</div>




<div class="row headerRow">
<div class="col-sm-12">
<label>Income and Expense</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Fare from House to Office and back (in pesos)</label>
<input type="text" value="<?=$data[$i]['inc_expce_fare_home']?>"  placeholder="Fare from House to Office and back (in pesos)" class="form-control" name="MemberFarefromHousetoOfficeandback[]" id="MemberFarefromHousetoOfficeandback">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Monthly Salary or Income from Business (in pesos)</label>
<input type="text" value="<?=$data[$i]['inc_expcee_monthly_salary']?>"  placeholder="Monthly Salary or Income from Business (in pesos)" class="form-control" name="MemberMonthlySalaryorIncomefromBusiness[]" id="MemberMonthlySalaryorIncomefromBusiness">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Additional Monthly Income</label>
<input type="text" value="<?=$data[$i]['inc_expce_addition_monthly']?>"  placeholder="Additional Monthly Income" class="form-control" name="MemberAdditionalMonthlyIncome[]" id="MemberAdditionalMonthlyIncome">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Source of Income</label>
<select class="form-control" name="member_source_of_income[]">
<!-- <option value="">Educational Attainment</option> -->
<option value="Employment"  <?php if($data[$i]['source_of_income']=="Employment") echo 'selected';?>>Employment</option>
<option value="Business"  <?php if($data[$i]['source_of_income']=="Business") echo 'selected';?>>Business</option>
<option value="Remittance"  <?php if($data[$i]['source_of_income']=="Remittance") echo 'selected';?>>Remittance</option>
<option value="Investment"  <?php if($data[$i]['source_of_income']=="Investment") echo 'selected';?>>Investment</option>
<option value="Others"  <?php if($data[$i]['source_of_income']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Monthly Expenses</label>
<input type="text" value="<?=$data[$i]['inc_expce_mothly_expence']?>"  placeholder="Monthly Expenses" class="form-control" name="MemberMonthlyExpenses[]" id="MemberMonthlyExpenses">
</div>
</div>
</div>

<?
$z++; }

?>