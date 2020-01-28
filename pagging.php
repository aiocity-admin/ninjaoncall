        <?php if($endRecord > 0) { ?>
<span class="pagin_left">Showing <b><?php if($total_results > 0) {echo ($start+1); } else { echo 0; } ?></b> to <b><?php echo ($start+$endRecord); ?></b> of <b><?php echo $total_results; ?></b> entries</span>
<?php } ?>
<div class="pagination">
<ul>
<?php if ($total_pages > 1) {
   // echo "pagging".paginate($reload, $show_page, $total_pages);


$adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $firstlabel = "&lsaquo;&lsaquo; First";
    $Lastlabel = "Last &rsaquo;&rsaquo;";
    $out = "";
    // previous
    if ($page == 1) {
        $out.= "<span class='disabled-page001'>" . $prevlabel . "</span>\n";
    } elseif ($page == 2) {
        $out.= "<li><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
    } else {
        $out.= "<li><a  href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
    }
  if ($page > 3) {
        $out.= "<a style='font-size:11px' href='" . $reload . "'&amp;page='1'>".$firstlabel."</a>\n";
    }
  
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
        } else {
            $out.= "<li><a  href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n</li>";
        }
    }
    
    if ($page < ($tpages - $adjacents)) {
        $out.= "<a style='font-size:11px' href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $Lastlabel . "</a>\n";
    }
    // next
    if ($page < $tpages) {
        $out.= "<li><a  href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
    } else {
        $out.= "<span class='disabled-page002'>" . $nextlabel . "</span>\n";
    }
    $out.= "";
    echo $out;

} ?>
</ul></div>
<script type="text/javascript">
        function Redirect(sortby,order) {
            //$('html').addClass('loading');
            $("#sortby").val(sortby);
            if(order == 0) { order = 1; } else { order = 0; }
            $("#order").val(order);
            $("#page").val('1');
            var action = $("#_list_form").attr('action');
            var formValus = $("#pageForm").serialize();
            //alert(formValus);
            window.location.href = action+"?"+formValus;
    }
    
</script>