<?
	include_once("common.php");

	global $generalobj;
	$script="Faq";
	
	
	$meta = $generalobj->getStaticPage(1,$_SESSION['sess_lang']);
	 $meta_arr = $generalobj->getsettingSeo(3);

	 $ssql='';
	 $iFaqcategoryId=isset($_REQUEST['id'])?$_REQUEST['id']:'3';
	 $Type=isset($_REQUEST['type'])?$_REQUEST['type']:'General';
	 if($iFaqcategoryId!="")
	 {
		 $ssql=$ssql."AND iFaqcategoryId='".$iFaqcategoryId."'";
	 }
	 
	
    $sql = "SELECT iFaqId,iFaqcategoryId,iDisplayOrder,vTitle_".$_SESSION['sess_lang']." as Que ,tAnswer_".$_SESSION['sess_lang']." as Ans FROM faqs WHERE eStatus='Active' $ssql ORDER BY iDisplayOrder";
	$db_faqs = $obj->MySQLSelect($sql);

	
	$sql = "SELECT * FROM faq_categories WHERE vCode='".$_SESSION['sess_lang']."' AND eStatus='Active' order by iDisplayOrder";
	$db_faq_categories = $obj->MySQLSelect($sql);

?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--<title><?=$COMPANY_NAME?> | Faq</title>-->
	<title><?php echo $meta_arr['meta_title'];?></title>
	<meta name="keywords" value="<?=$meta_arr['meta_keyword'];?>"/>
	<meta name="description" value="<?=$meta_arr['meta_desc'];?>"/>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
    <?php include_once("top/validation.php");?>
    <!-- End: Default Top Script and css-->
	<script type="text/javascript" src="assets/js/script.js"></script>
</head>
<body>
    <!-- home page -->
    <div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
        <div class="page-contant">
            <div class="page-contant-inner">
                <h2 class="header-page-f"><?=$langage_lbl['LBL_FAQ_TEXT']; ?></h2>
                <!-- contact page -->
                  <div class="faq-page">
                  <div class="faq-top-part">
				 <ul>
                
					<?php 
						if(count($db_faq_categories)>0)
						{
							for($i=0;$i<count($db_faq_categories);$i++)
							
							{ ?>
								<li <? if(trim($Type)==trim($db_faq_categories[$i]['vTitle'])){?>class="Active" <?}?>>
									<a href="javascript:void(0);" onClick="getFaqs('<? echo $db_faq_categories[$i]['vTitle'];?>',<?=$db_faq_categories[$i]['iUniqueId'];?>)"><?echo $db_faq_categories[$i]['vTitle'];?></a>
								</li>
							<?
							}
						}
					?>
				</ul>
                </div>
                <div class="faq-bottom-part" id='cssmenu'>
				  <ul>
								<? 
									for($i=0;$i<count($db_faqs);$i++)
										
									//echo "<pre>";print_r($db_faqs);exit;
								{?>
											<li class='has-sub'>
												<a href="#" class="faq-q">
													<span>
													<b><?=$langage_lbl['LBL_Q']; ?></b>
													<h3><?=$db_faqs[$i]['Que'];?></h3>
													</span>
													</a>
													<ul class="faq-ans"  style="display:none">
														<li id="faq_<?=$db_faqs[$i]['iFaqId']?>">
															<span>  <?=$db_faqs[$i]['Ans'];?></span>
														</li>
													</ul>
											</li>
											
								 <?}?>
						 </ul>
						 </div>
					</div>
                <div style="clear:both;"></div>
            </div>
			<form name="faq" id="faq" action="">
	 
			   <input type="hidden" name="id" id="iUniqueId"  value="">
			   <input type="hidden" name="type" id="CatName"  value="">
			</form>
        </div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
            <!-- End:contact page-->
            <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    
    <script type="text/javascript">
		
		function FacdeQuestion(id)
			{
				if($("#faq_"+id).is( ":visible" )){
					$("#faq_"+id).slideToggle("slow");
				}else{
					$("#faq_"+id).slideToggle("slow");	
				}
			}
	
	 function getFaqs(cat,id)
		{
			$("#iUniqueId").val(id);
			$("#CatName").val(cat);
			document.faq.submit();
		}
		
		
    </script>
    <!-- End: Footer Script -->
</body>
</html>
