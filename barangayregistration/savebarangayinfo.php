<?php

include_once('../common.php');
if(isset($_SESSION['sess_iUserId'])){
	$userId=$_SESSION['sess_iUserId'];
}
else{
	$userId=isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '';
}
if(isset($_POST['submit']))
{
  
	$id= isset($_REQUEST['id']) ? $_REQUEST['id'] : ''; 
  $area=$_REQUEST['area'];
  $tag_number=$_REQUEST['tag_number'];
$sur_name=$_POST['Surname'];
$name=$_POST['Name'];
 $middle_name=$_POST['MiddleInitial'];
  $province=$_POST['Province'];
   $city=$_POST['City'];
    $barangay=$_POST['Barangay'];
    $street=$_POST['Street'];
    $house_unit_number=$_POST['HouseUnitNumber'];
    $gen_barangay_ID=$_POST['GenerateBarangayIDNumberhead'];
    $date_of_birth=$_POST['DateofBirth'];
    $civil_status=$_POST['CivilStatus'];
     $sex=$_POST['Sex'];
      $landline=$_POST['Landline'];
      $mobile=$_POST['Mobile'];
      $email=$_POST['EmailAddress'];
      $status_residency=$_POST['StatusofResidency'];
       $special_disability_or=$_POST['SpecialDisabilityorSeniorCitizen'];
        $religion=$_POST['Religion'];
        $LGBTI=$_POST['LGBTI'];
         $work_status=$_POST['StatusofWork'];
         $occupation=$_POST['Occupation'];
         $if_student=$_POST['IfStudent'];
         $work_nature=$_POST['NatureofWork'];
         $profession_name=$_POST['ProfessionName'];
         $profession_industry=$_POST['ProfessionIndustry'];
         $work_sector=$_POST['WorkingSector'];
         $helth_benefit=$_POST['HealthBenefits'];
         $helth_benefit_other=$_POST['OtherHealthBenefits'];
          $membership_or_affiliation=$_POST['Membership'];
           $membership_or_affiliation_other=$_POST['OtherMembership'];
            $skill_certification_name=$_POST['NameofCertification'];
            $acquired=$_POST['WhereAcquired'];
             $private_institute=$_POST['private_institute'];
            $skill_name=$_POST['NameofSkills'];
             $inc_expce_fare_home=$_POST['FarefromHousetoOfficeandback'];
             $inc_expcee_monthly_salary=$_POST['MonthlySalaryorIncomefromBusiness'];
              $inc_expce_addition_monthly=$_POST['AdditionalMonthlyIncome'];
              $inc_expce_mothly_expence=$_POST['MonthlyExpenses'];  
			  $number_of_family     			= isset($_POST['numberofFamily']) ? $_POST['numberofFamily'] : "";
$waterServiceInfo   			= isset($_POST['waterServiceInfo']) ? $_POST['waterServiceInfo'] : "";
$electicitiyServiceInfo   		= isset($_POST['electicitiyServiceInfo']) ? $_POST['electicitiyServiceInfo'] : "";
$toiletServiceInfo        		= isset($_POST['toiletServiceInfo']) ? $_POST['toiletServiceInfo'] : "";
$toilet_other             		= isset($_POST['toilet_other']) ? $_POST['toilet_other'] : "";
$renter_year              		= isset($_POST['renter_year']) ? $_POST['renter_year'] : "";
$isProperty_Owned         		= isset($_POST['isProperty_Owned']) ? $_POST['isProperty_Owned'] : "";
$status_of_residency         	= isset($_POST['status_of_residency']) ? $_POST['status_of_residency'] : "";
$prone_to_flood         		= isset($_POST['prone_to_flood']) ? $_POST['prone_to_flood'] : "";
$prone_to_fire         		= isset($_POST['prone_to_fire']) ? $_POST['prone_to_fire'] : "";
$human_or_nature        		= isset($_POST['human_or_nature']) ? $_POST['human_or_nature'] : "";
$economic_reason_living        	= isset($_POST['economic_reason_living']) ? $_POST['economic_reason_living'] : "";
$social_reason_living        	= isset($_POST['social_reason_living']) ? $_POST['social_reason_living'] : "";
$other_reason_living        	= isset($_POST['other_reason_living']) ? $_POST['other_reason_living'] : "";
$yrs_residency_info        		= isset($_POST['yrs_residency_info']) ? $_POST['yrs_residency_info'] : "";
$residency_since_birth        	= isset($_POST['residency_since_birth']) ? $_POST['residency_since_birth'] : "";
$residency_no_year        		= isset($_POST['residency_no_year']) ? $_POST['residency_no_year'] : "";
$residency_no_month        		= isset($_POST['residency_no_month']) ? $_POST['residency_no_month'] : "";
$previous_residency        		= isset($_POST['previous_residency']) ? $_POST['previous_residency'] : "";
$pre_city_or_muni        		= isset($_POST['pre_city_or_muni']) ? $_POST['pre_city_or_muni'] : "";
$pre_province        			= isset($_POST['pre_province']) ? $_POST['pre_province'] : "";
$no_times_flood_yr        		= isset($_POST['no_times_flood_yr']) ? $_POST['no_times_flood_yr'] : "";
$no_times_prn_fire_yr        	= isset($_POST['no_times_prn_fire_yr']) ? $_POST['no_times_prn_fire_yr'] : "";
$assets_house_quantity        	= isset($_POST['assets_house_quantity']) ? $_POST['assets_house_quantity'] : "";
$assets_house_estimated        	= isset($_POST['assets_house_estimated']) ? $_POST['assets_house_estimated'] : "";
$assets_tricycle_quantity       = isset($_POST['assets_tricycle_quantity']) ? $_POST['assets_tricycle_quantity'] : "";
$assets_tricycle_estimated      = isset($_POST['assets_tricycle_estimated']) ? $_POST['assets_tricycle_estimated'] : "";
$assets_car_quantity        	= isset($_POST['assets_car_quantity']) ? $_POST['assets_car_quantity'] : "";
$assets_car_estimated        	= isset($_POST['assets_car_estimated']) ? $_POST['assets_car_estimated'] : "";
$assets_jeep_owner_quantity     = isset($_POST['assets_jeep_owner_quantity']) ? $_POST['assets_jeep_owner_quantity'] : "";
$assets_jeep_owner_estimated    = isset($_POST['assets_jeep_owner_estimated']) ? $_POST['assets_jeep_owner_estimated'] : "";
$assets_motorcycle_quantity     = isset($_POST['assets_motorcycle_quantity']) ? $_POST['assets_motorcycle_quantity'] : "";
$assets_quantity_estimated      = isset($_POST['assets_quantity_estimated']) ? $_POST['assets_quantity_estimated'] : "";
$assets_computer_quantity       = isset($_POST['assets_computer_quantity']) ? $_POST['assets_computer_quantity'] : "";
$assets_computer_estimated      = isset($_POST['assets_computer_estimated']) ? $_POST['assets_computer_estimated'] : "";
$assets_tv_quantity        	    = isset($_POST['assets_tv_quantity']) ? $_POST['assets_tv_quantity'] : "";
$assets_tv_estimated        	= isset($_POST['assets_tv_estimated']) ? $_POST['assets_tv_estimated'] : "";
$assets_vcrvcd_quantity        	= isset($_POST['assets_vcrvcd_quantity']) ? $_POST['assets_vcrvcd_quantity'] : "";
$assets_vcrvcd_estimated        = isset($_POST['assets_vcrvcd_estimated']) ? $_POST['assets_vcrvcd_estimated'] : "";
$assets_hifi_quantity        	= isset($_POST['assets_hifi_quantity']) ? $_POST['assets_hifi_quantity'] : "";
$assets_hifi_estimated        	= isset($_POST['assets_hifi_estimated']) ? $_POST['assets_hifi_estimated'] : "";

$assets_refrigerator_quantity   = isset($_POST['assets_refrigerator_quantity']) ? $_POST['assets_refrigerator_quantity'] : "";

$assets_refrigerator_estimated  = isset($_POST['assets_refrigerator_estimated']) ? $_POST['assets_refrigerator_estimated'] : "";

$assets_oven_range_quantity     = isset($_POST['assets_oven_range_quantity']) ? $_POST['assets_oven_range_quantity'] : "";

$assets_oven_range_estimated    = isset($_POST['assets_oven_range_estimated']) ? $_POST['assets_oven_range_estimated'] : "";

$assets_other_quantity        	= isset($_POST['assets_other_quantity']) ? $_POST['assets_other_quantity'] : "";
$assets_other_estimated        	= isset($_POST['assets_other_estimated']) ? $_POST['assets_other_estimated'] : "";
$assets_specify_other        	= isset($_POST['assets_specify_other']) ? $_POST['assets_specify_other'] : "";
$assets_total        			= isset($_POST['assets_total']) ? $_POST['assets_total'] : "";
$monthly_expenses_food        	= isset($_POST['monthly_expenses_food']) ? $_POST['monthly_expenses_food'] : "";
$monthly_expenses_electricity   = isset($_POST['monthly_expenses_electricity']) ? $_POST['monthly_expenses_electricity'] : "";

$monthly_expenses_water        	= isset($_POST['monthly_expenses_water']) ? $_POST['monthly_expenses_water'] : "";
$monthly_expenses_education        	= isset($_POST['monthly_expenses_education']) ? $_POST['monthly_expenses_education'] : "";
$monthly_expenses_communication = isset($_POST['monthly_expenses_communication']) ? $_POST['monthly_expenses_communication'] : "";

$monthly_expenses_house_rent    = isset($_POST['monthly_expenses_house_rent']) ? $_POST['monthly_expenses_house_rent'] : "";

$monthly_expenses_land_rent     = isset($_POST['monthly_expenses_land_rent']) ? $_POST['monthly_expenses_land_rent'] : "";
$monthly_expenses_other         = isset($_POST['monthly_expenses_other']) ? $_POST['monthly_expenses_other'] : "";
$monthly_expenses_tax           = isset($_POST['monthly_expenses_tax']) ? $_POST['monthly_expenses_tax'] : "";
$monthly_total_expenditure      = isset($_POST['monthly_total_expenditure']) ? $_POST['monthly_total_expenditure'] : "";

$health_problem_last_two_year_fever    	=		isset($_POST['health_problem_last_two_year_fever']) ? $_POST['health_problem_last_two_year_fever'] : "";

$health_problem_last_two_year_colds    		= 	isset($_POST['health_problem_last_two_year_colds']) ? $_POST['health_problem_last_two_year_colds'] : "";

$health_problem_last_two_year_recurring_fever  =	isset($_POST['health_problem_last_two_year_recurring_fever']) ? $_POST['health_problem_last_two_year_recurring_fever'] : "";

$health_problem_last_two_year_skin_infection   =	isset($_POST['health_problem_last_two_year_skin_infection']) ? $_POST['health_problem_last_two_year_skin_infection'] : "";

$health_problem_last_two_year_typhoid  = isset($_POST['health_problem_last_two_year_typhoid']) ? $_POST['health_problem_last_two_year_typhoid'] : "";

$health_problem_last_two_year_other  =  isset($_POST['health_problem_last_two_year_other']) ? $_POST['health_problem_last_two_year_other'] : "";

$biggest_community_problem    = isset($_POST['biggest_community_problem']) ? $_POST['biggest_community_problem'] : "";

$homeower_association_neighbour_name = isset($_POST['homeower_association_neighbour_name']) ? $_POST['homeower_association_neighbour_name'] : "";

$homeower_association_neighbour_position = isset($_POST['homeower_association_neighbour_position']) ? $_POST['homeower_association_neighbour_position'] : "";

$homeower_association_neighbour_nature = isset($_POST['homeower_association_neighbour_nature']) ? $_POST['homeower_association_neighbour_nature'] : "";



$accreditation_with_legal_sec = isset($_POST['accreditation_with_legal_sec']) ? $_POST['accreditation_with_legal_sec'] : "";

$accreditation_with_legal_dti = isset($_POST['accreditation_with_legal_dti']) ? $_POST['accreditation_with_legal_dti'] : "";

$accreditation_with_legal_hlurb   = isset($_POST['accreditation_with_legal_hlurb']) ? $_POST['accreditation_with_legal_hlurb'] : "";

$accreditation_with_legal_coa   = isset($_POST['accreditation_with_legal_coa']) ? $_POST['accreditation_with_legal_coa'] : "";

$accreditation_with_legal_dswd  = isset($_POST['accreditation_with_legal_dswd']) ? $_POST['accreditation_with_legal_dswd'] : "";

$government_agency_other        =isset($_POST['government_agency_other']) ? $_POST['government_agency_other'] : "";

$date = date('Y-m-d H:i:s');

$educational_attainment=$_POST['EducationalAttainment'];

$place_of_school_barangay_name = isset($_POST['place_of_school_barangay_name']) ? $_POST['place_of_school_barangay_name'] : '';
$place_of_school_city = isset($_POST['place_of_school_city']) ? $_POST['place_of_school_city'] : '';
$place_of_school_province = isset($_POST['place_of_school_province']) ? $_POST['place_of_school_province'] : '';

$place_of_birth = isset($_POST['place_of_birth']) ? $_POST['place_of_birth'] : '';
$citizenship = isset($_POST['citizenship']) ? $_POST['citizenship'] : '';
$nationality_if_non_filipino = isset($_POST['nationality_if_non_filipino']) ? $_POST['nationality_if_non_filipino'] : '';
$ethnicity = isset($_POST['ethnicity']) ? $_POST['ethnicity'] : '';
$specify_disability = isset($_POST['specify_disability']) ? $_POST['specify_disability'] : '';
$senior_citizen = isset($_POST['senior_citizen']) ? $_POST['senior_citizen'] : '';
$solo_parent  = isset($_POST['solo_parent']) ? $_POST['solo_parent'] : '';
$place_of_work_barangay_name = isset($_POST['place_of_work_barangay_name']) ? $_POST['place_of_work_barangay_name'] : '';
$place_of_work_city_municipality = isset($_POST['place_of_work_city_municipality']) ? $_POST['place_of_work_city_municipality'] : '';
$place_of_work_province = isset($_POST['place_of_work_province']) ? $_POST['place_of_work_province'] : '';
$is_student_enrolled = isset($_POST['is_student_enrolled']) ? $_POST['is_student_enrolled'] : '';
$have_a_valid_ctc = isset($_POST['have_a_valid_ctc']) ? $_POST['have_a_valid_ctc'] : '';
$ctc_issued_barangay = isset($_POST['ctc_issued_barangay']) ? $_POST['ctc_issued_barangay'] : '';
$place_of_delivery = isset($_POST['place_of_delivery']) ? $_POST['place_of_delivery'] : '';
$place_of_delivery_specify_other = isset($_POST['place_of_delivery_specify_other']) ? $_POST['place_of_delivery_specify_other'] : '';
$person_assisted_delivery = isset($_POST['person_assisted_delivery']) ? $_POST['person_assisted_delivery'] : '';
$person_assisted_delivery_specify_other = isset($_POST['person_assisted_delivery_specify_other']) ? $_POST['person_assisted_delivery_specify_other'] : '';
$last_vaccine_received = isset($_POST['last_vaccine_received']) ? $_POST['last_vaccine_received'] : '';
$no_of_pregnancies_for_mothers = isset($_POST['no_of_pregnancies_for_mothers']) ? $_POST['no_of_pregnancies_for_mothers'] : '';
$no_of_children_living_for_mothers = isset($_POST['no_of_children_living_for_mothers']) ? $_POST['no_of_children_living_for_mothers'] : '';
$family_planning_method = isset($_POST['family_planning_method']) ? $_POST['family_planning_method'] : '';
$source_family_planning_method = isset($_POST['source_family_planning_method']) ? $_POST['source_family_planning_method'] : '';
$source_family_planning_method_others = isset($_POST['source_family_planning_method_others']) ? $_POST['source_family_planning_method_others'] : '';
$intension_use_family_planning = isset($_POST['intension_use_family_planning']) ? $_POST['intension_use_family_planning'] : '';
$intension_use_family_planning_reasons = isset($_POST['intension_use_family_planning_reasons']) ? $_POST['intension_use_family_planning_reasons'] : '';
$philhealth_status = isset($_POST['philhealth_status']) ? $_POST['philhealth_status'] : '';
$health_facility_visited_12_months = isset($_POST['health_facility_visited_12_months']) ? $_POST['health_facility_visited_12_months'] : '';
$health_facility_visited_12_months_others = isset($_POST['health_facility_visited_12_months_others']) ? $_POST['health_facility_visited_12_months_others'] : '';
$reason_visiting_health_facility = isset($_POST['reason_visiting_health_facility']) ? $_POST['reason_visiting_health_facility'] : '';
$reason_visiting_health_facility_others = isset($_POST['reason_visiting_health_facility_others']) ? $_POST['reason_visiting_health_facility_others'] : '';
$skill_development_training_interest = isset($_POST['skill_development_training_interest']) ? $_POST['skill_development_training_interest'] : '';
$skill_development_training_interest_others = isset($_POST['skill_development_training_interest_others']) ? $_POST['skill_development_training_interest_others'] : '';
$present_skills = isset($_POST['present_skills']) ? $_POST['present_skills'] : '';
$present_skills_others = isset($_POST['present_skills_others']) ? $_POST['present_skills_others'] : '';
$sourceOfIncome = isset($_POST['sourceOfIncome']) ? $_POST['sourceOfIncome'] : '';
$TypeOfHousehold = isset($_POST['TypeOfHousehold']) ? $_POST['TypeOfHousehold'] : '';
$HousingUnitOccupancyStatus = isset($_POST['HousingUnitOccupancyStatus']) ? $_POST['HousingUnitOccupancyStatus'] : '';
$LotOccupancyStatus = isset($_POST['LotOccupancyStatus']) ? $_POST['LotOccupancyStatus'] : '';
$HouseholdFuelLighting = isset($_POST['HouseholdFuelLighting']) ? $_POST['HouseholdFuelLighting'] : '';
$HouseholdFuelLightingOthers = isset($_POST['HouseholdFuelLightingOthers']) ? $_POST['HouseholdFuelLightingOthers'] : '';
$HouseholdFuelCooking = isset($_POST['HouseholdFuelCooking']) ? $_POST['HouseholdFuelCooking'] : '';
$HouseholdFuelCookingOthers = isset($_POST['HouseholdFuelCookingOthers']) ? $_POST['HouseholdFuelCookingOthers'] : '';
$SourceDrinkingWater = isset($_POST['SourceDrinkingWater']) ? $_POST['SourceDrinkingWater'] : '';
$SourceDrinkingWaterOthers = isset($_POST['SourceDrinkingWaterOthers']) ? $_POST['SourceDrinkingWaterOthers'] : '';
$KitchenGarbageDisposal = isset($_POST['KitchenGarbageDisposal']) ? $_POST['KitchenGarbageDisposal'] : '';
$KitchenGarbageDisposalOthers = isset($_POST['KitchenGarbageDisposalOthers']) ? $_POST['KitchenGarbageDisposalOthers'] : '';
$IfSegregateGarbage = isset($_POST['IfSegregateGarbage']) ? $_POST['IfSegregateGarbage'] : '';

$ToiletFacility = isset($_POST['ToiletFacility']) ? $_POST['ToiletFacility'] : '';
$ToiletFacilityOthers = isset($_POST['ToiletFacilityOthers']) ? $_POST['ToiletFacilityOthers'] : '';

$BuildingHouseType = isset($_POST['BuildingHouseType']) ? $_POST['BuildingHouseType'] : '';
$BuildingHouseTypeOthers = isset($_POST['BuildingHouseTypeOthers']) ? $_POST['BuildingHouseTypeOthers'] : '';

$construction_material_used_walls = isset($_POST['construction_material_used_walls']) ? $_POST['construction_material_used_walls'] : '';
$construction_material_used_walls_others = isset($_POST['construction_material_used_walls_others']) ? $_POST['construction_material_used_walls_others'] : '';

$ToiletFacility2 = isset($_POST['ToiletFacility2']) ? $_POST['ToiletFacility2'] : '';
$ToiletFacilityOthers2 = isset($_POST['ToiletFacilityOthers2']) ? $_POST['ToiletFacilityOthers2'] : '';

$type_of_residence = isset($_POST['type_of_residence']) ? $_POST['type_of_residence'] : '';
$date_of_transfer_to_barangay = isset($_POST['date_of_transfer_to_barangay']) ? $_POST['date_of_transfer_to_barangay'] : '';
$reason_for_leaving_place = isset($_POST['reason_for_leaving_place']) ? implode(",",$_POST['reason_for_leaving_place']) : '';




$plan_to_return_previous_barangay = isset($_POST['plan_to_return_previous_barangay']) ? $_POST['plan_to_return_previous_barangay'] : '';
$plan_to_return_previous_barangay_when = isset($_POST['plan_to_return_previous_barangay_when']) ? $_POST['plan_to_return_previous_barangay_when'] : '';
$intent_duration_stay_barangay = isset($_POST['intent_duration_stay_barangay']) ? $_POST['intent_duration_stay_barangay'] : '';
$hh_female_died_age = isset($_POST['hh_female_died_age']) ? $_POST['hh_female_died_age'] : '';
$hh_female_died_cause = isset($_POST['hh_female_died_cause']) ? $_POST['hh_female_died_cause'] : '';
$is_hh_female_died_registered = isset($_POST['is_hh_female_died_registered']) ? $_POST['is_hh_female_died_registered'] : '';
$hh_female_died_where = isset($_POST['hh_female_died_where']) ? $_POST['hh_female_died_where'] : '';
$hh_child_died_age = isset($_POST['hh_child_died_age']) ? $_POST['hh_child_died_age'] : '';
$hh_child_died_cause = isset($_POST['hh_child_died_cause']) ? $_POST['hh_child_died_cause'] : '';
$is_hh_child_died_registered = isset($_POST['is_hh_child_died_registered']) ? $_POST['is_hh_child_died_registered'] : '';
$hh_child_died_where = isset($_POST['hh_child_died_where']) ? $_POST['hh_child_died_where'] : '';
$common_disease_1 = isset($_POST['common_disease_1']) ? $_POST['common_disease_1'] : '';
$common_disease_2 = isset($_POST['common_disease_2']) ? $_POST['common_disease_2'] : '';
$common_disease_3 = isset($_POST['common_disease_3']) ? $_POST['common_disease_3'] : '';
$barangay_common_need_1 = isset($_POST['barangay_common_need_1']) ? $_POST['barangay_common_need_1'] : '';
$barangay_common_need_2 = isset($_POST['barangay_common_need_2']) ? $_POST['barangay_common_need_2'] : '';
$barangay_common_need_3 = isset($_POST['barangay_common_need_3']) ? $_POST['barangay_common_need_3'] : '';

$where_stay_5_yrs_barangay = isset($_POST['where_stay_5_yrs_barangay']) ? $_POST['where_stay_5_yrs_barangay'] : '';
$where_stay_5_yrs_barangay_municipal = isset($_POST['where_stay_5_yrs_barangay_municipal']) ? $_POST['where_stay_5_yrs_barangay_municipal'] : '';
$where_stay_5_yrs_barangay_province = isset($_POST['where_stay_5_yrs_barangay_province']) ? $_POST['where_stay_5_yrs_barangay_province'] : '';

$name_of_interviewer = isset($_POST['name_of_interviewer']) ? $_POST['name_of_interviewer'] : '';











if($id!='')
{
$query="update `barangay_inhabitants_head` 
set `sur_name`='$sur_name',
 `name`='$name',
 `middle_name`='$middle_name',
 `province`='$province',
 `city`='$city',
 `barangay`='$barangay',
 `street`='$street',
 `house_unit_number`= '$house_unit_number',
 `gen_barangay_ID`='$gen_barangay_ID',
 `date_of_birth`='$date_of_birth',
 `civil_status`='$civil_status',
 `sex`='$sex',
 `landline`='$landline', 
 `mobile`='$mobile', 
 `email`='$email',
 `status_residency`='$status_residency',
 `special_disability_or`='$special_disability_or',
 `religion`='$religion', 
 `LGBTI`='$LGBTI',
 `work_status`='$work_status',
 `occupation`='$occupation',
 `if_student`='$if_student',
 `work_nature`='$work_nature',
 `profession_name`='$profession_name',
 `profession_industry`='$profession_industry',
 `work_sector`='$work_sector',
 `helth_benefit`='$helth_benefit',
 `helth_benefit_other`='$helth_benefit_other',
 `membership_or_affiliation`='$membership_or_affiliation',
 `membership_or_affiliation_other`='$membership_or_affiliation_other',
 `skill_certification_name`='$skill_certification_name',
 `acquired`='$acquired',
 `private_institute`='$private_institute',
 `skill_name`= '$skill_name', 
 `inc_expce_fare_home`='$inc_expce_fare_home',
 `inc_expcee_monthly_salary`= '$inc_expcee_monthly_salary',
 `inc_expce_addition_monthly`='$inc_expce_addition_monthly',
 `inc_expce_mothly_expence`='$inc_expce_mothly_expence',
 `area`='$area',
 `tag_number`='$tag_number',
 educational_attainment='$educational_attainment',
 `place_of_school_barangay_name`= '$place_of_school_barangay_name',
 `place_of_school_city`= '$place_of_school_city',
 `place_of_school_province`= '$place_of_school_province',
`number_of_family` = '$number_of_family',    						
`waterServiceInfo` = '$waterServiceInfo',   							
`electicitiyServiceInfo` = '$electicitiyServiceInfo',   					
`toiletServiceInfo` = '$toiletServiceInfo',       					
`toilet_other` = '$toilet_other',             					
`renter_year` = '$renter_year',              					
`isProperty_Owned` = '$isProperty_Owned',         					
`status_of_residency` = '$status_of_residency',         					
`prone_to_flood` = '$prone_to_flood',         						
`prone_to_fire` = '$prone_to_fire',         						
`human_or_nature` = '$human_or_nature',        						
`economic_reason_living` = '$economic_reason_living',        				
`social_reason_living` = '$social_reason_living',        				
`other_reason_living` = '$other_reason_living',        					
`yrs_residency_info` = '$yrs_residency_info',        					
`residency_since_birth` = '$residency_since_birth',        				
`residency_no_year` = '$residency_no_year',        					
`residency_no_month` = '$residency_no_month',        					
`previous_residency` = '$previous_residency',        					
`pre_city_or_muni` = '$pre_city_or_muni',        					
`pre_province` = '$pre_province',        						
`no_times_flood_yr` = '$no_times_flood_yr',        					
`no_times_prn_fire_yr` = '$no_times_prn_fire_yr',        				
`assets_house_quantity` = '$assets_house_quantity',        				
`assets_house_estimated` = '$assets_house_estimated',        				
`assets_tricycle_quantity` = '$assets_tricycle_quantity',        			
`assets_tricycle_estimated` = '$assets_tricycle_estimated',        			
`assets_car_quantity` = '$assets_car_quantity',        					
`assets_car_estimated` = '$assets_car_estimated',        				
`assets_jeep_owner_quantity` = '$assets_jeep_owner_quantity',        			
`assets_jeep_owner_estimated` = '$assets_jeep_owner_estimated',        			
`assets_motorcycle_quantity` = '$assets_motorcycle_quantity',        			
`assets_quantity_estimated` = '$assets_quantity_estimated',        			
`assets_computer_quantity` = '$assets_computer_quantity',        			
`assets_computer_estimated` = '$assets_computer_estimated',        			
`assets_tv_quantity` = '$assets_tv_quantity',        					
`assets_tv_estimated` = '$assets_tv_estimated',        					
`assets_vcrvcd_quantity` = '$assets_vcrvcd_quantity',        				
`assets_vcrvcd_estimated` = '$assets_vcrvcd_estimated',        				
`assets_hifi_quantity` = '$assets_hifi_quantity',        				
`assets_hifi_estimated` = '$assets_hifi_estimated',        				
`assets_refrigerator_quantity` = '$assets_refrigerator_quantity',        		
`assets_refrigerator_estimated` = '$assets_refrigerator_estimated',        		
`assets_oven_range_quantity` = '$assets_oven_range_quantity',        			
`assets_oven_range_estimated` = '$assets_oven_range_estimated',        			
`assets_other_quantity` = '$assets_other_quantity',        				
`assets_other_estimated` = '$assets_other_estimated',       				
`assets_specify_other` = '$assets_specify_other',        				
`assets_total` = '$assets_total',        						
`monthly_expenses_food` = '$monthly_expenses_food',        				
`monthly_expenses_electricity` = '$monthly_expenses_electricity',        		
`monthly_expenses_water` = '$monthly_expenses_water',        				
`monthly_expenses_education` = '$monthly_expenses_education',        				
`monthly_expenses_communication` = '$monthly_expenses_communication',        		
`monthly_expenses_house_rent` = '$monthly_expenses_house_rent',        			
`monthly_expenses_land_rent` = '$monthly_expenses_land_rent',            		
`monthly_expenses_other` = '$monthly_expenses_other',               		
`monthly_expenses_tax` = '$monthly_expenses_tax',                  		
`monthly_total_expenditure` = '$monthly_total_expenditure',        	  		
`health_problem_last_two_year_fever` = '$health_problem_last_two_year_fever',    		
`health_problem_last_two_year_colds` = '$health_problem_last_two_year_colds',    		
`health_problem_last_two_year_recurring_fever` = '$health_problem_last_two_year_recurring_fever',  
`health_problem_last_two_year_skin_infection` = '$health_problem_last_two_year_skin_infection',   
`health_problem_last_two_year_typhoid` = '$health_problem_last_two_year_typhoid',          
`health_problem_last_two_year_other` = '$health_problem_last_two_year_other',            
`biggest_community_problem` = '$biggest_community_problem',      				
`homeower_association_neighbour_name` = '$homeower_association_neighbour_name',    		
`homeower_association_neighbour_position` = '$homeower_association_neighbour_position',    	
`homeower_association_neighbour_nature` = '$homeower_association_neighbour_nature',      	
`accreditation_with_legal_sec` = '$accreditation_with_legal_sec',      			
`accreditation_with_legal_dti` = '$accreditation_with_legal_dti',       			
`accreditation_with_legal_hlurb` = '$accreditation_with_legal_hlurb',     			
`accreditation_with_legal_coa` = '$accreditation_with_legal_coa',       			
`accreditation_with_legal_dswd` = '$accreditation_with_legal_dswd',      			
`government_agency_other` = '$government_agency_other',

`place_of_birth` = '$place_of_birth', 
`citizenship` = '$citizenship',
`nationality_if_non_filipino` = '$nationality_if_non_filipino', 
`ethnicity` = '$ethnicity',
`specify_disability` = '$specify_disability',
`senior_citizen` = '$senior_citizen',
`solo_parent` = '$solo_parent', 
`place_of_work_barangay_name` = '$place_of_work_barangay_name',
`place_of_work_city_municipality` = '$place_of_work_city_municipality',
`place_of_work_province` = '$place_of_work_province',
`is_student_enrolled` = '$is_student_enrolled',
`have_a_valid_ctc` = '$have_a_valid_ctc',
`ctc_issued_barangay` = '$ctc_issued_barangay',
`place_of_delivery` = '$place_of_delivery',
`place_of_delivery_specify_other` = '$place_of_delivery_specify_other',
`person_assisted_delivery` = '$person_assisted_delivery',
`person_assisted_delivery_specify_other` = '$person_assisted_delivery_specify_other',
`last_vaccine_received` = '$last_vaccine_received',
`no_of_pregnancies_for_mothers` = '$no_of_pregnancies_for_mothers',
`no_of_children_living_for_mothers` = '$no_of_children_living_for_mothers',
`family_planning_method` = '$family_planning_method',
`source_family_planning_method` = '$source_family_planning_method',
`source_family_planning_method_others` = '$source_family_planning_method_others',
`intension_use_family_planning` = '$intension_use_family_planning',
`intension_use_family_planning_reasons` = '$intension_use_family_planning_reasons',
`philhealth_status` = '$philhealth_status',
`health_facility_visited_12_months` = '$health_facility_visited_12_months',
`health_facility_visited_12_months_others` = '$health_facility_visited_12_months_others',
`reason_visiting_health_facility` = '$reason_visiting_health_facility',
`reason_visiting_health_facility_others` = '$reason_visiting_health_facility_others',
`skill_development_training_interest` = '$skill_development_training_interest',
`skill_development_training_interest_others` = '$skill_development_training_interest_others',
`present_skills` = '$present_skills',
`present_skills_others` = '$present_skills_others',
`sourceOfIncome` = '$sourceOfIncome',
`TypeOfHousehold` = '$TypeOfHousehold',
`HousingUnitOccupancyStatus` = '$HousingUnitOccupancyStatus',
`LotOccupancyStatus` = '$LotOccupancyStatus',
`HouseholdFuelLighting` = '$HouseholdFuelLighting',
`HouseholdFuelLightingOthers` = '$HouseholdFuelLightingOthers',
`HouseholdFuelCooking` = '$HouseholdFuelCooking',
`HouseholdFuelCookingOthers` = '$HouseholdFuelCookingOthers',
`SourceDrinkingWater` = '$SourceDrinkingWater',
`SourceDrinkingWaterOthers` = '$SourceDrinkingWaterOthers',
`KitchenGarbageDisposal` = '$KitchenGarbageDisposal',
`KitchenGarbageDisposalOthers` = '$KitchenGarbageDisposalOthers',
`IfSegregateGarbage` = '$IfSegregateGarbage',
`BuildingHouseType` = '$BuildingHouseType',
`BuildingHouseTypeOthers` = '$BuildingHouseTypeOthers',

`ToiletFacility` = '$ToiletFacility',
`ToiletFacilityOthers` = '$ToiletFacilityOthers',

`construction_material_used_walls` = '$construction_material_used_walls',
`construction_material_used_walls_others` = '$construction_material_used_walls_others',
`ToiletFacility2` = '$ToiletFacility2',
`ToiletFacilityOthers2` = '$ToiletFacilityOthers2',
`type_of_residence` = '$type_of_residence',
`date_of_transfer_to_barangay` = '$date_of_transfer_to_barangay',
`reason_for_leaving_place` = '$reason_for_leaving_place',
`plan_to_return_previous_barangay` = '$plan_to_return_previous_barangay',
`plan_to_return_previous_barangay_when` = '$plan_to_return_previous_barangay_when',
`intent_duration_stay_barangay` = '$intent_duration_stay_barangay',
`hh_female_died_age` = '$hh_female_died_age',
`hh_female_died_cause` = '$hh_female_died_cause',
`is_hh_female_died_registered` = '$is_hh_female_died_registered',
`hh_female_died_where` = '$hh_female_died_where',
`hh_child_died_age` = '$hh_child_died_age',
`hh_child_died_cause` = '$hh_child_died_cause',
`is_hh_child_died_registered` = '$is_hh_child_died_registered',
`hh_child_died_where` = '$hh_child_died_where',
`common_disease_1` = '$common_disease_1',
`common_disease_2` = '$common_disease_2',
`common_disease_3` = '$common_disease_3',
`barangay_common_need_1` = '$barangay_common_need_1',
`barangay_common_need_2` = '$barangay_common_need_2',
`barangay_common_need_3` = '$barangay_common_need_3',

`where_stay_5_yrs_barangay` = '$where_stay_5_yrs_barangay',
`where_stay_5_yrs_barangay_municipal` = '$where_stay_5_yrs_barangay_municipal',
`where_stay_5_yrs_barangay_province` = '$where_stay_5_yrs_barangay_province',
`name_of_interviewer` = '$name_of_interviewer',


 update_dt='$date'
 where `user_id` ='$userId'";
}else
{

$query="INSERT INTO `barangay_inhabitants_head`(
`sur_name`, `name`, `middle_name`, `province`, `city`, `barangay`, `street`, `house_unit_number`, `gen_barangay_ID`, `date_of_birth`, `civil_status`, `sex`, `landline`, `mobile`, `email`, `status_residency`, `special_disability_or`, `religion`, `LGBTI`, `work_status`, `occupation`, `if_student`, `work_nature`, `profession_name`, `profession_industry`, `work_sector`, `helth_benefit`, `helth_benefit_other`, `membership_or_affiliation`, `membership_or_affiliation_other`, `skill_certification_name`, `acquired`, `private_institute`, `skill_name`, `inc_expce_fare_home`, `inc_expcee_monthly_salary`, `inc_expce_addition_monthly`, `inc_expce_mothly_expence`, `user_id`,`area`,`tag_number`,
`number_of_family`,    						
`waterServiceInfo`,   							
`electicitiyServiceInfo`,   					
`toiletServiceInfo`,       					
`toilet_other`,             					
`renter_year`,              					
`isProperty_Owned`,         					
`status_of_residency`,         					
`prone_to_flood`,         						
`prone_to_fire`,         						
`human_or_nature`,        						
`economic_reason_living`,        				
`social_reason_living`,        				
`other_reason_living`,        					
`yrs_residency_info`,        					
`residency_since_birth`,        				
`residency_no_year`,        					
`residency_no_month`,        					
`previous_residency`,        					
`pre_city_or_muni`,        					
`pre_province`,        						
`no_times_flood_yr`,        					
`no_times_prn_fire_yr`,        				
`assets_house_quantity`,        				
`assets_house_estimated`,        				
`assets_tricycle_quantity`,        			
`assets_tricycle_estimated`,        			
`assets_car_quantity`,        					
`assets_car_estimated`,        				
`assets_jeep_owner_quantity`,        			
`assets_jeep_owner_estimated`,        			
`assets_motorcycle_quantity`,        			
`assets_quantity_estimated`,        			
`assets_computer_quantity`,        			
`assets_computer_estimated`,        			
`assets_tv_quantity`,        					
`assets_tv_estimated`,        					
`assets_vcrvcd_quantity`,        				
`assets_vcrvcd_estimated`,        				
`assets_hifi_quantity`,        				
`assets_hifi_estimated`,        				
`assets_refrigerator_quantity`,        		
`assets_refrigerator_estimated`,        		
`assets_oven_range_quantity`,        			
`assets_oven_range_estimated`,        			
`assets_other_quantity`,        				
`assets_other_estimated`,       				
`assets_specify_other`,        				
`assets_total`,        						
`monthly_expenses_food`,        				
`monthly_expenses_electricity`,        		
`monthly_expenses_water`,        				
`monthly_expenses_education`,        				
`monthly_expenses_communication`,        		
`monthly_expenses_house_rent`,        			
`monthly_expenses_land_rent`,            		
`monthly_expenses_other`,               		
`monthly_expenses_tax`,                  		
`monthly_total_expenditure`,        	  		
`health_problem_last_two_year_fever`,    		
`health_problem_last_two_year_colds`,    		
`health_problem_last_two_year_recurring_fever`,  
`health_problem_last_two_year_skin_infection`,   
`health_problem_last_two_year_typhoid`,          
`health_problem_last_two_year_other`,            
`biggest_community_problem`,      				
`homeower_association_neighbour_name`,    		
`homeower_association_neighbour_position`,    	
`homeower_association_neighbour_nature`,      	
`accreditation_with_legal_sec`,      			
`accreditation_with_legal_dti`,       			
`accreditation_with_legal_hlurb`,     			
`accreditation_with_legal_coa`,       			
`accreditation_with_legal_dswd`,      			
`government_agency_other`,
educational_attainment,
`place_of_school_barangay_name`,
`place_of_school_city`,
`place_of_school_province`,
created_dt,
`place_of_birth`,
`citizenship`,
`nationality_if_non_filipino`, 
`ethnicity`,
`specify_disability`,
`senior_citizen`,
`solo_parent`, 
`place_of_work_barangay_name`,
`place_of_work_city_municipality`,
`place_of_work_province`,
`is_student_enrolled`,
`have_a_valid_ctc`,
`ctc_issued_barangay`,
`place_of_delivery`,
`place_of_delivery_specify_other`,
`person_assisted_delivery`,
`person_assisted_delivery_specify_other`,
`last_vaccine_received`,
`no_of_pregnancies_for_mothers`,
`no_of_children_living_for_mothers`,
`family_planning_method`,
`source_family_planning_method`,
`source_family_planning_method_others`,
`intension_use_family_planning`,
`intension_use_family_planning_reasons`,
`philhealth_status`,
`health_facility_visited_12_months`,
`health_facility_visited_12_months_others`,
`reason_visiting_health_facility`,
`reason_visiting_health_facility_others`,
`skill_development_training_interest`,
`skill_development_training_interest_others`,
`present_skills`,
`present_skills_others`,
`sourceOfIncome`,
`TypeOfHousehold`,
`HousingUnitOccupancyStatus`,
`LotOccupancyStatus`,
`HouseholdFuelLighting`,
`HouseholdFuelLightingOthers`,
`HouseholdFuelCooking`,
`HouseholdFuelCookingOthers`,
`SourceDrinkingWater`,
`SourceDrinkingWaterOthers`,
`KitchenGarbageDisposal`,
`KitchenGarbageDisposalOthers`,
`IfSegregateGarbage`,
`ToiletFacility`,
`ToiletFacilityOthers`,
`BuildingHouseType`,
`BuildingHouseTypeOthers`,
`construction_material_used_walls`,
`construction_material_used_walls_others`,
`ToiletFacility2`,
`ToiletFacilityOthers2`,
`type_of_residence`,
`date_of_transfer_to_barangay`,
`reason_for_leaving_place`,
`plan_to_return_previous_barangay`,
`plan_to_return_previous_barangay_when`,
`intent_duration_stay_barangay`,
`hh_female_died_age`,
`hh_female_died_cause`,
`is_hh_female_died_registered`,
`hh_female_died_where`,
`hh_child_died_age`,
`hh_child_died_cause`,
`is_hh_child_died_registered`,
`hh_child_died_where`,
`common_disease_1`,
`common_disease_2`,
`common_disease_3`,
`barangay_common_need_1`,
`barangay_common_need_2`,
`barangay_common_need_3`,

`where_stay_5_yrs_barangay`,
`where_stay_5_yrs_barangay_municipal`,
`where_stay_5_yrs_barangay_province`,
`name_of_interviewer`

) VALUES ('$sur_name', '$name', '$middle_name', '$province', '$city', '$barangay', '$street', '$house_unit_number', '$gen_barangay_ID', '$date_of_birth', '$civil_status', '$sex', '$landline', '$mobile', '$email', '$status_residency', '$special_disability_or', '$religion', '$LGBTI', '$work_status', '$occupation', '$if_student', '$work_nature', '$profession_name', '$profession_industry', '$work_sector', '$helth_benefit', '$helth_benefit_other', '$membership_or_affiliation', '$membership_or_affiliation_other', '$skill_certification_name', '$acquired', '$private_institute', '$skill_name', '$inc_expce_fare_home', '$inc_expcee_monthly_salary', '$inc_expce_addition_monthly', '$inc_expce_mothly_expence', '$userId','$area','$tag_number',
'$number_of_family',    						
'$waterServiceInfo',   							
'$electicitiyServiceInfo',   					
'$toiletServiceInfo',       					
'$toilet_other',             					
'$renter_year',              					
'$isProperty_Owned',         					
'$status_of_residency',         					
'$prone_to_flood',         						
'$prone_to_fire',         						
'$human_or_nature',        						
'$economic_reason_living',        				
'$social_reason_living',        				
'$other_reason_living',        					
'$yrs_residency_info',        					
'$residency_since_birth',        				
'$residency_no_year',        					
'$residency_no_month',        					
'$previous_residency',        					
'$pre_city_or_muni',        					
'$pre_province',        						
'$no_times_flood_yr',        					
'$no_times_prn_fire_yr',        				
'$assets_house_quantity',        				
'$assets_house_estimated',        				
'$assets_tricycle_quantity',        			
'$assets_tricycle_estimated',        			
'$assets_car_quantity',        					
'$assets_car_estimated',        				
'$assets_jeep_owner_quantity',        			
'$assets_jeep_owner_estimated',        			
'$assets_motorcycle_quantity',        			
'$assets_quantity_estimated',        			
'$assets_computer_quantity',        			
'$assets_computer_estimated',        			
'$assets_tv_quantity',        					
'$assets_tv_estimated',        					
'$assets_vcrvcd_quantity',        				
'$assets_vcrvcd_estimated',        				
'$assets_hifi_quantity',        				
'$assets_hifi_estimated',        				
'$assets_refrigerator_quantity',        		
'$assets_refrigerator_estimated',        		
'$assets_oven_range_quantity',        			
'$assets_oven_range_estimated',        			
'$assets_other_quantity',        				
'$assets_other_estimated',       				
'$assets_specify_other',        				
'$assets_total',        						
'$monthly_expenses_food',        				
'$monthly_expenses_electricity',        		
'$monthly_expenses_water',        				
'$monthly_expenses_education',        				
'$monthly_expenses_communication',        		
'$monthly_expenses_house_rent',        			
'$monthly_expenses_land_rent',            		
'$monthly_expenses_other',               		
'$monthly_expenses_tax',                  		
'$monthly_total_expenditure',        	  		
'$health_problem_last_two_year_fever',    		
'$health_problem_last_two_year_colds',    		
'$health_problem_last_two_year_recurring_fever',  
'$health_problem_last_two_year_skin_infection',   
'$health_problem_last_two_year_typhoid',          
'$health_problem_last_two_year_other',            
'$biggest_community_problem',      				
'$homeower_association_neighbour_name',    		
'$homeower_association_neighbour_position',    	
'$homeower_association_neighbour_nature',      	
'$accreditation_with_legal_sec',      			
'$accreditation_with_legal_dti',       			
'$accreditation_with_legal_hlurb',     			
'$accreditation_with_legal_coa',       			
'$accreditation_with_legal_dswd',      			
'$government_agency_other',
'$educational_attainment',
'$place_of_school_barangay_name',
'$place_of_school_city',
'$place_of_school_province',
'$date',
'$place_of_birth',
'$citizenship',
'$nationality_if_non_filipino', 
'$ethnicity',
'$specify_disability',
'$senior_citizen',
'$solo_parent', 
'$place_of_work_barangay_name',
'$place_of_work_city_municipality',
'$place_of_work_province',
'$is_student_enrolled',
'$have_a_valid_ctc',
'$ctc_issued_barangay',
'$place_of_delivery',
'$place_of_delivery_specify_other',
'$person_assisted_delivery',
'$person_assisted_delivery_specify_other',
'$last_vaccine_received',
'$no_of_pregnancies_for_mothers',
'$no_of_children_living_for_mothers',
'$family_planning_method',
'$source_family_planning_method',
'$source_family_planning_method_others',
'$intension_use_family_planning',
'$intension_use_family_planning_reasons',
'$philhealth_status',
'$health_facility_visited_12_months',
'$health_facility_visited_12_months_others',
'$reason_visiting_health_facility',
'$reason_visiting_health_facility_others',
'$skill_development_training_interest',
'$skill_development_training_interest_others',
'$present_skills',
'$present_skills_others',
'$sourceOfIncome',
'$TypeOfHousehold',
'$HousingUnitOccupancyStatus',
'$LotOccupancyStatus',
'$HouseholdFuelLighting',
'$HouseholdFuelLightingOthers',
'$HouseholdFuelCooking',
'$HouseholdFuelCookingOthers',
'$SourceDrinkingWater',
'$SourceDrinkingWaterOthers',
'$KitchenGarbageDisposal',
'$KitchenGarbageDisposalOthers',
'$IfSegregateGarbage',
'$ToiletFacility',
'$ToiletFacilityOthers',
'$BuildingHouseType',
'$BuildingHouseTypeOthers',
'$construction_material_used_walls',
'$construction_material_used_walls_others',
'$ToiletFacility2',
'$ToiletFacilityOthers2',
'$type_of_residence',
'$date_of_transfer_to_barangay',
'$reason_for_leaving_place',
'$plan_to_return_previous_barangay',
'$plan_to_return_previous_barangay_when',
'$intent_duration_stay_barangay',
'$hh_female_died_age',
'$hh_female_died_cause',
'$is_hh_female_died_registered',
'$hh_female_died_where',
'$hh_child_died_age',
'$hh_child_died_cause',
'$is_hh_child_died_registered',
'$hh_child_died_where',
'$common_disease_1',
'$common_disease_2',
'$common_disease_3',
'$barangay_common_need_1',
'$barangay_common_need_2',
'$barangay_common_need_3',

'$where_stay_5_yrs_barangay',
'$where_stay_5_yrs_barangay_municipal',
'$where_stay_5_yrs_barangay_province',
'$name_of_interviewer'

)";
}

	 
	$obj->sql_query($query);
// echo '<pre>';
//   print_r($_POST);
//   echo "query==========================";
//   echo $query;
//   exit;
		$id = ($id != '') ? $id : $obj->GetInsertId();



 $head_ID=$id;
$member_sur_name=$_POST['MemberSurname'];
$member_name=$_POST['MemberName'];
$member_middle_name=$_POST['MemberMiddleInitial'];
$member_date_of_birth=$_POST['MemberDateofBirth'];
$member_ralationship=$_POST['MemberRelationship'];
$member_civil_status=$_POST['MemberCivilStatus'];
$member_sex=$_POST['MemberSex'];
$member_landline=$_POST['MemberLandline'];
$member_mobile=$_POST['MemberMobile'];
$member_email=$_POST['MemberEmailAddress'];
$member_status_residency=$_POST['MemberStatusofResidency'];
$member_special_disability_or=$_POST['MemberSpecialDisabilityorSeniorCitizen'];
$member_religion=$_POST['MemberReligion'];
$member_LGBTI=$_POST['MemberLGBTI'];
$member_work_status=$_POST['MemberStatusofWork'];
$member_occupation=$_POST['MemberOccupation'];
$member_if_student=$_POST['MemberIfStudent'];
$member_work_nature=$_POST['MemberNatureofWork'];
$member_profession_name=$_POST['MemberProfessionName'];
$member_profession_industry=$_POST['MemberProfessionIndustry'];
$member_work_sector=$_POST['MemberWorkingSector'];
$member_helth_benefit_other=$_POST['MemberOtherHealthBenefits'];
$member_membership_or_affiliation_other=$_POST['MemberOtherMembership'];
$member_skill_certification_name=$_POST['MemberNameofCertification'];
//$member_private_institute=$_POST['member_private_institute'];
$member_skill_name=$_POST['MemberNameofSkills'];
$member_inc_expce_fare_home=$_POST['MemberFarefromHousetoOfficeandback'];
$member_inc_expcee_monthly_salary=$_POST['MemberMonthlySalaryorIncomefromBusiness'];
$member_inc_expce_addition_monthly=$_POST['MemberAdditionalMonthlyIncome'];
$member_inc_expce_mothly_expence=$_POST['MemberMonthlyExpenses'];
$member_educational_attainment=$_POST['MemberEducationalAttainment'];

$member_place_of_school_barangay_name  =$_POST['member_place_of_school_barangay_name'];
$member_place_of_school_city  =$_POST['member_place_of_school_city'];
$member_place_of_school_province  =$_POST['member_place_of_school_province'];

$member_place_of_birth  =$_POST['member_place_of_birth'];
$member_citizenship  =$_POST['member_citizenship'];
$member_nationality_if_non_filipino  =$_POST['member_nationality_if_non_filipino'];
$member_ethnicity  =$_POST['member_ethnicity'];
$member_specify_disability  =$_POST['member_specify_disability'];

$member_solo_parent  =$_POST['member_solo_parent'];


$member_type_of_residence = isset($_POST['member_type_of_residence']) ? $_POST['member_type_of_residence'] : '';
$member_date_of_transfer_to_barangay = isset($_POST['member_date_of_transfer_to_barangay']) ? $_POST['member_date_of_transfer_to_barangay'] : '';

$member_reason_for_leaving_place = isset($_POST['member_reason_for_leaving_place']) ? $_POST['member_reason_for_leaving_place'] : '';

$member_plan_to_return_previous_barangay = isset($_POST['member_plan_to_return_previous_barangay']) ? $_POST['member_plan_to_return_previous_barangay'] : '';
$member_plan_to_return_previous_barangay_when = isset($_POST['member_plan_to_return_previous_barangay_when']) ? $_POST['member_plan_to_return_previous_barangay_when'] : '';
$member_intent_duration_stay_barangay = isset($_POST['member_intent_duration_stay_barangay']) ? $_POST['member_intent_duration_stay_barangay'] : '';



$member_place_of_work_barangay_name  =$_POST['member_place_of_work_barangay_name'];
$member_place_of_work_city_municipality  =$_POST['member_place_of_work_city_municipality'];
$member_place_of_work_province  =$_POST['member_place_of_work_province'];

$member_have_a_valid_ctc  =$_POST['member_have_a_valid_ctc'];
$member_ctc_issued_barangay  =$_POST['member_ctc_issued_barangay'];
$member_place_of_delivery  =$_POST['member_place_of_delivery'];
$member_place_of_delivery_specify_other  =$_POST['member_place_of_delivery_specify_other'];
$member_person_assisted_delivery  =$_POST['member_person_assisted_delivery'];
$member_person_assisted_delivery_specify_other  =$_POST['member_person_assisted_delivery_specify_other'];
$member_last_vaccine_received  =$_POST['member_last_vaccine_received'];
$member_no_of_pregnancies_for_mothers  =$_POST['member_no_of_pregnancies_for_mothers'];
$member_no_of_children_living_for_mothers  =$_POST['member_no_of_children_living_for_mothers'];
$member_family_planning_method  =$_POST['member_family_planning_method']; 
$member_source_family_planning_method  =$_POST['member_source_family_planning_method'];
$member_source_family_planning_method_others  =$_POST['member_source_family_planning_method_others'];
$member_intension_use_family_planning  =$_POST['member_intension_use_family_planning'];
$member_intension_use_family_planning_reasons  =$_POST['member_intension_use_family_planning_reasons'];
$member_philhealth_status  =$_POST['member_philhealth_status'];
$member_health_facility_visited_12_months  =$_POST['member_health_facility_visited_12_months'];
$member_health_facility_visited_12_months_others  =$_POST['member_health_facility_visited_12_months_others'];
$member_reason_visiting_health_facility  =$_POST['member_reason_visiting_health_facility'];
$member_reason_visiting_health_facility_others  =$_POST['member_reason_visiting_health_facility_others'];
$member_skill_development_training_interest  =$_POST['member_skill_development_training_interest'];
$member_skill_development_training_interest_others  =$_POST['member_skill_development_training_interest_others'];
$member_present_skills  =$_POST['member_present_skills'];
$member_present_skills_others  =$_POST['member_present_skills_others'];
$member_source_of_income  =$_POST['member_source_of_income'];




    $obj->sql_query("delete from barangay_inhabitants_member where head_ID='$id'");

  for ($i = 0; $i <count($member_name); $i++) {  
    $member_is_student_enrolled  =$_POST['member_is_student_enrolled_'.$i];
    $member_acquired=$_POST['MemberWhereAcquired_'.$i];
    $member_membership_or_affiliation=$_POST['MemberMembership_'.$i];
    $member_helth_benefit=$_POST['MemberHealthBenefits_'.$i];

    $member_senior_citizen  =$_POST['member_senior_citizen_'.$i];

$GenerateBarangayIDNumber = isset($_POST["GenerateBarangayIDNumber"]) ?  $_POST["GenerateBarangayIDNumber"][$i]: "" ;
$query="INSERT INTO `barangay_inhabitants_member`(
`head_ID`,
`gen_barangay_ID`, 
`sur_name`, 
`name`,
`middle_name`, 
`date_of_birth`,
`ralationship`,
`civil_status`, 
`sex`, 
`landline`, 
`mobile`,
`email`, 
`status_residency`,
`special_disability_or`, 
`religion`, 
`LGBTI`, 
`work_status`, 
`occupation`,
`if_student`,
`work_nature`,
`profession_name`,
`profession_industry`,
`work_sector`,
`helth_benefit`,
`helth_benefit_other`,
`membership_or_affiliation`,
`membership_or_affiliation_other`,
`skill_certification_name`,
`acquired`,
`private_institute`,
`skill_name`,
`inc_expce_fare_home`,
`inc_expcee_monthly_salary`,
`inc_expce_addition_monthly`,
`inc_expce_mothly_expence`,
educational_attainment,
`place_of_school_barangay_name`,
`place_of_school_city`,
`place_of_school_province`,
created_dt,
update_dt,
`place_of_birth`,
`citizenship`,
`nationality_if_non_filipino`,
`ethnicity`,
`specify_disability`,
`senior_citizen`,
`solo_parent`,
`type_of_residence`,
`date_of_transfer_to_barangay`,
`reason_for_leaving_place`,
`plan_to_return_previous_barangay`,
`plan_to_return_previous_barangay_when`,
`intent_duration_stay_barangay`,
`place_of_work_barangay_name`,
`place_of_work_city_municipality`,
`place_of_work_province`,
`is_student_enrolled`,
`have_a_valid_ctc`,
`ctc_issued_barangay`,
`place_of_delivery`,
`place_of_delivery_specify_other`,
`person_assisted_delivery`,
`person_assisted_delivery_specify_other`,
`last_vaccine_received`,
`no_of_pregnancies_for_mothers`,
`no_of_children_living_for_mothers`,
`family_planning_method`,
`source_family_planning_method`,
`source_family_planning_method_others`,
`intension_use_family_planning`,
`intension_use_family_planning_reasons`,
`philhealth_status`,
`health_facility_visited_12_months`,
`health_facility_visited_12_months_others`,
`reason_visiting_health_facility`,
`reason_visiting_health_facility_others`,
`skill_development_training_interest`,
`skill_development_training_interest_others`,
`present_skills`,
`present_skills_others`,
`source_of_income`
 
) VALUES (
'$head_ID',
'$GenerateBarangayIDNumber',
'$member_sur_name[$i]', 
'$member_name[$i]', 
'$member_middle_name[$i]', 
'$member_date_of_birth[$i]', 
'$member_ralationship[$i]', 
'$member_civil_status[$i]', 
'$member_sex[$i]', 
'$member_landline[$i]', 
'$member_mobile[$i]', 
'$member_email[$i]', 
'$member_status_residency[$i]', 
'$member_special_disability_or[$i]', 
'$member_religion[$i]', 
'$member_LGBTI[$i]', 
'$member_work_status[$i]', 
'$member_occupation[$i]', 
'$member_if_student[$i]', 
'$member_work_nature[$i]', 
'$member_profession_name[$i]', 
'$member_profession_industry[$i]', 
'$member_work_sector[$i]', 
'$member_helth_benefit',
 '$member_helth_benefit_other[$i]', 
 '$member_membership_or_affiliation', 
 '$member_membership_or_affiliation_other[$i]', 
 '$member_skill_certification_name[$i]', 
 '$member_acquired', 
 '$member_private_institute[$i]', 
 '$member_skill_name[$i]', 
 '$member_inc_expce_fare_home[$i]', 
 '$member_inc_expcee_monthly_salary[$i]', 
 '$member_inc_expce_addition_monthly[$i]', 
 '$member_inc_expce_mothly_expence[$i]',
 '$member_educational_attainment[$i]',
'$member_place_of_school_barangay_name[$i]',
'$member_place_of_school_city[$i]',
'$member_place_of_school_province[$i]',
'$date',
'$date',
'$member_place_of_birth[$i]',
'$member_citizenship[$i]',
'$member_nationality_if_non_filipino[$i]',
'$member_ethnicity[$i]',
'$member_specify_disability[$i]',
'$member_senior_citizen',
'$member_solo_parent[$i]',
'$member_type_of_residence[$i]',
'$member_date_of_transfer_to_barangay[$i]',
'$member_reason_for_leaving_place[$i]',
'$member_plan_to_return_previous_barangay[$i]',
'$member_plan_to_return_previous_barangay_when[$i]',
'$member_intent_duration_stay_barangay[$i]',
'$member_place_of_work_barangay_name[$i]',
'$member_place_of_work_city_municipality[$i]',
'$member_place_of_work_province[$i]',
'$member_is_student_enrolled',
'$member_have_a_valid_ctc[$i]',
'$member_ctc_issued_barangay[$i]',
'$member_place_of_delivery[$i]',
'$member_place_of_delivery_specify_other[$i]',
'$member_person_assisted_delivery[$i]',
'$member_person_assisted_delivery_specify_other[$i]',
'$member_last_vaccine_received[$i]',
'$member_no_of_pregnancies_for_mothers[$i]',
'$member_no_of_children_living_for_mothers[$i]',
'$member_family_planning_method[$i]',
'$member_source_family_planning_method[$i]',
'$member_source_family_planning_method_others[$i]',
'$member_intension_use_family_planning[$i]',
'$member_intension_use_family_planning_reasons[$i]',
'$member_philhealth_status[$i]',
'$member_health_facility_visited_12_months[$i]',
'$member_health_facility_visited_12_months_others[$i]',
'$member_reason_visiting_health_facility[$i]',
'$member_reason_visiting_health_facility_others[$i]',
'$member_skill_development_training_interest[$i]',
'$member_skill_development_training_interest_others[$i]',
'$member_present_skills[$i]',
'$member_present_skills_others[$i]',
'$member_source_of_income[$i]'


)";
   

    $obj->sql_query($query);

}
$_SESSION['msg']="success";
if(!$_POST['webRequest'])
    header("Location:barangayinfo.php?msg=success&userId=$userId");


}

?>