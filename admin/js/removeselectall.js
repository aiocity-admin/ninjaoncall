$(document).ready(function(){
	$(".multiselect").click(function(){  
  $(document).on("keyup", ".multiselect-search", function (e) {
var target=$(this).parent('.input-group').parent('.multiselect-item').parent(".multiselect-container");
setTimeout(function(){
if(target.find("li:visible").length<=3)
target.find("li").eq(1).css("display","none");
else
target.find("li").eq(1).css("display","");
  }, 300);
});
});
});