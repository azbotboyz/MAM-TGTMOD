<?php 
if (!defined('TGT-MUSIC')) die("ichphien_pro !"); 
$cat = $tgtdb->databasetgt("cat_id, cat_name","theloai"," sub_id = 0 ORDER BY cat_order ASC");
for($z=0;$z<count($cat);$z++) {
	$cat_url = url_link($cat[$z][1],$cat[$z][0],'video-cat');
?>
<div class="box w_1">
	<h1><?=$cat[$z][1];?></h1>
    <div class="padding">
    	<ul>
        <?
        $cats1 = $tgtdb->databasetgt("cat_id, cat_name","theloai"," sub_id = '".$cat[$z][0]."' ORDER BY cat_order ASC");
        for($i=0;$i<count($cats1);$i++) {
            $cats1_url = url_link($cats1[$i][1],$cats1[$i][0],'video-cat');
        ?>
        	<li><a href="<?=$cats1_url;?>"><?=$cats1[$i][1];?></a></li>
        <? } ?>
		</ul>
	</div>
</div>
<? } ?>