<?php
define('TGT-MUSIC',true);
include("../../tgt/tgt_music.php");
include("../../tgt/class.inputfilter.php");
include("../../tgt/securesession.class.php");
include("../../tgt/class.upload.php");
include("../fckeditor/fckeditor.php");
include("../functions.php");
$myFilter = new InputFilter();
$upload =& new UPLOAD_FILES();
$ss = new SecureSession();
$ss->check_browser = true;
$ss->check_ip_blocks = 2;
$ss->secure_word = 'SALT_';
$ss->regenerate_id = true;
$ss->Open();
include("../auth.php");
// phan trang


if(isset($_GET["mode"])) $mode=$myFilter->process($_GET["mode"]);
if(isset($_GET["act"])) $act=$myFilter->process($_GET["act"]);
if(isset($_GET["search"])) $search=$myFilter->process($_GET["search"]);
if(isset($_GET["id"])) $id_del=$myFilter->process($_GET["id"]);
if(isset($_GET["p"])) $page=$myFilter->process($_GET["p"]);


if($page > 0 && $page!= "")
	$start=($page-1) * HOME_PER_PAGE;
else{
	$page = 1;
	$start=0;
}
if($search) {
	$search 	=  get_ascii($search);
	$sql_where 	= "singer_name_ascii LIKE '%".$search."%'";
	$link_pages = "list_singer.php?search=".$search."&";
}
else {
	$sql_order = "singer_id ORDER BY singer_id DESC";
	$link_pages = "list_singer.php?";
}
	$sql_tt = "SELECT singer_id  FROM tgt_nhac_singer WHERE $sql_where $sql_order LIMIT 660";

	$phan_trang = linkPage($sql_tt,HOME_PER_PAGE,$page,$link_pages."p=#page#","");

	$rStar = HOME_PER_PAGE * ($page -1 );

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Content</title>

<link href="../styles/style.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../styles/admin.js"></script>

</head>

<script language="JavaScript" type="text/JavaScript">

<!--

function onover(obj,cls){obj.className=cls;}

function onout(obj,cls){obj.className=cls;}

function ondown(obj,url,cls){obj.className=cls; window.location=url;}

//-->

</script>

<body topmargin="0" leftmargin="0">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td class="style_border" width="7" >&nbsp;</td>

    <td valign="top" class="style"><table width="100%" height="100%" border="0" cellspacing="1" cellpadding="0">

        <tr>

          <td valign="top" class="style_bg"><table width="100%" border="0" cellspacing="0" cellpadding="0">

              <tr>

                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td class="title_c">Danh sách &gt; ca sĩ</td>

                    <td align="right" valign="middle" class="title_c">

                   ID ca sĩ cần sửa: 

                   <input id=m_id size=10 value="" class="input">

                      <input type="image" src="../images/b_search.gif" onclick='window.location.href = "singer.php?mode=edit&id="+document.getElementById("m_id").value;'>

                    

                    ID ca sĩ cần xóa: 

                    <input id=m_del_id size=10 value="" class="input">

 <input type="image" src="../images/b_search.gif" onclick='window.location.href = "singer.php?del_id="+document.getElementById("m_del_id").value;'>

                    

                    Tìm theo tên ca sĩ: <input id=search size=40 value="<? echo $search;?>" class="input">

                      <input type="image" src="../images/b_search.gif" onclick='window.location.href = "list_singer.php?search="+document.getElementById("search").value;'>&nbsp;&nbsp;&nbsp; </td>

                  </tr>

                </table>               </td>

              </tr>

            </table>

			

			<table width="100%" border="0" cellspacing="0" cellpadding="0">

			</table>

				

		    <table width="100%" border="0" cellspacing="0" cellpadding="0">

              <tr>

                <td nowrap class="menu"><? echo $phan_trang;?></td>

              </tr>

              <tr>

                <td colspan="">

                <form name=media_list method=post action='list_singer.php' onSubmit="return check_checkbox();">

                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                    <tr >

                    <td width="1%" align="center" nowrap class="menu"><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td>

                    <td nowrap class="menu">hình ảnh </td>

                      <td nowrap class="menu" width="80%">Tên ca sĩ </td>
						<td nowrap class="menu" width="10%">Ca sĩ HOT</td>
                      <td width="10%" align="center" nowrap class="menu">Chức năng</td>					  

                    </tr>

<?
$arr_singer = $tgtdb->databasetgt("  singer_id, singer_name, singer_img, singer_hot  ","singer"," $sql_where $sql_order LIMIT ".$rStar .",". HOME_PER_PAGE,"");
for($i=0;$i<count($arr_singer);$i++) {
	if($arr_singer[$i][3] == 1) $status = '<a href="singer.php?bohot='.$arr_singer[$i][0].'"><img src="../images/status.gif" border=0></a>';
	else	$status = '<a href="singer.php?sethot='.$arr_singer[$i][0].'"><img src="../images/status_no.gif" border=0></a>';
	
	if (!@ereg("http://",$arr_singer[$i][2])) {
		if($arr_singer[$i][2] != "")
			$img_src = SITE_LINK."/".str_replace(" ","%20",$arr_singer[$i][2]);
		else
			$img_src = check_img($arr_singer[$i][2]);
	}
	else
		$img_src = $arr_singer[$i][2];
	
?>

<tr onMouseOver="bgColor='#c9daf6'" onMouseOut="bgColor='#FFFFFF'">

						  <td nowrap align="center"><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=<? echo $arr_singer[$i][0];?>></td>

                       <td align="center" nowrap ><img class=IMG_S src="<? echo $img_src;?>"></td>	

					  <td class="song_name" align="left" style="padding-left: 10px;" nowrap  ><a href="singer.php?mode=edit&id=<? echo en_id($arr_singer[$i][0]);?>" title="Edit this industry"><? echo $arr_singer[$i][1];?></a></td>						

<td align="center" nowrap  style="padding-left:7px">
<? echo $status;?>
</td>
				    <td align="center" nowrap  style="padding-left:7px">
                  
                    <a href="singer.php?mode=edit&id=<? echo en_id($arr_singer[$i][0]);?>" title="Edit this industry"><img src="../images/edit.png" width="16" height="16" border="0"></a> 

						  <a href="singer.php?del_id=<? echo en_id($arr_singer[$i][0]);?>" title="Xóa danh mục này" ><img src="../images/b_delete.png" width="11" height="14" border="0"></a></td>

						</tr>

						<tr><td colspan="7" height="1" bgcolor="#CCCCCC"></td></tr>

<? } ?>

		<tr onMouseOver="bgColor='#c9daf6'" onMouseOut="bgColor='#FFFFFF'"><td colspan=7 align="center" style="padding: 5px;" >Với những Media đã chọn : 

        <select name="selected_option">

        <option value="del">Xóa</option>
		<option value="hot">Set HOT</option>
        </select>

        <input type="submit" name="do" class=submit value="Thực hiện"></td></tr>

		</form>

        <tr><td colspan="7" height="1" bgcolor="#CCCCCC"></td></tr>

                </table></td>

              </tr>

            </table></td>

        </tr>

      </table></td>

  </tr>

</table>

</body>

</html>

<?
if ($_POST['do']) {
	$arr = $_POST['checkbox'];
	if (!count($arr)) die('Lỗi');
	if ($_POST['selected_option'] == 'del') {
		$in_sql = implode(',',$arr);
		mysql_query("DELETE FROM tgt_nhac_singer WHERE singer_id IN (".$in_sql.")");
		mss ("Xóa thành công ","list_singer.php");
	}		
	if ($_POST['selected_option'] == 'hot') {
		$in_sql = implode(',',$arr);
		mysql_query("UPDATE tgt_nhac_singer SET singer_hot = 1 WHERE singer_id IN (".$in_sql.")");
		mss ("Update thành công ","list_singer.php");
	}	
	exit();
}
?>