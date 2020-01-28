<div class="row">
  <div class="col-sm-12">
    <label>Generate Tag Number </label>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <input type="text" required readonly  value="<?=$data[0]['tag_number']?>" placeholder="Generate Tag Number" class="form-control" name="tag_number" id="GenerateTagNumber">
  </div>
  <!-- <div class="col-xs-2"> <a  id="btn-generate-tag-number"  >Generate</a></div> -->
</div>
<div class="row">
  <div class="col-sm-12">
    <label class="labelHeader">Enter Area</label>
    <select class="form-control" name="area" required>
      <!-- <option value="">Enter Area</option> -->
      <option value="1–Parokya Rd" <?php if($data[0]['area']=="1–Parokya Rd") echo 'selected';?>>1–Parokya Rd</option>
      <option value="1-Seminaryo" <?php if($data[0]['area']=="1-Seminaryo") echo 'selected';?>>1-Seminaryo</option>
      <option value="1-Urbano" <?php if($data[0]['area']=="1-Urbano") echo 'selected';?>>1-Urbano</option>
      <option value="1-Esperanza" <?php if($data[0]['area']=="1-Esperanza") echo 'selected';?>>1-Esperanza</option>
      <option value="1-Carreon" <?php if($data[0]['area']=="1-Carreon") echo 'selected';?>>1-Carreon</option>
      <option value="1-Goodwill Townhomes" <?php if($data[0]['area']=="1-Goodwill Townhomes") echo 'selected';?>>1-Goodwill Townhomes</option>
      <option value="1-Biglang-Awa" <?php if($data[0]['area']=="1-Biglang-Awa") echo 'selected';?>>1-Biglang-Awa</option>
      <option value="1-Seminaryo Looban" <?php if($data[0]['area']=="1-Seminaryo Looban") echo 'selected';?>>1-Seminaryo Looban</option>
      <option value="1–Armando" <?php if($data[0]['area']=="1–Armando") echo 'selected';?>>1–Armando</option>
      <option value="1–Goodwill Homes" <?php if($data[0]['area']=="1–Goodwill Homes") echo 'selected';?>>1–Goodwill Homes</option>
      <option value="2-Blas Roque" <?php if($data[0]['civil_status']=="2-Blas Roque") echo 'selected';?>>2-Blas Roque</option>
      <option value="2-Celina Drive" <?php if($data[0]['area']=="2-Celina Drive") echo 'selected';?>>2-Celina Drive</option>
      <option value="2-Ngiyaw" <?php if($data[0]['area']=="2-Ngiyaw") echo 'selected';?>>2-Ngiyaw</option>
      <option value="2-Goldhill" <?php if($data[0]['area']=="2-Goldhill") echo 'selected';?>>2-Goldhill</option>
      <option value="2-Sinagtala" <?php if($data[0]['area']=="2-Sinagtala") echo 'selected';?>>2-Sinagtala</option>
      <option value="2-Quirino Highway" <?php if($data[0]['area']=="2-Quirino Highway") echo 'selected';?>>2-Quirino Highway</option>
      <option value="2-Callejon" <?php if($data[0]['area']=="2-Callejon") echo 'selected';?>>2-Callejon</option>
      <option value="3-Oro" <?php if($data[0]['area']=="3-Oro") echo 'selected';?>>3-Oro</option>
      <option value="3-Uping" <?php if($data[0]['area']=="3-Uping") echo 'selected';?>>3-Uping</option>
      <option value="3-Pinera" <?php if($data[0]['area']=="3-Pinera") echo 'selected';?>>3-Pinera</option>
      <option value="3-San Pedro 9" <?php if($data[0]['area']=="3-San Pedro 9") echo 'selected';?>>3-San Pedro 9</option>
      <option value="3-Maloles" <?php if($data[0]['area']=="3-Maloles") echo 'selected';?>>3-Maloles</option>
      <option value="3-Babina" <?php if($data[0]['area']=="3-Babina") echo 'selected';?>>3-Babina</option>
      <option value="3-Francisco" <?php if($data[0]['area']=="3-Francisco") echo 'selected';?>>3-Francisco</option>
      <option value="3-Katipunan Kanan" <?php if($data[0]['area']=="3-Katipunan Kanan") echo 'selected';?>>3-Katipunan Kanan</option>
      <option value="3-Unang Tangke" <?php if($data[0]['area']=="3-Unang Tangke") echo 'selected';?>>3-Unang Tangke</option>
      <option value="3-Daniac" <?php if($data[0]['area']=="3-Daniac") echo 'selected';?>>3-Daniac</option>
      <option value="3-Kasiyahan" <?php if($data[0]['area']=="3-Kasiyahan") echo 'selected';?>>3-Kasiyahan</option>
      <option value="3-Bicol Compound" <?php if($data[0]['area']=="3-Bicol Compound") echo 'selected';?>>3-Bicol Compound</option>
      <option value="3-Alipio Compound" <?php if($data[0]['area']=="3-Alipio Compound") echo 'selected';?>>3-Alipio Compound</option>
      <option value="4-Dupax" <?php if($data[0]['area']=="4-Dupax") echo 'selected';?>>4-Dupax</option>
      <option value="4-Wings" <?php if($data[0]['area']=="4-Wings") echo 'selected';?>>4-Wings</option>
      <option value="4-Santos" <?php if($data[0]['area']=="4-Santos") echo 'selected';?>>4-Santos</option>
      <option value="4-Gresar" <?php if($data[0]['area']=="4-Gresar") echo 'selected';?>>4-Gresar</option>
      <option value="4-Franco" <?php if($data[0]['area']=="4-Franco") echo 'selected';?>>4-Franco</option>
      <option value="4-Katipunan Kaliwa" <?php if($data[0]['area']=="4-Katipunan Kaliwa") echo 'selected';?>>4-Katipunan Kaliwa</option>
      <option value="4-Coronel" <?php if($data[0]['area']=="4-Coronel") echo 'selected';?>>4-Coronel</option>
      <option value="4-Mantikaan" <?php if($data[0]['area']=="4-Mantikaan") echo 'selected';?>>4-Mantikaan</option>
      <option value="4-Likas" <?php if($data[0]['area']=="4-Likas") echo 'selected';?>>4-Likas</option>
      <option value="5-Ibayo I & II" <?php if($data[0]['area']=="5-Ibayo I & II") echo 'selected';?>>5-Ibayo I & II</option>
      <option value="5-Maligaya" <?php if($data[0]['area']=="5-Maligaya") echo 'selected';?>>5-Maligaya</option>
      <option value="5-Leon Cleofas" <?php if($data[0]['area']=="5-Leon Cleofas") echo 'selected';?>>5-Leon Cleofas</option>
      <option value="5-St. Michael" <?php if($data[0]['area']=="5-St. Michael") echo 'selected';?>>5-St. Michael</option>
      <option value="5-Urcia" <?php if($data[0]['area']=="5-Urcia") echo 'selected';?>>5-Urcia</option>
      <option value="5-Magno" <?php if($data[0]['area']=="5-Magno") echo 'selected';?>>5-Magno</option>
      <option value="5-Bernarty" <?php if($data[0]['area']=="5-Bernarty") echo 'selected';?>>5-Bernarty</option>
      <option value="6-Don Julio Gregorio" <?php if($data[0]['area']=="6-Don Julio Gregorio") echo 'selected';?>>6-Don Julio Gregorio</option>
      <option value="6-Richland, Marides" <?php if($data[0]['area']=="6-Richland, Marides") echo 'selected';?>>6-Richland, Marides</option>
      <option value="6-Abbey Rd" <?php if($data[0]['area']=="6-Abbey Rd") echo 'selected';?>>6-Abbey Rd</option>
      <option value="6-Manggahan" <?php if($data[0]['area']=="6-Manggahan") echo 'selected';?>>6-Manggahan</option>
      <option value="6-Road 1-4" <?php if($data[0]['area']=="6-Road 1-4") echo 'selected';?>>6-Road 1-4</option>
      <option value="6-Narra" <?php if($data[0]['area']=="6-Narra") echo 'selected';?>>6-Narra</option>
      <option value="6-Progressive" <?php if($data[0]['area']=="6-Progressive") echo 'selected';?>>6-Progressive</option>
      <option value="6-Phase 1 & 2" <?php if($data[0]['area']=="6-Phase 1 & 2") echo 'selected';?>>6-Phase 1 & 2</option>
      <option value="6-De Assis" <?php if($data[0]['area']=="6-De Assis") echo 'selected';?>>6-De Assis</option>
      <option value="7-Seminaryo" <?php if($data[0]['area']=="7-Seminaryo") echo 'selected';?>>7-Seminaryo</option>
      <option value="7-Remarville Ave" <?php if($data[0]['area']=="7-Remarville Ave") echo 'selected';?>>7-Remarville Ave</option>
      <option value="7-Zodiac" <?php if($data[0]['area']=="7-Zodiac") echo 'selected';?>>7-Zodiac</option>
      <option value="7-Apollo" <?php if($data[0]['area']=="7-Apollo") echo 'selected';?>>7-Apollo</option>
      <option value="7-Old Paliguan" <?php if($data[0]['area']=="7-Old Paliguan") echo 'selected';?>>7-Old Paliguan</option>
      <option value="7-Gawad Kalinga" <?php if($data[0]['area']=="7-Gawad Kalinga") echo 'selected';?>>7-Gawad Kalinga</option>
      <option value="7-Remarville Subdivision" <?php if($data[0]['area']=="7-Remarville Subdivision") echo 'selected';?>>7-Remarville Subdivision</option>
      <option value="7-Manilog" <?php if($data[0]['area']=="7-Manilog") echo 'selected';?>>7-Manilog</option>  
    </select>
  </div>
</div>

<div class="row headerRow">
  <div class="col-sm-12">
    <label>Enter Name of Head of the Family </label>
  </div>
</div>
<div class="row">
  <div class="col-sm-12">
    <label class="labelHeader">Surname</label>
    <input type="text" placeholder="Surname"  required value="<?=$data[0]['sur_name']?>"  class="form-control" name="Surname" id="Surname">
  </div>
</div>

<div class="row">
<div class="col-sm-12">
 <label class="labelHeader">Name</label>
<input type="text" placeholder="Name" required value="<?=$data[0]['name']?>"     class="form-control" name="Name" id="Name">
</div>
</div>

<div class="row">
<div class="col-sm-12">
 <label class="labelHeader">Middle Initial</label>
<input type="text" placeholder="Middle Initial"  maxlength="50" value="<?=$data[0]['middle_name']?>" class="form-control" name="MiddleInitial" id="MiddleInitial">
</div>
</div>

<div class="row headerRow">
<div class="col-sm-12">
<label>Enter Address of Head of the Family</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Province</label>
<input type="text" placeholder="Province" value="<?=isset($data[0]['province']) && $data[0]['province'] != "" ? $data[0]['province'] : "Metro Manila"?>" required class="form-control" name="Province" id="Province">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">City</label>
<input type="text" placeholder="City" value="<?=isset($data[0]['city']) && $data[0]['city'] != "" ? $data[0]['city'] : "Quezon"?>" required class="form-control" name="City" id="City">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Barangay</label>
<input type="text" placeholder="Barangay" value="<?=isset($data[0]['barangay']) && $data[0]['barangay'] != "" ? $data[0]['barangay'] : "Bagbag"?>" required class="form-control" name="Barangay" id="Barangay">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Street</label>
<input type="text" placeholder="Street" required value="<?=$data[0]['street']?>" class="form-control" name="Street" id="Street">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">House/Unit Number</label>
<input type="text" placeholder="House/Unit Number" value="<?=$data[0]['house_unit_number']?>"  maxlength="30" required class="form-control" name="HouseUnitNumber" id="HouseUnitNumber">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label>Barangay ID Number </label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<input type="text" readonly required placeholder="Generate Barangay ID Number"  value="<?=$data[0]['gen_barangay_ID']?>"   class="form-control" name="GenerateBarangayIDNumberhead" id="GenerateBarangayIDNumber">
</div>
</div>

<!-- <div class="col-xs-1">
  <a  id="btn-generate-barangay-id">Generate</a>
  </div>
</div>
 -->
<!-- <div class="row">
<div class="col-sm-12">
<select class="form-control">
<option value="">Select</option>
<option value="HeadoftheFamilyInformation">Head of the Family Information </option>
<option value="EnterHouseholdInformationofHeadoftheFamily">Enter Household Information of Head of the Family </option>
<option value="EnterMembersofFamilyInformation">Enter Members of Family Information </option>
<option value="Exit">Exit</option>
</select>
</div>
</div> -->

<div class="HeadoftheFamilyInformation" >
<div class="row headerRow">
<div class="col-sm-12">
<label>Head of the Family Information</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Enter Date of Birth (MM/DD/YY)</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Date of Birth (MM/DD/YY)"  value="<?=$data[0]['date_of_birth']?>"  required class="form-control datepicker" name="DateofBirth" id="DateofBirth">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Place of Birth</label>
<input type="text" placeholder="Place of Birth" value="<?=$data[0]['place_of_birth']?>"  maxlength="255"  class="form-control" name="place_of_birth" id="place_of_birth">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Civil Status</label>
<select class="form-control" name="CivilStatus">
<!-- <option value="">Civil Status</option> -->
<option value="Single"  <?php if($data[0]['civil_status']=="Single") echo 'selected';?>>Single</option>
<option value="Married" <?php if($data[0]['civil_status']=="Married") echo 'selected';?>>Married</option>
<option value="Living-in" <?php if($data[0]['civil_status']=="Living-in") echo 'selected';?>>Living-in</option>
<option value="Separated"  <?php if($data[0]['civil_status']=="Separated") echo 'selected';?>>Separated</option>
<option value="Widow"  <?php if($data[0]['civil_status']=="Widow") echo 'selected';?>>Widow</option>
<option value="Partner"  <?php if($data[0]['civil_status']=="Partner") echo 'selected';?>>Partner</option>
<option value="Divorced"  <?php if($data[0]['civil_status']=="Divorced") echo 'selected';?>>Divorced</option>
<option value="Unknown"  <?php if($data[0]['civil_status']=="Unknown") echo 'selected';?>>Unknown</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Sex</label>
<select class="form-control" name="Sex">
<!-- <option value="">Sex</option> -->
<option value="Male"  <?php if($data[0]['sex']=="Male") echo 'selected';?>>Male </option>
<option value="Female"  <?php if($data[0]['sex']=="Female") echo 'selected';?>>Female</option>
<option value="SOGIE"  <?php if($data[0]['sex']=="SOGIE") echo 'selected';?>>SOGIE</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Citizenship</label>
<select class="form-control" name="citizenship">
<!-- <option value="">Sex</option> -->
<option value="Filipino"  <?php if($data[0]['citizenship']=="Filipino") echo 'selected';?>>Filipino </option>
<option value="Non-Filipino"  <?php if($data[0]['citizenship']=="Non-Filipino") echo 'selected';?>>Non-Filipino</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Nationality if Non-Filipino</label>
<input type="text" placeholder="Nationality if Non-Filipino" maxlength="255" value="<?=$data[0]['nationality_if_non_filipino']?>"  class="form-control" name="nationality_if_non_filipino" id="nationality_if_non_filipino">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Ethnicity (Tagalog, Bisaya, Bicolona Etc.)</label>
<input type="text" placeholder="Ethnicity (Tagalog/Bicolona/Bisaya etc)" maxlength="255" value="<?=$data[0]['ethnicity']?>"  class="form-control" name="ethnicity" id="ethnicity">
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
<input type="text" placeholder="Landline"  maxlength="15" value="<?=$data[0]['landline']?>"  class="form-control" name="Landline" id="Landline">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Mobile</label>
<input type="text" placeholder="Mobile"maxlength="15"  value="<?=$data[0]['mobile']?>"  class="form-control" name="Mobile" id="Mobile">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Email Address</label>
<input type="email" placeholder="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" value="<?=$data[0]['email']?>"  class="form-control" name="EmailAddress" id="EmailAddress">
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Status of Residency</label>
<select class="form-control" name="StatusofResidency">
<!-- <option value="">Status of Residency</option> -->
<option value="Voter"  <?php if($data[0]['status_residency']=="Voter") echo 'selected';?>>Voter  </option>
<option value="Non-Voter"  <?php if($data[0]['status_residency']=="Non-Voter") echo 'selected';?>>Non-Voter</option>
<option value="Registering"  <?php if($data[0]['status_residency']=="Registering") echo 'selected';?>>Registering</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Special Disability</label>
<select class="form-control" name="SpecialDisabilityorSeniorCitizen">
<!-- <option value="">Special Disability or Senior Citizen</option> -->
<option value="Psychosocial"  <?php if($data[0]['special_disability_or']=="Psychosocial") echo 'selected';?>>Psychosocial  </option>
<option value="Mental Disability"  <?php if($data[0]['special_disability_or']=="Mental Disability") echo 'selected';?>>Mental Disability  </option>
<option value="Visual Disability"  <?php if($data[0]['special_disability_or']=="Visual Disability") echo 'selected';?>>Visual Disability  </option>
<option value="Learning Disability"  <?php if($data[0]['special_disability_or']=="Learning Disability") echo 'selected';?>>Learning Disability  </option>
<option value="Speech Impairment"  <?php if($data[0]['special_disability_or']=="Speech Impairment") echo 'selected';?>>Speech Impairment  </option>
<option value="Orthopedic (Musculoskeletal)"  <?php if($data[0]['special_disability_or']=="Orthopedic (Musculoskeletal)") echo 'selected';?>>Orthopedic (Musculoskeletal)</option>
<option value="Not Applicable"  <?php if($data[0]['special_disability_or']=="Not Applicable" || empty($data[0]['special_disability_or'])) echo 'selected';?>>Not Applicable  </option>
<option value="Others"  <?php if($data[0]['special_disability_or']=="Others") echo 'selected';?>>Others  </option>

</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Disability</label>
<input type="text" placeholder="Specify Disability"  value="<?=$data[0]['specify_disability']?>"  class="form-control" name="specify_disability" id="specify_disability">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Senior Citizen</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio"  value="yes"  <?php if($data[0]['senior_citizen']=="yes") echo 'checked';?> class="HealthBenefits"  name="senior_citizen" id="senior_citizenYes">   <label> Yes </label>
<input type="radio" value="no"  checked <?php if($data[0]['senior_citizen']=="no" || empty($data[0]['senior_citizen'])) echo 'checked';?>  class="HealthBenefits"   name="senior_citizen" id="senior_citizenNo">  <label> No  </label>
<br>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Solo Parent</label>
<select class="form-control" name="solo_parent">
<!-- <option value="">Special Disability or Senior Citizen</option> -->
<option value="Registered Solo Parent"  <?php if($data[0]['solo_parent']=="Registered Solo Parent") echo 'selected';?>>Registered Solo Parent  </option>
<option value="Non-Solo Parent"  <?php if($data[0]['solo_parent']=="Non-Solo Parent") echo 'selected';?>>Non-Solo Parent  </option>
<option value="Unregistered Parent"  <?php if($data[0]['solo_parent']=="Unregistered Parent") echo 'selected';?>>Unregistered Parent  </option>
<option value="Not Applicable"  <?php if($data[0]['solo_parent']=="Not Applicable" || empty($data[0]['solo_parent'])) echo 'selected';?>>Not Applicable  </option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Religion</label>
<select class="form-control" name="Religion">
<!-- <option value="">Religion</option> -->
<option value="Catholic" <?php if($data[0]['religion']=="Catholic") echo 'selected';?>>Catholic</option>
<option value="Iglesia ni Kristo" <?php if($data[0]['religion']=="Iglesia ni Kristo") echo 'selected';?>>Iglesia ni Kristo</option>
<option value="Ang Dating Daan" <?php if($data[0]['religion']=="Ang Dating Daan") echo 'selected';?>>Ang Dating Daan</option>
<option value="Born Again Christian" <?php if($data[0]['religion']=="Born Again Christian") echo 'selected';?>>Born Again Christian</option>
<option value="Jehova’s Witness" <?php if($data[0]['religion']=="Jehova’s Witness") echo 'selected';?>>Jehova’s Witness</option>
<option value="Aglipay" <?php if($data[0]['religion']=="Aglipay") echo 'selected';?>>Aglipay</option>
<option value="7th Day Adventist" <?php if($data[0]['religion']=="7th Day Adventist") echo 'selected';?>>7th Day Adventist</option>
<option value="Methodist" <?php if($data[0]['religion']=="Methodist") echo 'selected';?>>Methodist</option>
<option value="Muslim" <?php if($data[0]['religion']=="Muslim") echo 'selected';?>>Muslim</option>
<option value="Hindu" <?php if($data[0]['religion']=="Hindu") echo 'selected';?>>Hindu</option>
<option value="Budhist" <?php if($data[0]['religion']=="Budhist") echo 'selected';?>>Budhist </option>
<option value="Others" <?php if($data[0]['religion']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">LGBTI</label>
<select class="form-control" name="LGBTI">
<!-- <option value="">LGBTI</option> -->
<option value="Lesbian" <?php if($data[0]['LGBTI']=="Lesbian") echo 'selected';?>>Lesbian</option>
<option value="Gay" <?php if($data[0]['LGBTI']=="Gay") echo 'selected';?>>Gay</option>
<option value="Bisexual" <?php if($data[0]['LGBTI']=="Bisexual") echo 'selected';?>>Bisexual</option>
<option value="Transgender" <?php if($data[0]['LGBTI']=="Transgender") echo 'selected';?>>Transgender</option>
<option value="Intersex" <?php if($data[0]['LGBTI']=="Intersex") echo 'selected';?>>Intersex</option>
<option value="Not Applicable" <?php if($data[0]['LGBTI']=="Not Applicable" || empty($data[0]['LGBTI'])) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Status of Work</label>
<select class="form-control" name="StatusofWork">
<!-- <option value="">Status of Work</option> -->
<option value="Casual"  <?php if($data[0]['work_status']=="Casual") echo 'selected';?>>Casual</option>
<option value="Sole Proprietorship"  <?php if($data[0]['work_status']=="Sole Proprietorship") echo 'selected';?>>Sole Proprietorship</option>
<option value="Partnership"  <?php if($data[0]['work_status']=="Partnership") echo 'selected';?>>Partnership</option>
<option value="Corporation"  <?php if($data[0]['work_status']=="Corporation") echo 'selected';?>>Corporation</option>
<option value="Not Applicable"  <?php if($data[0]['work_status']=="Not Applicable" || empty($data[0]['work_status'])) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row headerRow">
<div class="col-sm-12">
<label>Place of Work</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Barangay Name</label>
<input type="text"   value="<?=$data[0]['place_of_work_barangay_name']?>"  placeholder="Barangay Name" class="form-control" name="place_of_work_barangay_name" id="place_of_work_barangay_name">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">City/Municipality </label>
<input type="text"   value="<?=$data[0]['place_of_work_city_municipality']?>"  placeholder="City/Municipality" class="form-control" name="place_of_work_city_municipality" id="place_of_work_city_municipality">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Province </label>
<input type="text"   value="<?=$data[0]['place_of_work_province']?>"  placeholder="Province" class="form-control" name="place_of_work_province" id="place_of_work_province">
</div>
</div>

<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Occupation</label>
<select class="form-control" name="Occupation">
<!-- <option value="">Occupation</option> -->
<option value="Employee"  <?php if($data[0]['occupation']=="Employee") echo 'selected';?>>Employee</option>
<option value="Business-Registered"  <?php if($data[0]['occupation']=="Business-Registered") echo 'selected';?>>Business-Registered</option>
<option value="Business-Not Registered"  <?php if($data[0]['occupation']=="Business-Not Registered") echo 'selected';?>>Business-Not Registered</option>
<option value="Unemployed"  <?php if($data[0]['occupation']=="Unemployed") echo 'selected';?>>Unemployed</option>
<option value="Student"  <?php if($data[0]['occupation']=="Student") echo 'selected';?>>Student</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">If Student</label>
<select class="form-control" name="IfStudent">
<!-- <option value="">If Student</option> -->
<option value="Private School" <?php if($data[0]['if_student']=="Private School") echo 'selected';?>>Private School</option>
<option value="Public School" <?php if($data[0]['if_student']=="Public School") echo 'selected';?>>Public School</option>
<option value="Not Applicable" <?php if($data[0]['if_student']=="Not Applicable") echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label>Enrolled?</label><br>
<input type="radio"  value="yes"   <?php if($data[0]['is_student_enrolled']=="yes") echo 'checked';?>   name="is_student_enrolled" id="is_student_enrolledyes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['is_student_enrolled']=="no") echo 'checked';?>   name="is_student_enrolled" id="is_student_enrolledNo">  <label>No</label>

</div>
</div>


<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Nature of Work</label>
<select class="form-control" name="NatureofWork">
<!-- <option value="">Nature of Work</option> -->
<option value="Professional" <?php if($data[0]['work_nature']=="Professional") echo 'selected';?>>Professional</option>
<option value="Not Professional" <?php if($data[0]['work_nature']=="Not Professional") echo 'selected';?>>Not Professional</option>
<option value="Informal Sector" <?php if($data[0]['work_nature']=="Informal Sector") echo 'selected';?>>Informal Sector</option>
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
<input type="text"  value="<?=$data[0]['profession_name']?>" placeholder="Name (eg. Doctor,Dentist,Engineer,etc)" class="form-control" name="ProfessionName" id="ProfessionName">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Industry (Medical,IT,Engineering, etc)</label>
<input type="text"  value="<?=$data[0]['profession_industry']?>"  maxlength="60" placeholder="Industry (Medical,IT,Engineering, etc)" class="form-control" name="ProfessionIndustry" id="ProfessionIndustry">
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Working Sector</label>
<select class="form-control" name="WorkingSector">
<!-- <option value="">Working Sector</option> -->
<option value="Private" <?php if($data[0]['work_sector']=="Private") echo 'selected';?>>Private</option>
<option value="Public (Government)"  <?php if($data[0]['work_sector']=="Public (Government)") echo 'selected';?>>Public (Government)</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Valid CTC?</label>
<select class="form-control" name="have_a_valid_ctc">
<!-- <option value="">Working Sector</option> -->
<option value="yes" <?php if($data[0]['have_a_valid_ctc']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['have_a_valid_ctc']=="no" || empty($data[0]['have_a_valid_ctc'])) echo 'selected';?>>No</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">CTC from Barangay?</label>
<select class="form-control" name="ctc_issued_barangay">
<!-- <option value="">Working Sector</option> -->
<option value="yes" <?php if($data[0]['ctc_issued_barangay']=="yes") echo 'selected';?>>Yes</option>
<option value="no" checked <?php if($data[0]['ctc_issued_barangay']=="no" || empty($data[0]['ctc_issued_barangay']) ) echo 'selected';?>>No</option>
</select>
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label>Health Benefits</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio"  value="Philhealth"  <?php if($data[0]['helth_benefit']=="Philhealth") echo 'checked';?> class="HealthBenefits"  name="HealthBenefits" id="PhilhealthYes">   <label> Philhealth </label>


<input type="radio" value="HMO"  <?php if($data[0]['helth_benefit']=="HMO") echo 'checked';?>  class="HealthBenefits"   name="HealthBenefits" id="HMOYes">  <label> HMO  </label>
<br>

<input type="radio" value="Other Health Insurance"  class="HealthBenefits"  <?php if($data[0]['helth_benefit']=="Other Health Insurance") echo 'checked';?>  name="HealthBenefits" id="OtherHealthInsuranceYes">   <label> Other Health Insurance </label>

<input type="radio" value="None"  class="HealthBenefits"   <?php if($data[0]['helth_benefit']=="None") echo 'checked';?>  name="HealthBenefits" id="NoneYes">  <label> None </label>
<input type="text" hidden  value="<?=$data[0]['helth_benefit_other']?>" placeholder="Other Health Benefits" class="form-control" name="OtherHealthBenefits"  id="OtherHealthBenefits">
</div>
</div>

<br>
<div class="row">
<div class="col-sm-12">
<label>Membership or Affiliation with a Financial Institution</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio"  value="Pagibig Fund (HDMF)"   <?php if($data[0]['membership_or_affiliation']=="Pagibig Fund (HDMF)") echo 'checked';?>   name="Membership" id="PagibigFundYes">  <label> Pagibig Fund (HDMF)    </label>


<input type="radio" class="Membership"  value="SSS"   <?php if($data[0]['membership_or_affiliation']=="SSS") echo 'checked';?>   name="Membership" id="SSSYes">  <label>  SSS    </label>


<input type="radio" class="Membership"   value="GSIS"   <?php if($data[0]['membership_or_affiliation']=="GSIS") echo 'checked';?>   name="Membership" id="GSISYes">  <label>GSIS </label>
<br>
<input type="radio" class="Membership"   value="Cooperative"   <?php if($data[0]['membership_or_affiliation']=="Cooperative") echo 'checked';?>   name="Membership" id="CooperativeYes">  <label>Cooperative </label>


<input type="radio" class="Membership"   value="Other"   <?php if($data[0]['membership_or_affiliation']=="Other") echo 'checked';?>   name="Membership" id="PagibigFundYes">  <label> Other    </label>

</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[0]['membership_or_affiliation_other']?>" maxlength="100" placeholder="Specify Others" class="form-control" name="OtherMembership" id="OtherMembership">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Place of Delivery</label>
<select class="form-control" name="place_of_delivery">
<!-- <option value="">Educational Attainment</option> -->

<option value="Public Hospital"  <?php if($data[0]['place_of_delivery']=="Public Hospital") echo 'selected';?>>Public Hospital</option>
<option value="Private Hospital"  <?php if($data[0]['place_of_delivery']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Lying-in Clinic"  <?php if($data[0]['place_of_delivery']=="Lying-in Clinic") echo 'selected';?>>Lying-in Clinic</option>
<option value="Home"  <?php if($data[0]['place_of_delivery']=="Home") echo 'selected';?>>Home</option>
<option value="Others"  <?php if($data[0]['place_of_delivery']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[0]['place_of_delivery']=="Not Applicable" || empty($data[0]['place_of_delivery']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[0]['place_of_delivery_specify_other']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="place_of_delivery_specify_other" id="place_of_delivery_specify_other">
</div>
</div> -->



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Person who Assisted the Delivery</label>
<select class="form-control" name="person_assisted_delivery">
<!-- <option value="">Educational Attainment</option> -->
<option value="Doctor"  <?php if($data[0]['person_assisted_delivery']=="Doctor") echo 'selected';?>>Doctor</option>
<option value="Nurse"  <?php if($data[0]['person_assisted_delivery']=="Nurse") echo 'selected';?>>Nurse</option>
<option value="Midwife"  <?php if($data[0]['person_assisted_delivery']=="Midwife") echo 'selected';?>>Midwife</option>
<option value="Hilot"  <?php if($data[0]['person_assisted_delivery']=="Hilot") echo 'selected';?>>Hilot</option>
<option value="Others"  <?php if($data[0]['person_assisted_delivery']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"   <?php if($data[0]['person_assisted_delivery']=="Not Applicable" || empty($data[0]['person_assisted_delivery']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[0]['person_assisted_delivery_specify_other']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="person_assisted_delivery_specify_other" id="person_assisted_delivery_specify_other">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Last Vaccine Received</label>
<input type="text" placeholder="Last Vaccine Received"  value="<?=$data[0]['last_vaccine_received']?>"  class="form-control" name="last_vaccine_received" id="last_vaccine_received">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Number of Pregnancies(for Mothers)</label>
<input type="text" placeholder="No. of Pregnancies(For Mothers)"  value="<?=$data[0]['no_of_pregnancies_for_mothers']?>"  class="form-control" name="no_of_pregnancies_for_mothers" id="no_of_pregnancies_for_mothers">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Number of Children Living(for Mothers)</label>
<input type="text" placeholder="No. of Children Living(For Mothers)"  value="<?=$data[0]['no_of_children_living_for_mothers']?>"  class="form-control" name="no_of_children_living_for_mothers" id="no_of_children_living_for_mothers">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Family Planning Method</label>
<select class="form-control" name="family_planning_method">
<!-- <option value="">Educational Attainment</option> -->

<option value="Ligation"  <?php if($data[0]['family_planning_method']=="Ligation") echo 'selected';?>>Ligation</option>
<option value="Vasectomy"  <?php if($data[0]['family_planning_method']=="Vasectomy") echo 'selected';?>>Vasectomy</option>
<option value="IUD"  <?php if($data[0]['family_planning_method']=="IUD") echo 'selected';?>>IUD</option>
<option value="Injectables"  <?php if($data[0]['family_planning_method']=="Injectables") echo 'selected';?>>Injectables</option>
<option value="Implant"  <?php if($data[0]['family_planning_method']=="Implant") echo 'selected';?>>Implant</option>
<option value="Pill"  <?php if($data[0]['family_planning_method']=="Pill") echo 'selected';?>>Pill</option>
<option value="Condom"  <?php if($data[0]['family_planning_method']=="Condom") echo 'selected';?>>Condom</option>
<option value="Natural"  <?php if($data[0]['family_planning_method']=="Natural") echo 'selected';?>>Natural</option>
<option value="LAM"  <?php if($data[0]['family_planning_method']=="LAM") echo 'selected';?>>LAM</option>
<option value="Traditional"  <?php if($data[0]['family_planning_method']=="Traditional") echo 'selected';?>>Traditional</option>
<option value="None"  <?php if($data[0]['family_planning_method']=="None") echo 'selected';?>>None</option>
<option value="Not Applicable"  <?php if($data[0]['family_planning_method']=="Not Applicable" || empty($data[0]['family_planning_method']) ) echo 'selected';?>>Not Applicable</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Source of Family Planning Method</label>
<select class="form-control" name="source_family_planning_method">
<!-- <option value="">Educational Attainment</option> -->

<option value="Government Hospital"  <?php if($data[0]['source_family_planning_method']=="Government Hospital") echo 'selected';?>>Government Hospital</option>
<option value="RHU/Health Station"  <?php if($data[0]['source_family_planning_method']=="RHU/Health Station") echo 'selected';?>>RHU/Health Station</option>
<option value="Private Hospital"  <?php if($data[0]['source_family_planning_method']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Pharmacy"  <?php if($data[0]['source_family_planning_method']=="Pharmacy") echo 'selected';?>>Pharmacy</option>
<option value="Others"  <?php if($data[0]['source_family_planning_method']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[0]['source_family_planning_method']=="Not Applicable" || empty($data[0]['source_family_planning_method']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"  value="<?=$data[0]['source_family_planning_method_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="source_family_planning_method_others" id="source_family_planning_method_others">
</div>
</div>




<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Intention to Use Family Planning Method</label>
<select class="form-control" name="intension_use_family_planning">
<!-- <option value="">Educational Attainment</option> -->
<option value="yes"  <?php if($data[0]['intension_use_family_planning']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['intension_use_family_planning']=="no") echo 'selected';?>>No</option>
<option value="Not Applicable"  <?php if($data[0]['intension_use_family_planning']=="Not Applicable" || empty($data[0]['intension_use_family_planning']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
  <input type="text"  value="<?=$data[0]['intension_use_family_planning_reasons']?>" maxlength="255" placeholder="Specify Reason" class="form-control" name="intension_use_family_planning_reasons" id="intension_use_family_planning_reasons">
</div>
</div>




<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Status of Philhealth</label>
<select class="form-control" name="philhealth_status">
<!-- <option value="">Educational Attainment</option> -->
<option value="Paying Member"  <?php if($data[0]['philhealth_status']=="Paying Member") echo 'selected';?>>Paying Member</option>
<option value="Dependent of Paying Member"  <?php if($data[0]['philhealth_status']=="Dependent of Paying Member") echo 'selected';?>>Dependent of Paying Member</option>
<option value="Indigent Member"  <?php if($data[0]['philhealth_status']=="Indigent Member") echo 'selected';?>>Indigent Member</option>
<option value="Dependent of Indigent"  <?php if($data[0]['philhealth_status']=="Dependent of Indigent") echo 'selected';?>>Dependent of Indigent</option>
<option value="Not Applicable"  <?php if($data[0]['philhealth_status']=="Not Applicable" || empty($data[0]['philhealth_status']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Health Facility Visited in Past 12 Months</label>
<select class="form-control" name="health_facility_visited_12_months">
<!-- <option value="">Educational Attainment</option> -->
<option value="Government Hospital"  <?php if($data[0]['health_facility_visited_12_months']=="Government Hospital") echo 'selected';?>>Government Hospital</option>
<option value="RHU/Health Station"  <?php if($data[0]['health_facility_visited_12_months']=="RHU/Health Station") echo 'selected';?>>RHU/Health Station</option>
<option value="Barangay Health Station"  <?php if($data[0]['health_facility_visited_12_months']=="Barangay Health Station") echo 'selected';?>>Barangay Health Station</option>
<option value="Private Hospital"  <?php if($data[0]['health_facility_visited_12_months']=="Private Hospital") echo 'selected';?>>Private Hospital</option>
<option value="Private Clinic"  <?php if($data[0]['health_facility_visited_12_months']=="Private Clinic") echo 'selected';?>>Private Clinic</option>
<option value="Pharmacy"  <?php if($data[0]['health_facility_visited_12_months']=="Pharmacy") echo 'selected';?>>Pharmacy</option>
<option value="Hilot/Herbalist"  <?php if($data[0]['health_facility_visited_12_months']=="Hilot/Herbalist") echo 'selected';?>>Hilot/Herbalist</option>
<option value="Others"  <?php if($data[0]['health_facility_visited_12_months']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[0]['health_facility_visited_12_months']=="Not Applicable" ||  empty($data[0]['health_facility_visited_12_months']) ) echo 'selected';?>>Not Applicable</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
  <input type="text"  value="<?=$data[0]['health_facility_visited_12_months_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="health_facility_visited_12_months_others" id="health_facility_visited_12_months_others">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Reason for Visiting Health Facility</label>
<select class="form-control" name="reason_visiting_health_facility">
<!-- <option value="">Educational Attainment</option> -->
<option value="Sick/Injured"  <?php if($data[0]['reason_visiting_health_facility']=="Sick/Injured") echo 'selected';?>>Sick/Injured</option>
<option value="Prenatal/Postnatal"  <?php if($data[0]['reason_visiting_health_facility']=="Prenatal/Postnatal") echo 'selected';?>>Prenatal/Postnatal</option>
<option value="Gave Birth"  <?php if($data[0]['reason_visiting_health_facility']=="Gave Birth") echo 'selected';?>>Gave Birth</option>
<option value="Dental"  <?php if($data[0]['reason_visiting_health_facility']=="Dental") echo 'selected';?>>Dental</option>
<option value="Medical Check-up"  <?php if($data[0]['reason_visiting_health_facility']=="Medical Check-up") echo 'selected';?>>Medical Check-up</option>
<option value="Medical Requirement"  <?php if($data[0]['reason_visiting_health_facility']=="Medical Requirement") echo 'selected';?>>Medical Requirement</option>
<option value="NHTS/CCT/4Ps Requirement"  <?php if($data[0]['reason_visiting_health_facility']=="NHTS/CCT/4Ps Requirement") echo 'selected';?>>NHTS/CCT/4Ps Requirement</option>
<option value="Others"  <?php if($data[0]['reason_visiting_health_facility']=="Others") echo 'selected';?>>Others</option>
<option value="Not Applicable"  <?php if($data[0]['reason_visiting_health_facility']=="Not Applicable" || empty($data[0]['reason_visiting_health_facility'])) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
  <input type="text"  value="<?=$data[0]['reason_visiting_health_facility_others']?>" maxlength="255" placeholder="Specify Others" class="form-control" name="reason_visiting_health_facility_others" id="reason_visiting_health_facility_others">
</div>
</div>











<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Educational Attainment</label>
<select class="form-control" name="EducationalAttainment">
<!-- <option value="">Educational Attainment</option> -->
<option value="No Education"  <?php if($data[0]['educational_attainment']=="No Education") echo 'selected';?>>No Education</option>
<option value="Pre-school"  <?php if($data[0]['educational_attainment']=="Pre-school") echo 'selected';?>>Pre-school</option>
<option value="Elementary Level"  <?php if($data[0]['educational_attainment']=="Elementary Level") echo 'selected';?>>Elementary Level</option>
<option value="Elementary Graduate"  <?php if($data[0]['educational_attainment']=="Elementary Graduate") echo 'selected';?>>Elementary Graduate</option>
<option value="High School Level"  <?php if($data[0]['educational_attainment']=="High School Level") echo 'selected';?>>High School Level</option>
<option value="High School Graduate"  <?php if($data[0]['educational_attainment']=="High School Graduate") echo 'selected';?>>High School Graduate</option>
<option value="Junior HS"  <?php if($data[0]['educational_attainment']=="Junior HS") echo 'selected';?>>Junior HS</option>
<option value="Junior HS Graduate"  <?php if($data[0]['educational_attainment']=="Junior HS Graduate") echo 'selected';?>>Junior HS Graduate</option>

<!-- <option value="Senior HS graduate"  <?php if($data[0]['educational_attainment']=="Senior HS graduate") echo 'selected';?>>Senior HS graduate</option> -->

<option value="Vocational/Tech"  <?php if($data[0]['educational_attainment']=="Vocational/Tech") echo 'selected';?>>Vocational/Tech</option>
<option value="College Level"  <?php if($data[0]['educational_attainment']=="College Level") echo 'selected';?>>College Level</option>
<option value="College Graduate"  <?php if($data[0]['educational_attainment']=="College Graduate") echo 'selected';?>>College Graduate</option>
<option value="Post-Graduate"  <?php if($data[0]['educational_attainment']=="Post-Graduate") echo 'selected';?>>Post-Graduate</option>


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
<label class="labelHeader">Barangay Name</label>  
<input type="text"   value="<?=$data[0]['place_of_school_barangay_name']?>"  placeholder="Barangay Name" class="form-control" name="place_of_school_barangay_name" id="place_of_school_barangay_name">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">City/Municipality</label>  
<input type="text"   value="<?=$data[0]['place_of_school_city']?>"  placeholder="City/Municipality" class="form-control" name="place_of_school_city" id="place_of_school_city">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Province</label>  
<input type="text"   value="<?=$data[0]['place_of_school_province']?>"  placeholder="Province" class="form-control" name="place_of_school_province" id="place_of_school_province">
</div>
</div>



<br>
<div class="row">
<div class="col-sm-12">
<label>Skills and Certification </label>
</div>
</div>
<!-- <div class="row">
<div class="col-sm-12">
<input type="text" placeholder="Name of Certification" class="form-control" name="NameofCertification" id="NameofCertification">
</div>
</div> -->
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name of Certification</label>
<input type="text" placeholder="Name of Certification"  value="<?=$data[0]['skill_certification_name']?>"  class="form-control" name="NameofCertification" id="NameofCertification">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>Where Acquired </label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="radio" value="Tesda" <?php if($data[0]['acquired']=="Tesda") echo 'checked';?>   name="WhereAcquired" id="Tesda">  <label> Tesda </label>


<input type="radio" value="Private Institution" <?php if($data[0]['acquired']=="Private Institution") echo 'checked';?>  name="WhereAcquired" id="PrivateInstitution">  <label>Private Institution  </label>

</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name of Skills</label>
<input type="text" placeholder="Name of Skills"   value="<?=$data[0]['skill_name']?>"  class="form-control" name="NameofSkills" id="NameofSkills">
</div>
</div>


<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Skills Development Training of Interest</label>
<select class="form-control" name="skill_development_training_interest">
<!-- <option value="">Status of Work</option> -->
<option value="Refrigeration and Airconditioning"  <?php if($data[0]['skill_development_training_interest']=="Refrigeration and Airconditioning") echo 'selected';?>>Refrigeration and Airconditioning</option>
<option value="Automotive/Heavy Equipment Servicing"  <?php if($data[0]['skill_development_training_interest']=="Automotive/Heavy Equipment Servicing") echo 'selected';?>>Automotive/Heavy Equipment Servicing</option>
<option value="Metal Worker"  <?php if($data[0]['skill_development_training_interest']=="Metal Worker") echo 'selected';?>>Metal Worker</option>
<option value="Building Wiring Installation"  <?php if($data[0]['skill_development_training_interest']=="Building Wiring Installation") echo 'selected';?>>Building Wiring Installation</option>
<option value="Heavy Equipment Operation"  <?php if($data[0]['skill_development_training_interest']=="Heavy Equipment Operation") echo 'selected';?>>Heavy Equipment Operation</option>
<option value="Plumbing"  <?php if($data[0]['skill_development_training_interest']=="Plumbing") echo 'selected';?>>Plumbing</option>
<option value="Welding"  <?php if($data[0]['skill_development_training_interest']=="Welding") echo 'selected';?>>Welding</option>
<option value="Carpentry"  <?php if($data[0]['skill_development_training_interest']=="Carpentry") echo 'selected';?>>Carpentry</option>
<option value="Baking"  <?php if($data[0]['skill_development_training_interest']=="Baking") echo 'selected';?>>Baking</option>
<option value="Dress-making"  <?php if($data[0]['skill_development_training_interest']=="Dress-making") echo 'selected';?>>Dress-making</option>
<option value="Linguist"  <?php if($data[0]['skill_development_training_interest']=="Linguist") echo 'selected';?>>Linguist</option>
<option value="Computer Graphics"  <?php if($data[0]['skill_development_training_interest']=="Computer Graphics") echo 'selected';?>>Computer Graphics</option>
<option value="Painting"  <?php if($data[0]['skill_development_training_interest']=="Painting") echo 'selected';?>>Painting</option>
<option value="Beauty Care"  <?php if($data[0]['skill_development_training_interest']=="Beauty Care") echo 'selected';?>>Beauty Care</option>
<option value="Commercial Cooking"  <?php if($data[0]['skill_development_training_interest']=="Commercial Cooking") echo 'selected';?>>Commercial Cooking</option>
<option value="House Keeping"  <?php if($data[0]['skill_development_training_interest']=="House Keeping") echo 'selected';?>>House Keeping</option>
<option value="Massage Therapy"  <?php if($data[0]['skill_development_training_interest']=="Massage Therapy") echo 'selected';?>>Massage Therapy</option>
<option value="Others"  <?php if($data[0]['skill_development_training_interest']=="Others" || empty($data[0]['skill_development_training_interest'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['skill_development_training_interest_others']?>"  placeholder="Specify Others" class="form-control" name="skill_development_training_interest_others" id="skill_development_training_interest_others">
</div>
</div>




<div class="row">
<div class="col-sm-12">
  <label class="labelHeader">Present Skills</label>
<select class="form-control" name="present_skills">
<!-- <option value="">Status of Work</option> -->
<option value="Refrigeration and Airconditioning"  <?php if($data[0]['present_skills']=="Refrigeration and Airconditioning") echo 'selected';?>>Refrigeration and Airconditioning</option>
<option value="Automotive/Heavy Equipment Servicing"  <?php if($data[0]['present_skills']=="Automotive/Heavy Equipment Servicing") echo 'selected';?>>Automotive/Heavy Equipment Servicing</option>
<option value="Metal Worker"  <?php if($data[0]['present_skills']=="Metal Worker") echo 'selected';?>>Metal Worker</option>
<option value="Building Wiring Installation"  <?php if($data[0]['present_skills']=="Building Wiring Installation") echo 'selected';?>>Building Wiring Installation</option>
<option value="Heavy Equipment Operation"  <?php if($data[0]['present_skills']=="Heavy Equipment Operation") echo 'selected';?>>Heavy Equipment Operation</option>
<option value="Plumbing"  <?php if($data[0]['present_skills']=="Plumbing") echo 'selected';?>>Plumbing</option>
<option value="Welding"  <?php if($data[0]['present_skills']=="Welding") echo 'selected';?>>Welding</option>
<option value="Carpentry"  <?php if($data[0]['present_skills']=="Carpentry") echo 'selected';?>>Carpentry</option>
<option value="Baking"  <?php if($data[0]['present_skills']=="Baking") echo 'selected';?>>Baking</option>
<option value="Dressmaking"  <?php if($data[0]['present_skills']=="Dressmaking") echo 'selected';?>>Dressmaking</option>
<option value="Linguist"  <?php if($data[0]['present_skills']=="Linguist") echo 'selected';?>>Linguist</option>
<option value="Computer Graphics"  <?php if($data[0]['present_skills']=="Computer Graphics") echo 'selected';?>>Computer Graphics</option>
<option value="Painting"  <?php if($data[0]['present_skills']=="Painting") echo 'selected';?>>Painting</option>
<option value="Beauty Care"  <?php if($data[0]['present_skills']=="Beauty Care") echo 'selected';?>>Beauty Care</option>
<option value="Commercial Cooking"  <?php if($data[0]['present_skills']=="Commercial Cooking") echo 'selected';?>>Commercial Cooking</option>
<option value="House Keeping"  <?php if($data[0]['present_skills']=="House Keeping") echo 'selected';?>>House Keeping</option>
<option value="Massage Therapy"  <?php if($data[0]['present_skills']=="Massage Therapy") echo 'selected';?>>Massage Therapy</option>
<option value="Others"  <?php if($data[0]['present_skills']=="Others" || empty($data[0]['present_skills'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['present_skills_others']?>"  placeholder="Specify Others" class="form-control" name="present_skills_others" id="present_skills_others">
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
<input type="text"   value="<?=$data[0]['inc_expce_fare_home']?>"  placeholder="Fare from House to Office and back (in pesos)" class="form-control" name="FarefromHousetoOfficeandback" id="FarefromHousetoOfficeandback">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Monthly Salary or Income from Business (in pesos)</label>
<input type="text"   value="<?=$data[0]['inc_expcee_monthly_salary']?>"  placeholder="Monthly Salary or Income from Business (in pesos)" class="form-control" name="MonthlySalaryorIncomefromBusiness" id="MonthlySalaryorIncomefromBusiness">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Additional Monthly Income</label>
<input type="text"   value="<?=$data[0]['inc_expce_addition_monthly']?>"  placeholder="Additional Monthly Income" class="form-control" name="AdditionalMonthlyIncome" id="AdditionalMonthlyIncome">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Source of Income</label>
<select class="form-control" name="sourceOfIncome">
<!-- <option value="">Educational Attainment</option> -->
<option value="Employment"  <?php if($data[0]['sourceOfIncome']=="Employment") echo 'selected';?>>Employment</option>
<option value="Business"  <?php if($data[0]['sourceOfIncome']=="Business") echo 'selected';?>>Business</option>
<option value="Remittance"  <?php if($data[0]['sourceOfIncome']=="Remittance") echo 'selected';?>>Remittance</option>
<option value="Investment"  <?php if($data[0]['sourceOfIncome']=="Investment") echo 'selected';?>>Investment</option>
<option value="Others"  <?php if($data[0]['sourceOfIncome']=="Others" || empty($data[0]['sourceOfIncome'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Monthly Expenses</label>
<input type="text"   value="<?=$data[0]['inc_expce_mothly_expence']?>"  placeholder="Monthly Expenses" class="form-control" name="MonthlyExpenses" id="MonthlyExpenses">
</div>
</div>




<div class="row headerRow">
<div class="col-sm-12">
<label>Household Information of Head of the Family</label>
</div>
</div><div class="row">
<div class="col-sm-12">
<label class="labelHeader">Number of persons living with the family</label>
<input type="text"   value="<?=$data[0]['number_of_family']?>"  placeholder="Number of persons living with the family" class="form-control" name="numberofFamily" id="numberofFamily">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Water Service Information</label>
<select class="form-control" name="waterServiceInfo">
<!-- <option value="">Educational Attainment</option> -->
<option value="Maynilad"  <?php if($data[0]['waterServiceInfo']=="Maynilad") echo 'selected';?>>Maynilad</option>
<option value="Manila Water"  <?php if($data[0]['waterServiceInfo']=="Manila Water") echo 'selected';?>>Manila Water</option>
<option value="Deep Well"  <?php if($data[0]['waterServiceInfo']=="Deep Well") echo 'selected';?>>Deep Well</option>
<option value="Water Delivery"  <?php if($data[0]['waterServiceInfo']=="Water Delivery") echo 'selected';?>>Water Delivery</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Electricity  Service Information</label>
<select class="form-control" name="electicitiyServiceInfo">
<!-- <option value="">Educational Attainment</option> -->
<option value="Meralco"  <?php if($data[0]['electicitiyServiceInfo']=="Meralco") echo 'selected';?>>Meralco</option>
<option value="Sub-Meter"  <?php if($data[0]['electicitiyServiceInfo']=="Sub-Meter") echo 'selected';?>>Sub-Meter</option>
<option value="Solar Powered"  <?php if($data[0]['electicitiyServiceInfo']=="Solar Powered") echo 'selected';?>>Solar Powered</option>
</Select>
</div>
</div>

<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Toilet  Service Information</label>
<select class="form-control" name="toiletServiceInfo">
<option value="Septic Tank"  <?php if($data[0]['toiletServiceInfo']=="Septic Tank") echo 'selected';?>>Septic Tank</option>
<option value="No Septic Tank"  <?php if($data[0]['toiletServiceInfo']=="No Septic Tank") echo 'selected';?>>No Septic Tank</option>
<option value="Public Toilet"  <?php if($data[0]['toiletServiceInfo']=="Public Toilet") echo 'selected';?>>Public Toilet</option>
<option value="Others"  <?php if($data[0]['toiletServiceInfo']=="Others") echo 'selected';?>>Others</option>
</select>
</div>
</div>
 -->
<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">If Others, specify</label>
<input type="text"   value="<?=$data[0]['toilet_other']?>"  placeholder="If Others, specify" class="form-control" name="toilet_other" id="toilet_other">
</div>
</div> -->


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">If Renter, No. of Years Renting</label>
<input type="text"   value="<?=$data[0]['renter_year']?>"  placeholder="If Renter, No. of Years Renting" class="form-control" name="renter_year" id="renter_year">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label>Is the Property Owned (Y/N)</label><br>
<input type="radio"  value="yes"   <?php if($data[0]['isProperty_Owned']=="yes") echo 'checked';?>   name="isProperty_Owned" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['isProperty_Owned']=="no") echo 'checked';?>   name="isProperty_Owned" id="PagibigFundYes">  <label>No</label>

</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Type of Household</label>
<select class="form-control" name="TypeOfHousehold">
<option value="Household"  <?php if($data[0]['TypeOfHousehold']=="Household") echo 'selected';?>>Household</option>
<option value="Institutional Living Quarters"  <?php if($data[0]['TypeOfHousehold']=="Institutional Living Quarters") echo 'selected';?>>Institutional Living Quarters</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Housing Unit Occupancy Status</label>
<select class="form-control" name="HousingUnitOccupancyStatus">
<option value="Rent – Free without consent of owner"  <?php if($data[0]['HousingUnitOccupancyStatus']=="Rent – Free without consent of owner") echo 'selected';?>>Rent – Free without consent of owner</option>

<option value="Rent – Free with consent of owner"  <?php if($data[0]['HousingUnitOccupancyStatus']=="Rent – Free with consent of owner") echo 'selected';?>>Rent – Free with consent of owner</option>
<option value="Rented"  <?php if($data[0]['HousingUnitOccupancyStatus']=="Rented") echo 'selected';?>>Rented</option>
<option value="Owned with no obligation"  <?php if($data[0]['HousingUnitOccupancyStatus']=="Owned with no obligation") echo 'selected';?>>Owned with no obligation</option>
<option value="Owned being amortized"  <?php if($data[0]['HousingUnitOccupancyStatus']=="Owned being amortized") echo 'selected';?>>Owned being amortized</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Lot Occupancy Status</label>
<select class="form-control" name="LotOccupancyStatus">
<option value="Rent – Free without consent of owner"  <?php if($data[0]['LotOccupancyStatus']=="Rent – Free without consent of owner") echo 'selected';?>>Rent – Free without consent of owner</option>

<option value="Rent – Free with consent of owner"  <?php if($data[0]['LotOccupancyStatus']=="Rent – Free with consent of owner") echo 'selected';?>>Rent – Free with consent of owner</option>
<option value="Rented"  <?php if($data[0]['LotOccupancyStatus']=="Rented") echo 'selected';?>>Rented</option>
<option value="Owned with no obligation"  <?php if($data[0]['LotOccupancyStatus']=="Owned with no obligation") echo 'selected';?>>Owned with no obligation</option>
<option value="Owned being amortized"  <?php if($data[0]['LotOccupancyStatus']=="Owned being amortized") echo 'selected';?>>Owned being amortized</option>
</select>
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Household fuel for Lighting </label>
<select class="form-control" name="HouseholdFuelLighting">
<option value="None"  <?php if($data[0]['HouseholdFuelLighting']=="None") echo 'selected';?>>None</option>
<option value="Oil"  <?php if($data[0]['HouseholdFuelLighting']=="Oil") echo 'selected';?>>Oil</option>
<option value="LPG"  <?php if($data[0]['HouseholdFuelLighting']=="LPG") echo 'selected';?>>LPG</option>
<option value="Kerosene (Gas)"  <?php if($data[0]['HouseholdFuelLighting']=="Kerosene (Gas)") echo 'selected';?>>Kerosene (Gas)</option>
<option value="Electricity"  <?php if($data[0]['HouseholdFuelLighting']=="Electricity") echo 'selected';?>>Electricity</option>
<option value="Others"  <?php if($data[0]['HouseholdFuelLighting']=="Others" || empty($data[0]['HouseholdFuelLighting'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['HouseholdFuelLightingOthers']?>"  placeholder="Specify Others" class="form-control" name="HouseholdFuelLightingOthers" id="HouseholdFuelLightingOthers">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Household fuel for Cooking </label>
<select class="form-control" name="HouseholdFuelCooking">
<option value="None"  <?php if($data[0]['HouseholdFuelCooking']=="None") echo 'selected';?>>None</option>
<option value="Wood"  <?php if($data[0]['HouseholdFuelCooking']=="Wood") echo 'selected';?>>Wood</option>
<option value="Charcoal"  <?php if($data[0]['HouseholdFuelCooking']=="Charcoal") echo 'selected';?>>Charcoal</option>
<option value="LPG"  <?php if($data[0]['HouseholdFuelCooking']=="LPG") echo 'selected';?>>LPG</option>
<option value="Kerosene (Gas)"  <?php if($data[0]['HouseholdFuelCooking']=="Kerosene (Gas)") echo 'selected';?>>Kerosene (Gas)</option>
<option value="Electricity"  <?php if($data[0]['HouseholdFuelCooking']=="Electricity") echo 'selected';?>>Electricity</option>
<option value="Others"  <?php if($data[0]['HouseholdFuelCooking']=="Others" || empty($data[0]['HouseholdFuelCooking'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['HouseholdFuelCookingOthers']?>"  placeholder="Specify Others" class="form-control" name="HouseholdFuelCookingOthers" id="HouseholdFuelCookingOthers">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Source of Drinking Water </label>
<select class="form-control" name="SourceDrinkingWater">
<option value="Lake, River, Rain,  Others"  <?php if($data[0]['SourceDrinkingWater']=="Lake, River, Rain,  Others") echo 'selected';?>>Lake, River, Rain</option>
<option value="Dug Well"  <?php if($data[0]['SourceDrinkingWater']=="Dug Well") echo 'selected';?>>Dug Well</option>
<option value="Unprotected Spring"  <?php if($data[0]['SourceDrinkingWater']=="Unprotected Spring") echo 'selected';?>>Unprotected Spring</option>
<option value="Protected Spring"  <?php if($data[0]['SourceDrinkingWater']=="Protected Spring") echo 'selected';?>>Protected Spring</option>
<option value="Peddler"  <?php if($data[0]['SourceDrinkingWater']=="Peddler") echo 'selected';?>>Peddler</option>
<option value="Tubed/Piped shallow well"  <?php if($data[0]['SourceDrinkingWater']=="Tubed/Piped shallow well") echo 'selected';?>>Tubed/Piped shallow well</option>
<option value="Shared Tubed/Piped deep well"  <?php if($data[0]['SourceDrinkingWater']=="Shared Tubed/Piped deep well") echo 'selected';?>>Shared Tubed/Piped deep well</option>
<option value="Owned Tubed/Piped deep well"  <?php if($data[0]['SourceDrinkingWater']=="Owned Tubed/Piped deep well") echo 'selected';?>>Owned Tubed/Piped deep well</option>
<option value="Shared, Faucet community water system"  <?php if($data[0]['SourceDrinkingWater']=="Shared, Faucet community water system") echo 'selected';?>>Shared, Faucet community water system</option>
<option value="Owned, faucet community water system"  <?php if($data[0]['SourceDrinkingWater']=="Owned, faucet community water system") echo 'selected';?>>Owned, faucet community water system</option>
<option value="Bottled Water"  <?php if($data[0]['SourceDrinkingWater']=="Bottled Water") echo 'selected';?>>Bottled Water</option>
<option value="Others"  <?php if($data[0]['SourceDrinkingWater']=="Others" || empty($data[0]['SourceDrinkingWater'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['SourceDrinkingWaterOthers']?>"  placeholder="Specify Others" class="form-control" name="SourceDrinkingWaterOthers" id="SourceDrinkingWaterOthers">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Kitchen Garbage Disposal  </label>
<select class="form-control" name="KitchenGarbageDisposal">
<option value="Feeding to animals"  <?php if($data[0]['KitchenGarbageDisposal']=="Feeding to animals") echo 'selected';?>>Feeding to animals</option>
<option value="Burying"  <?php if($data[0]['KitchenGarbageDisposal']=="Burying") echo 'selected';?>>Burying</option>
<option value="Composting"  <?php if($data[0]['KitchenGarbageDisposal']=="Composting") echo 'selected';?>>Composting</option>
<option value="Burning"  <?php if($data[0]['KitchenGarbageDisposal']=="Burning") echo 'selected';?>>Burning</option>
<option value="Dumping individual pit"  <?php if($data[0]['KitchenGarbageDisposal']=="Dumping individual pit") echo 'selected';?>>Dumping individual pit</option>
<option value="Picked up by garbage truck"  <?php if($data[0]['KitchenGarbageDisposal']=="Picked up by garbage truck") echo 'selected';?>>Picked up by garbage truck</option>
<option value="Others"  <?php if($data[0]['KitchenGarbageDisposal']=="Others" || empty($data[0]['KitchenGarbageDisposal'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['KitchenGarbageDisposalOthers']?>"  placeholder="Specify Others" class="form-control" name="KitchenGarbageDisposalOthers" id="KitchenGarbageDisposalOthers">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Do you Segregate Garbage  </label>
<select class="form-control" name="IfSegregateGarbage">
<option value="yes"  <?php if($data[0]['IfSegregateGarbage']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['IfSegregateGarbage']=="no") echo 'selected';?>>No</option>
</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Toilet Facility</label>
<select class="form-control" name="ToiletFacility">
<option value="None"  <?php if($data[0]['ToiletFacility']=="None") echo 'selected';?>>None</option>
<option value="Open Pit"  <?php if($data[0]['ToiletFacility']=="Open Pit") echo 'selected';?>>Open Pit</option>
<option value="Close Pit"  <?php if($data[0]['ToiletFacility']=="Close Pit") echo 'selected';?>>Close Pit</option>
<option value="Water sealed, other depository, shared"  <?php if($data[0]['ToiletFacility']=="Water sealed, other depository, shared") echo 'selected';?>>Water sealed, other depository, shared</option>
<option value="Water sealed, other depository, owned"  <?php if($data[0]['ToiletFacility']=="Water sealed, other depository, owned") echo 'selected';?>>Water sealed, other depository, owned</option>
<option value="Water sealed, septic tank, shared"  <?php if($data[0]['ToiletFacility']=="Water sealed, septic tank, shared") echo 'selected';?>>Water sealed, septic tank, shared</option>
<option value="Water sealed, septic tank, exclusive"  <?php if($data[0]['ToiletFacility']=="Water sealed, septic tank, exclusive") echo 'selected';?>>Water sealed, septic tank, exclusive</option>
<option value="Others"  <?php if($data[0]['ToiletFacility']=="Others" || empty($data[0]['ToiletFacility'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['ToiletFacilityOthers']?>"  placeholder="Specify Others" class="form-control" name="ToiletFacilityOthers" id="ToiletFacilityOthers">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Type of Building/House</label>
<select class="form-control" name="BuildingHouseType">
<option value="Single House"  <?php if($data[0]['BuildingHouseType']=="Single House") echo 'selected';?>>Single House</option>
<option value="Duplex"  <?php if($data[0]['BuildingHouseType']=="Duplex") echo 'selected';?>>Duplex</option>
<option value="Multi-Unit Residential"  <?php if($data[0]['BuildingHouseType']=="Multi-Unit Residential") echo 'selected';?>>Multi-Unit Residential</option>
<option value="Institutional Living Quarters (Hospital/Hotel)"  <?php if($data[0]['BuildingHouseType']=="Institutional Living Quarters (Hospital/Hotel)") echo 'selected';?>>Institutional Living Quarters (Hospital/Hotel)</option>
<!-- <option value="Commercial/Industrial Residential"  <?php if($data[0]['BuildingHouseType']=="Commercial/Industrial Residential") echo 'selected';?>>Commercial/Industrial Residential</option> -->
<option value="Other Housing Unit (Boat, Cave, Others)"  <?php if($data[0]['BuildingHouseType']=="Other Housing Unit (Boat, Cave, Others)") echo 'selected';?>>Other Housing Unit (Boat, Cave, Others)</option>

</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['BuildingHouseTypeOthers']?>"  placeholder="Specify Others" class="form-control" name="BuildingHouseTypeOthers" id="BuildingHouseTypeOthers">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Toilet Facility</label>
<select class="form-control" name="ToiletFacility2">
<option value="No Walls"  <?php if($data[0]['ToiletFacility2']=="No Walls") echo 'selected';?>>No Walls</option>
<option value="Makeshift/salvaged/ improved Materials"  <?php if($data[0]['ToiletFacility2']=="Makeshift/salvaged/ improved Materials") echo 'selected';?>>Makeshift/salvaged/ improved Materials</option>
<option value="Glass"  <?php if($data[0]['ToiletFacility2']=="Glass") echo 'selected';?>>Glass</option>
<option value="Asbestos"  <?php if($data[0]['ToiletFacility2']=="Asbestos") echo 'selected';?>>Asbestos</option>
<option value="Bamboo/Sawali/ Cogon/Nipa"  <?php if($data[0]['ToiletFacility2']=="Bamboo/Sawali/ Cogon/Nipa") echo 'selected';?>>Bamboo/Sawali/ Cogon/Nipa</option>
<option value="Galvanized Iron/ Aluminum"  <?php if($data[0]['ToiletFacility2']=="Galvanized Iron/ Aluminum") echo 'selected';?>>Galvanized Iron/ Aluminum</option>
<option value="Half Concrete/Brick/ Stone/ and half wood."  <?php if($data[0]['ToiletFacility2']=="Half Concrete/Brick/ Stone/ and half wood.") echo 'selected';?>>Half Concrete/Brick/ Stone/ and half wood.</option>
<option value="Wood"  <?php if($data[0]['ToiletFacility2']=="Wood") echo 'Wood';?>>Wood</option>
<option value="Concrete/ Brick/ Stone"  <?php if($data[0]['ToiletFacility2']=="Concrete/ Brick/ Stone") echo 'Wood';?>>Concrete/ Brick/ Stone</option>
<option value="Others"  <?php if($data[0]['ToiletFacility2']=="Others" || empty($data[0]['ToiletFacility2'])) echo 'selected';?>>Others</option>
</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['ToiletFacilityOthers2']?>"  placeholder="Specify Others" class="form-control" name="ToiletFacilityOthers2" id="ToiletFacilityOthers2">
</div>
</div>



<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Construction Material Used for Walls</label>
<select class="form-control" name="construction_material_used_walls">
<option value="No Walls" <?php if($data[0]['construction_material_used_walls']=="No Walls") echo 'selected';?>>No Walls</option>
<option value="Makeshift/salvaged/improved materials" <?php if($data[0]['construction_material_used_walls']=="Makeshift/salvaged/improved materials") echo 'selected';?>>Makeshift/salvaged/improved materials</option>
<option value="Glass" <?php if($data[0]['construction_material_used_walls']=="Glass") echo 'selected';?>>Glass</option>
<option value="Asbestos" <?php if($data[0]['construction_material_used_walls']=="Asbestos") echo 'selected';?>>Asbestos</option>
<option value="Bamboo/Sawali/Cogon/Nipa" <?php if($data[0]['construction_material_used_walls']=="Bamboo/Sawali/Cogon/Nipa") echo 'selected';?>>Bamboo/Sawali/Cogon/Nipa</option>
<option value="Galvanized iron/aluminum" <?php if($data[0]['construction_material_used_walls']=="Galvanized iron/aluminum") echo 'selected';?>>Galvanized iron/aluminum</option>
<option value="Half concrete/brick/stone and half wood" <?php if($data[0]['construction_material_used_walls']=="Half concrete/brick/stone and half wood") echo 'selected';?>>Half concrete/brick/stone and half wood</option>
<option value="Wood" <?php if($data[0]['construction_material_used_walls']=="Wood") echo 'selected';?>>Wood</option>
<option value="Concrete/Brick/Stone" <?php if($data[0]['construction_material_used_walls']=="Concrete/Brick/Stone") echo 'selected';?>>Concrete/Brick/Stone</option>
<option value="Others" <?php if($data[0]['construction_material_used_walls']=="Others") echo 'selected';?>>Others</option>

</select>
</div>
</div>
  
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify, If others</label>
<input type="text"   value="<?=$data[0]['construction_material_used_walls_others']?>"  placeholder="Specify, If others" class="form-control" name="construction_material_used_walls_others" id="construction_material_used_walls_others">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Status of Residency</label>
<input type="text"   value="<?=$data[0]['status_of_residency']?>"  placeholder="Status of Residency" class="form-control" name="status_of_residency" id="status_of_residency">
</div>
</div> -->
<br>














<div class="row">
<div class="col-sm-12">
<label>Safety of Household</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>a. Prone to Flood</label><br>
<input type="radio"  value="yes"   <?php if($data[0]['prone_to_flood']=="yes") echo 'checked';?>   name="prone_to_flood" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['prone_to_flood']=="no") echo 'checked';?>   name="prone_to_flood" id="PagibigFundYes">  <label>No</label>
<br>
<label>b. Prone to Fire</label><br>
<input type="radio"  value="yes"   <?php if($data[0]['prone_to_fire']=="yes") echo 'checked';?>   name="prone_to_fire" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['prone_to_fire']=="no") echo 'checked';?>   name="prone_to_fire" id="PagibigFundYes">  <label>No</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Reasons of Calamity brought about by Human or Nature</label>
<input type="text"   value="<?=$data[0]['human_or_nature']?>"  placeholder="Reasons of Calamity brought about by Human or Nature " class="form-control" name="human_or_nature" id="human_or_nature">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Economic Reason for Living in the Place</label>
<select class="form-control" name="economic_reason_living">
<option value="Near the Workplace"  <?php if($data[0]['economic_reason_living']=="Near the Workplace") echo 'selected';?>>Near the Workplace</option>
<option value="Free Rent"  <?php if($data[0]['economic_reason_living']=="Free Rent") echo 'selected';?>>Free Rent</option>
<option value="Affordable Rent"  <?php if($data[0]['economic_reason_living']=="Affordable Rent") echo 'selected';?>>Affordable Rent</option>
<option value="Availability of Jobs"  <?php if($data[0]['economic_reason_living']=="Availability of Jobs") echo 'selected';?>>Availability of Jobs</option>
<option value="Higher Wage"  <?php if($data[0]['economic_reason_living']=="Higher Wage") echo 'selected';?>>Higher Wage</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Social Reason for Living in the Place</label>
<select class="form-control" name="social_reason_living">
<option value="Living with the Family"  <?php if($data[0]['social_reason_living']=="Living with the Family") echo 'selected';?>>Living with the Family</option>
<option value="Near the School"  <?php if($data[0]['social_reason_living']=="Near the School") echo 'selected';?>>Near the School</option>
<option value="Got Married in the Place"  <?php if($data[0]['social_reason_living']=="Got Married in the Place") echo 'selected';?>>Got Married in the Place</option>
<option value="Presence of Relatives and Friends"  <?php if($data[0]['social_reason_living']=="Presence of Relatives and Friends") echo 'selected';?>>Presence of Relatives and Friends</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Other Reason for Living in the Place</label>
<select class="form-control" name="other_reason_living">
<option value="Property Awarded"  <?php if($data[0]['other_reason_living']=="Property Awarded") echo 'selected';?>>Property Awarded</option>
<option value="Bought Rights to Property"  <?php if($data[0]['other_reason_living']=="Bought Rights to Property") echo 'selected';?>>Bought Rights to Property</option>
<option value="Emergency Reason (Demolition, Calamity, etc)"  <?php if($data[0]['other_reason_living']=="Emergency Reason (Demolition, Calamity, etc)") echo 'selected';?>>Emergency Reason (Demolition, Calamity, etc)</option>
<option value="Nowhere Else to Go"  <?php if($data[0]['other_reason_living']=="Nowhere Else to Go") echo 'selected';?>>Nowhere Else to Go</option>
</select>
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label>Years of Residency Information</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Type of Residence</label>
<select class="form-control" name="type_of_residence">
<option value="Non-Migrant"  <?php if($data[0]['type_of_residence']=="Non-Migrant") echo 'selected';?>>Non-migrant</option>
<option value="Migrant"  <?php if($data[0]['type_of_residence']=="Migrant") echo 'selected';?>>Migrant</option>
<option value="Transient"  <?php if($data[0]['type_of_residence']=="Transient") echo 'selected';?>>Transient</option>

</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Date of Transfer to Barangay</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Date of Transfer to Barangay (MM/DD/YY)"  value="<?=$data[0]['date_of_transfer_to_barangay']?>"   class="form-control datepicker" name="date_of_transfer_to_barangay" id="date_of_transfer_to_barangay">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label>a. Since Birth</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['residency_since_birth']=="yes") echo 'checked';?>   name="residency_since_birth" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['residency_since_birth']=="no") echo 'checked';?>   name="residency_since_birth" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. No. of Years</label>
<input type="text"   value="<?=$data[0]['residency_no_year']?>"  placeholder="No. of Years" class="form-control" name="residency_no_year" id="human_or_nature">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. No. of Months</label>
<input type="text"   value="<?=$data[0]['residency_no_month']?>"  placeholder="No. of Months" class="form-control" name="residency_no_month" id="residency_no_month">
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Previous Residency</label>
<select class="form-control" name="previous_residency">
<option value="Near the Barangay"  <?php if($data[0]['previous_residency']=="Near the Barangay") echo 'selected';?>>Near the Barangay</option>
<option value="From Previous City or Municipality"  <?php if($data[0]['previous_residency']=="From Previous City or Municipality") echo 'selected';?>>From Previous City or Municipality</option>
<option value="From Previous Province"  <?php if($data[0]['previous_residency']=="From Previous Province") echo 'selected';?>>From Previous Province</option>
<option value="Not Applicable"  <?php if($data[0]['previous_residency']=="Not Applicable" || empty($data[0]['previous_residency']=="Not Applicable")) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Previous City or Municipality</label>
<input type="text"   value="<?=$data[0]['pre_city_or_muni']?>"  placeholder="Specify Previous City or Municipality " class="form-control" name="pre_city_or_muni" id="pre_city_or_muni">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Previous Province</label>
<input type="text"   value="<?=$data[0]['pre_province']?>"  placeholder="Specify Previous Province" class="form-control" name="pre_province" id="pre_province">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Reason for Leaving the Place</label>


<!-- <select class="form-control" name="reason_for_leaving_place"> -->

<br><input type="checkbox" value="Lack of Employment" name="reason_for_leaving_place[]"  <?php if( strpos($data[0]['reason_for_leaving_place'], 'Lack of Employment') !== false ) echo 'checked';?>>&nbsp;Lack of Employment <br>
<input type="checkbox" value="Perception of Better Income in other Place" name="reason_for_leaving_place[]"   <?php if(strpos($data[0]['reason_for_leaving_place'] , "Perception of Better Income in other Place") !== false ) echo 'checked';?>>&nbsp;Perception of Better Income in other Place <br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Schooling"  <?php if(strpos($data[0]['reason_for_leaving_place'] ,"Schooling") !== false ) echo 'checked';?>>&nbsp;Schooling<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Presence of relatives and friends in other place"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Presence of relatives and friends in other place") !== false ) echo 'checked';?>>&nbsp;Presence of relatives and friends in other place<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Employment/Job Relocation"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Employment/Job Relocation") !== false ) echo 'checked';?>>&nbsp;Employment/Job Relocation<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Disaster Related Relocation"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Disaster Related Relocation") !== false ) echo 'checked';?>>&nbsp;Disaster Related Relocation<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Retirement"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Retirement") !== false ) echo 'checked';?>>&nbsp;Retirement<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="To live with Parents"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "To live with Parents") !== false ) echo 'checked';?>>&nbsp;To live with Parents<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="To live with children"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "To live with children") !== false ) echo 'checked';?>>&nbsp;To live with children<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Marriage"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Marriage") !== false ) echo 'checked';?>>&nbsp;Marriage<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Annulment/Divorce/Separation"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Annulment/Divorce/Separation") !== false ) echo 'checked';?>>&nbsp;Annulment/Divorce/Separation<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Community-related Reason"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Community-related Reason") !== false ) echo 'checked';?>>&nbsp;Community-related Reason<br>
<input type="checkbox" name="reason_for_leaving_place[]" value="Not Applicable"  <?php if(strpos($data[0]['reason_for_leaving_place'] , "Not Applicable") !== false ) echo 'checked';?>>&nbsp;Not Applicable<br>
<!-- </select> -->
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Plan to Return to Previous Barangay?</label>
<select class="form-control" name="plan_to_return_previous_barangay">
<option value="yes"  <?php if($data[0]['plan_to_return_previous_barangay']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['plan_to_return_previous_barangay']=="no") echo 'selected';?>>No</option>

</select>
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Plan to Return to Previous Barangay – When?</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Plan to Return to Previous Barangay – When? (MM/DD/YY)"  value="<?=$data[0]['plan_to_return_previous_barangay_when']?>"   class="form-control datepicker" name="plan_to_return_previous_barangay_when" id="plan_to_return_previous_barangay_when">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Intent/Duration of stay in Barangay</label>
<input type="text" onkeydown="return false"  style="background-color: white" placeholder="Intent/Duration of stay in Barangay (MM/DD/YY)"  value="<?=$data[0]['intent_duration_stay_barangay']?>"   class="form-control datepicker" name="intent_duration_stay_barangay" id="intent_duration_stay_barangay">
</div>
</div>



<div class="row">
<div class="col-sm-12">
<label class="labelHeader">No. of Times Flooded per Year</label>
<input type="text"   value="<?=$data[0]['no_times_flood_yr']?>"  placeholder="No. of Times Flooded per Year" class="form-control" name="no_times_flood_yr" id="no_times_flood_yr">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">No. of Times Prone to Fire per Year</label>
<input type="text"   value="<?=$data[0]['no_times_prn_fire_yr']?>"  placeholder="No. of Times Prone to Fire per Year" class="form-control" name="no_times_prn_fire_yr" id="no_times_prn_fire_yr">
</div>
</div>

<div class="row headerRow">
<div class="col-sm-12">
<label>Assets</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">a. House</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_house_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_house_quantity" id="assets_house_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_house_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_house_estimated" id="assets_house_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. Car</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_car_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_car_quantity" id="assets_car_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_car_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_car_estimated" id="assets_car_estimated">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. Jeep/Owner</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_jeep_owner_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_jeep_owner_quantity" id="assets_jeep_owner_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_jeep_owner_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_jeep_owner_estimated" id="assets_jeep_owner_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">d. Tricycle</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_tricycle_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_tricycle_quantity" id="assets_tricycle_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_tricycle_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_tricycle_estimated" id="assets_tricycle_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">e. Motorcycle</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_motorcycle_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_motorcycle_quantity" id="assets_motorcycle_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_quantity_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_quantity_estimated" id="assets_quantity_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">f. Computer</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_computer_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_computer_quantity" id="assets_computer_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_computer_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_computer_estimated" id="assets_computer_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">g. TV</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_tv_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_tv_quantity" id="assets_tv_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_tv_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_tv_estimated" id="assets_tv_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">h. VCR/VCD</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_vcrvcd_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_vcrvcd_quantity" id="assets_vcrvcd_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_vcrvcd_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_vcrvcd_estimated" id="assets_vcrvcd_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Hi-Fi Components</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_hifi_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_hifi_quantity" id="assets_hifi_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_hifi_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_hifi_estimated" id="assets_hifi_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">j. Refrigerator</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_refrigerator_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_refrigerator_quantity" id="assets_refrigerator_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_refrigerator_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_refrigerator_estimated" id="assets_refrigerator_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">k. Oven/Range</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_oven_range_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_oven_range_quantity" id="assets_oven_range_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_oven_range_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_oven_range_estimated" id="assets_oven_range_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">l. Others</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Quantity</label>
<input type="number"   value="<?=$data[0]['assets_other_quantity']?>"  placeholder="Quantity" class="form-control" name="assets_other_quantity" id="assets_other_quantity">
<br><label class="labelHeader">ii. Estimated Value</label>
<input type="text"   value="<?=$data[0]['assets_other_estimated']?>"  placeholder="Estimated Value" class="form-control" name="assets_other_estimated" id="assets_other_estimated">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Others</label>
<input type="text"   value="<?=$data[0]['assets_specify_other']?>"  placeholder="Specify Others" class="form-control" name="assets_specify_other" id="assets_specify_other">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Total Assets</label>
<input type="text"   value="<?=$data[0]['assets_total']?>"  placeholder="Total Assets" class="form-control" name="assets_total" id="assets_total">
</div>
</div>

<div class="row headerRow">
<div class="col-sm-12">
<label>Monthly Expenses</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">a. Food</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_food']?>"  placeholder="Food" class="form-control" name="monthly_expenses_food" id="monthly_expenses_food">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. Electricity Consumption</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_electricity']?>"  placeholder="Electricity Consumption" class="form-control" name="monthly_expenses_electricity" id="monthly_expenses_electricity">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. Water Consumption</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_water']?>"  placeholder="Water Consumption" class="form-control" name="monthly_expenses_water" id="monthly_expenses_water">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">d. Education</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_education']?>"  placeholder="Water Consumption" class="form-control" name="monthly_expenses_education" id="monthly_expenses_education">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">e. Communication (Telephone/Mobile)</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_communication']?>" maxlength="15"  placeholder="Communication (Telephone/Mobile" class="form-control" name="monthly_expenses_communication" id="monthly_expenses_communication">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">f. House Rental</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_house_rent']?>"  placeholder="House Rental" class="form-control" name="monthly_expenses_house_rent" id="monthly_expenses_house_rent">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">g. Lot/Land Rental</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_land_rent']?>"  placeholder="Lot/Land Rental" class="form-control" name="monthly_expenses_land_rent" id="monthly_expenses_land_rent">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">h. Others</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_other']?>"  placeholder="Others" class="form-control" name="monthly_expenses_other" id="monthly_expenses_other">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">i. Tax</label>
<input type="text"   value="<?=$data[0]['monthly_expenses_tax']?>"  placeholder="Tax" class="form-control" name="monthly_expenses_tax" id="monthly_expenses_tax">
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Total Expenditures per Month</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<input type="text"   value="<?=$data[0]['monthly_total_expenditure']?>"  placeholder="Total Expenditures per Month" class="form-control" name="monthly_total_expenditure" id="monthly_total_expenditure">
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label>Health Problems for the last 2 years</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>a. Pneumonia/Fever</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['health_problem_last_two_year_fever']=="yes") echo 'checked';?>   name="health_problem_last_two_year_fever" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['health_problem_last_two_year_fever']=="no") echo 'checked';?>   name="health_problem_last_two_year_fever" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>b. Colds</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['health_problem_last_two_year_colds']=="yes") echo 'checked';?>   name="health_problem_last_two_year_colds" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['health_problem_last_two_year_colds']=="no") echo 'checked';?>   name="health_problem_last_two_year_colds" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>c. Recurring Fever </label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['health_problem_last_two_year_recurring_fever']=="yes") echo 'checked';?>   name="health_problem_last_two_year_recurring_fever" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['health_problem_last_two_year_recurring_fever']=="no") echo 'checked';?>   name="health_problem_last_two_year_recurring_fever" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>d. Skin Disease/Infection </label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['health_problem_last_two_year_skin_infection']=="yes") echo 'checked';?>   name="health_problem_last_two_year_skin_infection" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['health_problem_last_two_year_skin_infection']=="no") echo 'checked';?>   name="health_problem_last_two_year_skin_infection" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>e. Typhoid Fever</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['health_problem_last_two_year_typhoid']=="yes") echo 'checked';?>   name="health_problem_last_two_year_typhoid" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['health_problem_last_two_year_typhoid']=="no") echo 'checked';?>   name="health_problem_last_two_year_typhoid" id="no">  <label>No</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Other Health Problems</label>
<input type="text"   value="<?=$data[0]['health_problem_last_two_year_other']?>"  placeholder="Specify Other Health Problems" class="form-control" name="health_problem_last_two_year_other" id="health_problem_last_two_year_other">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Biggest Community Problem</label>
<select class="form-control" name="biggest_community_problem">
<option value="Health"  <?php if($data[0]['biggest_community_problem']=="Health") echo 'selected';?>>Health</option>
<option value="Cleanliness"  <?php if($data[0]['biggest_community_problem']=="Cleanliness") echo 'selected';?>>Cleanliness</option>
<option value="Peace and Order"  <?php if($data[0]['biggest_community_problem']=="Peace and Order") echo 'selected';?>>Peace and Order</option>
<option value="Safety"  <?php if($data[0]['biggest_community_problem']=="Safety") echo 'selected';?>>Safety</option>
<option value="Employment"  <?php if($data[0]['biggest_community_problem']=="Employment") echo 'selected';?>>Employment</option>
<option value="Poor Government Service"  <?php if($data[0]['biggest_community_problem']=="Poor Government Service") echo 'selected';?>>Poor Government Service</option>
<option value="Flood"  <?php if($data[0]['biggest_community_problem']=="Flood") echo 'selected';?>>Flood</option>
<option value="Garbage"  <?php if($data[0]['biggest_community_problem']=="Garbage") echo 'selected';?>>Garbage</option>
</select>
</div>
</div>

<div class="row headerRow">
<div class="col-sm-12">
<label>Homeowners Association or Neighborhood Association</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">a. Name of Association</label>
<input type="text"   value="<?=$data[0]['homeower_association_neighbour_name']?>"  placeholder="Name of Association" class="form-control" name="homeower_association_neighbour_name" id="homeower_association_neighbour_name">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">b. Position in Association</label>
<select class="form-control" name="homeower_association_neighbour_position">
<option value="Officer"  <?php if($data[0]['homeower_association_neighbour_position']=="Officer") echo 'selected';?>>Officer</option>
<option value="Member"  <?php if($data[0]['homeower_association_neighbour_position']=="Member") echo 'selected';?>>Member</option>
</select>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">c. Nature of Association</label>
<select class="form-control" name="homeower_association_neighbour_nature">
<option value="HOA"  <?php if($data[0]['homeower_association_neighbour_nature']=="HOA") echo 'selected';?>>HOA</option>
<option value="Neighborhood"  <?php if($data[0]['homeower_association_neighbour_nature']=="Neighborhood") echo 'selected';?>>Neighborhood</option>
<option value="Sectoral"  <?php if($data[0]['homeower_association_neighbour_nature']=="Sectoral") echo 'selected';?>>Sectoral</option>
<option value="Alliance"  <?php if($data[0]['homeower_association_neighbour_nature']=="Alliance") echo 'selected';?>>Alliance</option>
<option value="Coalision"  <?php if($data[0]['homeower_association_neighbour_nature']=="Coalision") echo 'selected';?>>Coalision</option>
</select>
</div>
</div>

<br>
<div class="row">
<div class="col-sm-12">
<label>Accreditation with Legal Entity</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>a. SEC</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['accreditation_with_legal_sec']=="yes") echo 'checked';?>   name="accreditation_with_legal_sec" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['accreditation_with_legal_sec']=="no") echo 'checked';?>   name="accreditation_with_legal_sec" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>b. DTI</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['accreditation_with_legal_dti']=="yes") echo 'checked';?>   name="accreditation_with_legal_dti" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['accreditation_with_legal_dti']=="no") echo 'checked';?>   name="accreditation_with_legal_dti" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>c. HLURB</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['accreditation_with_legal_hlurb']=="yes") echo 'checked';?>   name="accreditation_with_legal_hlurb" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['accreditation_with_legal_hlurb']=="no") echo 'checked';?>   name="accreditation_with_legal_hlurb" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>d. COA</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['accreditation_with_legal_coa']=="yes") echo 'checked';?>   name="accreditation_with_legal_coa" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['accreditation_with_legal_coa']=="no") echo 'checked';?>   name="accreditation_with_legal_coa" id="no">  <label>No</label>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<label>e. DSWD</label>
<br>
<input type="radio"  value="yes"   <?php if($data[0]['accreditation_with_legal_dswd']=="yes") echo 'checked';?>   name="accreditation_with_legal_dswd" id="yes">  <label>Yes</label>
<input type="radio"  value="no"   <?php if($data[0]['accreditation_with_legal_dswd']=="no") echo 'checked';?>   name="accreditation_with_legal_dswd" id="no">  <label>No</label>
</div>
</div>

<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Specify Other Government Agency</label>
<input type="text"   value="<?=$data[0]['government_agency_other']?>"  placeholder="Specify Other Government Agency" class="form-control" name="government_agency_other" id="government_agency_other">
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">HH Female died (12 months)</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Age</label>  
<input type="number" maxlength="2" max = '100'  value="<?=$data[0]['hh_female_died_age']?>"  placeholder="Age" class="form-control" name="hh_female_died_age" id="hh_female_died_age">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Cause of Death</label>  
<input type="text"   value="<?=$data[0]['hh_female_died_cause']?>"  placeholder="Cause of Death" class="form-control" name="hh_female_died_cause" id="hh_female_died_cause">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Registered?</label>  
<select class="form-control" name="is_hh_female_died_registered">
<option value="yes"  <?php if($data[0]['is_hh_female_died_registered']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['is_hh_female_died_registered']=="no") echo 'selected';?>>No</option>
<option value="not applicable"  <?php if($data[0]['is_hh_female_died_registered']=="not applicable" || empty($data[0]['is_hh_female_died_registered']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Where</label>  
<input type="text"   value="<?=$data[0]['hh_female_died_where']?>"  placeholder="Where" class="form-control" name="hh_female_died_where" id="hh_female_died_where">
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">HH Child Below 5 years Died (12 months)</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Age</label>  
<input type="number" maxlength="2" max = '100'  value="<?=$data[0]['hh_child_died_age']?>"  placeholder="Age" class="form-control" name="hh_child_died_age" id="hh_child_died_age">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Cause of Death</label>  
<input type="text"   value="<?=$data[0]['hh_child_died_cause']?>"  placeholder="Cause of Death" class="form-control" name="hh_child_died_cause" id="hh_child_died_cause">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Registered?</label>  
<select class="form-control" name="is_hh_child_died_registered">
<option value="yes"  <?php if($data[0]['is_hh_child_died_registered']=="yes") echo 'selected';?>>Yes</option>
<option value="no"  <?php if($data[0]['is_hh_child_died_registered']=="no") echo 'selected';?>>No</option>
<option value="not applicable"  <?php if($data[0]['is_hh_child_died_registered']=="not applicable" || empty($data[0]['is_hh_child_died_registered']) ) echo 'selected';?>>Not Applicable</option>
</select>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Where</label>  
<input type="text"   value="<?=$data[0]['hh_child_died_where']?>"  placeholder="Where" class="form-control" name="hh_child_died_where" id="hh_child_died_where">
</div>
</div>

<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Cause of Death / Common Disease</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Disease 1</label>  
<input type="text"   value="<?=$data[0]['common_disease_1']?>"  placeholder="Disease 1" class="form-control" name="common_disease_1" id="common_disease_1">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Disease 2</label>  
<input type="text"   value="<?=$data[0]['common_disease_2']?>"  placeholder="Disease 2" class="form-control" name="common_disease_2" id="common_disease_2">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Disease 3</label>  
<input type="text"   value="<?=$data[0]['common_disease_3']?>"  placeholder="Disease 3" class="form-control" name="common_disease_3" id="common_disease_3">
</div>
</div>


<br>
<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Barangay's Common Needs</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Need 1</label>  
<input type="text"   value="<?=$data[0]['barangay_common_need_1']?>"  placeholder="Need 1" class="form-control" name="barangay_common_need_1" id="barangay_common_need_1">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Need 2</label>  
<input type="text"   value="<?=$data[0]['barangay_common_need_2']?>"  placeholder="Need 2" class="form-control" name="barangay_common_need_2" id="barangay_common_need_2">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Need 3</label>  
<input type="text"   value="<?=$data[0]['barangay_common_need_3']?>"  placeholder="Need 3" class="form-control" name="barangay_common_need_3" id="barangay_common_need_3">
</div>
</div>




<br>
<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Where to Stay 5 year from now</label>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Barangay</label>  
<input type="text"   value="<?=$data[0]['where_stay_5_yrs_barangay']?>"  placeholder="Barangay" class="form-control" name="where_stay_5_yrs_barangay" id="where_stay_5_yrs_barangay">
</div>
</div>


<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Municipal/City</label>  
<input type="text"   value="<?=$data[0]['where_stay_5_yrs_barangay_municipal']?>"  placeholder="Municipal/City" class="form-control" name="where_stay_5_yrs_barangay_municipal" id="where_stay_5_yrs_barangay_municipal">
</div>
</div>

<div class="row">
<div class="col-sm-12">
<label class="labelHeader">Province</label>  
<input type="text"   value="<?=$data[0]['where_stay_5_yrs_barangay_province']?>"  placeholder="Province" class="form-control" name="where_stay_5_yrs_barangay_province" id="where_stay_5_yrs_barangay_province">
</div>
</div>
<br> -->

<!-- <div class="row">
<div class="col-sm-12">
<label class="labelHeader">Name of the Interviewer</label>  
<input type="text"   value="<?=$data[0]['name_of_interviewer']?>"  placeholder="Name of the Interviewer" class="form-control" name="name_of_interviewer" id="name_of_interviewer">
</div>
</div>
<br> -->