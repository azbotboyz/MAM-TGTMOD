<?php
define('TGT-MUSIC',true);
include("../../tgt/tgt_music.php");
include("../../tgt/class.inputfilter.php");
include("../../tgt/securesession.class.php");
include("../../tgt/class.upload.php");
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
if(isset($_GET["id"])) $id_del=$myFilter->process($_GET["id"]);
if(isset($_GET["p"])) $page=$myFilter->process($_GET["p"]);

if($page > 0 && $page!= "")
	$start=($page-1) * HOME_PER_PAGE;
else{
	$page = 1;
	$start=0;
}

	$sql_tt = "SELECT userid  FROM tgt_nhac_user ORDER BY userid DESC";
	$phan_trang = linkPage($sql_tt,HOME_PER_PAGE,$page,"list_user.php?p=#page#","");
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
                    <td class="title_c">Danh sách &gt; thành viên</td>
                    <td align="right" valign="middle" class="title_c">
                 </td>
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
                <form name=media_list method=post action='list_user.php' onSubmit="return check_checkbox();">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr >
                    <td width="1%" align="center" nowrap class="menu"><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></td>
                      <td nowrap class="menu" width="55%">Tên thành viên </td>
                      <td width="15%" align="center" nowrap class="menu">Email</td>		
                                            <td width="15%" align="center" nowrap class="menu">Chức năng</td>				  
		  
                    </tr>
<?
	$arr_user = $tgtdb->databasetgt("  *  ","user"," userid ORDER BY userid DESC LIMIT ".$rStar .",". HOME_PER_PAGE,"");
	for($i=0;$i<count($arr_user);$i++) {
?>
<tr onMouseOver="bgColor='#c9daf6'" onMouseOut="bgColor='#FFFFFF'">
						  <td nowrap align="center"><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=<? echo $arr_user[$i][0];?>></td>
                       <td  nowrap ><? echo $arr_user[$i][1];?></td>	
					  <td class="song_name" align="left" style="padding-left: 10px;" nowrap  ><? echo $arr_user[$i][3];?></td>						
				    <td align="center" nowrap  style="padding-left:7px"><a href="user.php?mode=edit&id=<? echo $arr_user[$i][0];?>" title="Edit this industry"><img src="../images/edit.png" width="16" height="16" border="0"></a> 
						  <a href="user.php?del_id=<? echo $arr_user[$i][0];?>" title="Xóa" ><img src="../images/b_delete.png" width="11" height="14" border="0"></a></td>
						</tr>
						<tr><td colspan="7" height="1" bgcolor="#CCCCCC"></td></tr>
<? } ?>
		<tr onMouseOver="bgColor='#c9daf6'" onMouseOut="bgColor='#FFFFFF'"><td colspan=7 align="center" style="padding: 5px;" >Với những thành viên đã chọn : 
        <select name="selected_option">
        <option value="del">Xóa</option>
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
		mysql_query("DELETE FROM tgt_nhac_user WHERE userid IN (".$in_sql.")");
		mss ("Xóa thành công ","list_user.php");
	}		
	exit();
}
?>