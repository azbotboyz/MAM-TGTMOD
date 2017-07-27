<?php
#####################################
#		IPOS V1.0 (TGT 4.5)			#
#		code by ichphienpro			#
#	email: ichphien_pro@yahoo.com	#
#####################################
define('TGT-MUSIC',true);
include("./tgt/tgt_music.php");
include("./tgt/ajax.php");
include("./tgt/class.inputfilter.php");
$myFilter = new InputFilter();
if(isset($_GET["key"])) $key = $myFilter->process($_GET['key']);
if(isset($_GET["type"])) $type = $myFilter->process($_GET['type']);
if(isset($_GET["ks"])) $ks = $myFilter->process($_GET['ks']);
if(isset($_GET["fx"])) $fx = $myFilter->process($_GET['fx']);
if(isset($_GET["ft"])) $ft = $myFilter->process($_GET['ft']);

if(isset($_GET["p"])) $page=$myFilter->process($_GET["p"]);
$ky	 = $key;

$key_text	= str_replace (' ', '+', $key );
$key 		= replace($key);
$key		= str_replace ('-', ' ', $key );
$key		= htmlchars($key);


if($page > 0 && $page!= "")
	$start=($page-1) * HOME_PER_PAGE;
else{
	$page = 1;
	$start=0;
}

	// lấy thông tin ca sĩ
	$arr_singer = $tgtdb->databasetgt(" singer_id ","singer"," singer_name_ascii LIKE '%".$key."%' LIMIT 5");
	for($s=0;$s<count($arr_singer);$s++) {
		$list_singer .= $arr_singer[$s][0].',';
		$singer_list = substr($list_singer,0,-1);
	}
	if(count($arr_singer)>0) {
	 $singer_seach = "OR m_singer IN (".$singer_list.")";
	 $singer_seach_album = "OR album_singer IN (".$singer_list.")";
	}

	if($ks == 'title') {
		if($fx == 'hit') {
			$sql_where = " m_title_ascii LIKE '%".$key."%' AND m_tophot = 1";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=title&fx=hit';
		}
		elseif($fx == 'hq') {
			$sql_where = " m_title_ascii LIKE '%".$key."%' AND m_hq = 1";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=title&fx=hq';
		}
		elseif($fx == 'lyric') {
			$sql_where = " m_title_ascii LIKE '%".$key."%' AND m_lyric != '' ";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=title&fx=lyric';
		}
		elseif(!fx) {
			$sql_where = " m_title_ascii LIKE '%".$key."%' ";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=title';
		}
	}
	elseif($ks == 'singer') {
		if($fx == 'hit') {
			$sql_where = " m_singer IN (".$singer_list.") AND m_tophot = 1 ";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=singer&fx=hit';
		}
		elseif($fx == 'hq') {
			$sql_where = " m_singer IN (".$singer_list.")  AND m_hq = 1 ";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=singer&fx=hq';
		}
		elseif($fx == 'lyric') {
			$sql_where = " m_singer IN (".$singer_list.") AND m_lyric != '' ";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=singer&fx=lyric';
		}
		elseif(!$fx) {
			$sql_where = " m_singer IN (".$singer_list.")";
			$link_s = 'tim-kiem/video.html?key='.$key_text.'&ks=singer';
		}
	}
	elseif(!$ks) {
				$sql_where = " m_title_ascii LIKE '%".$key."%' ".$singer_seach;
				$link_s = 'tim-kiem/bai-hat.html?key='.$key_text;
	}
	if($ft == 'play') 		$sql_order = " ORDER BY m_viewed ";
	elseif($ft == 'down') 	$sql_order = " ORDER BY m_downloaded ";
	elseif($ft == 'time') 	$sql_order = " ORDER BY m_time ";
	elseif(!$ft) 			$sql_order = " ORDER BY m_id ";

	// phan trang
	
	$sql_tt = "SELECT m_id  FROM tgt_nhac_data WHERE ".$sql_where." AND m_type = 2 ".$sql_order." DESC LIMIT ".LIMITSONG;
	$phantrang = linkPage($sql_tt,HOME_PER_PAGE,$page,$link_s."&p=#page#","");
	$result = mysql_query($sql_tt);
	$totalRecord = mysql_num_rows($result);
	$rStar = HOME_PER_PAGE * ($page -1 );
	$arr_song = $tgtdb->databasetgt("  m_id, m_title, m_singer, m_viewed, m_img, m_type, m_cat, m_poster, m_time  ","data",$sql_where." AND m_type = 2  ".$sql_order."  DESC LIMIT ".$rStar .",". HOME_PER_PAGE,"");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<? echo SITE_LINK ?>" />
<title><? echo $ky;?> | video <? echo $ky;?> | Trang <? echo $page;?></title>
<meta name="title" content="tìm kiếm video <? echo $ky;?> mọi lúc mọi nơi" />
<meta name="keywords" content="<? echo $ky;?>, video <? echo $ky;?>, clip <? echo $ky;?>, tìm kiếm, nhạc số, mp3, download nhạc" />
<meta name="description" content="<? echo $ky;?>, video <? echo $ky;?>, clip <? echo $ky;?>, tìm kiếm, nhạc số, mp3, download nhạc" />
<link rel="image_src" href="http://nhac.topgiaitri.com/images/tgt_mp3.jpg" />
<? include("./theme/ip_java.php");?>
</head>
<body>
<div id="main">
	<? include("./theme/ip_header.php");?>
    <div class="top_banner"><?=BANNER('top_banner_search','1006');?></div>
    <div id="contents"  class="contents_bg box">
		<table width="100%" cellpadding="0" cellspacing="0">
        	<tr>
                <td width="120" valign="top">
                <ul class="searchmenu singer_">
					<li class="bottom"><a href="tim-kiem/bai-hat.html?key=<? echo $key_text;?>">Bài hát</a></li>
        			<li class="bt_s09"><a href="tim-kiem/video.html?key=<? echo $key_text;?>">Video</a></li>
                    <li class="bottom"><a href="tim-kiem/playlist.html?key=<? echo $key_text;?>">Playlist</a></li>
				</ul>
        		<ul class="searchmenu singer_">
                	<li class="isearch">Tìm theo</li>
                    <li class="bottom"><a href="tim-kiem/video.html?key=<? echo $key_text;?>">Tất cả</a></li>
					<li class="bottom <? if(!$ks) echo '_black';?>"><a href="tim-kiem/video.html?key=<? echo $key_text;?>&ks=title">Tên video</a></li>
        			<li class="bottom  <? if($ks == "singer") echo '_black';?>"><a href="tim-kiem/video.html?key=<? echo $key_text;?>&ks=singer">Ca sĩ</a></li>
				</ul>
        		<ul class="searchmenu singer_">
					<li class="isearch">Lọc theo</li>
					<li class="bottom <? if(!$fx) echo '_black';?>"><a href="tim-kiem/video.html?key=<? echo $key_text;?>">Tất cả</a></li>
					<li class="bottom <? if($fx == "hit") echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>&fx=hit">Hit</a></li>
					<li class="bottom <? if($fx == "hq") echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>&fx=hq">HQ</a></li>
					<li class="bottom <? if($fx == "lyric") echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>&fx=lyric">Có lyrics</a></li>
				</ul>
        		<ul class="searchmenu singer_">
					<li class="isearch">Xếp theo</li>
					<li class="bottom <? if(!$ft) echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>">Mặc định</a></li>
					<li class="bottom <? if($ft == "play") echo '_black';?>" ><a name="_seaBonFil" href="<? echo $link_s;?>&ft=play">Lượt nghe</a></li>
					<li class="bottom <? if($ft == "down") echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>&ft=down">Lượt download</a></li>
                    <li class="bottom <? if($ft == "time") echo '_black';?>"><a name="_seaBonFil" href="<? echo $link_s;?>&ft=time">Thời gian</a></li>
				</ul>
                </td>
                <td width="580" style="padding:0px 5px; border-left: 1px solid #cfcfcf;" valign="top">
        <div class="border_h2">
        <div class="list_album_s090">
       <div class="title_u">kết quả tìm được <strong><? echo $totalRecord; ?> video </strong>với từ khóa <strong>"<? echo $ky; ?>"</strong></div>

<? if($page <= 20) { 
for($i=0;$i<count($arr_song);$i++) {
	$singer_name = get_data("singer","singer_name"," singer_id = '".$arr_song[$i][2]."'");
	$user_name = get_user($arr_song[$i][7]);
	$type = check_type($arr_song[$i][5],$arr_song[$i][0]);
	$song_url = url_link($arr_song[$i][1],$arr_song[$i][0],'xem-video');
	$user_url = url_link('user',$arr_song[$i][7],'user');
	$singer_url = 'tim-kiem/video.html?key='.text_s($singer_name).'&ks=singer';
?>
 
        <div class="top_video">
        <div class="x_2"><a title="Xem video <? echo $arr_song[$i][1]; ?>" href="<? echo $song_url; ?>"><img class="img_video" src="<? echo check_img($arr_song[$i][4]);?>" title="Xem video <? echo $arr_song[$i][1]; ?>" /></a></div>
        <div class="x_s1">
            <p><a class="title" title="Xem video <? echo $arr_song[$i][1]; ?>" href="<? echo $song_url; ?>"><strong><?=$arr_song[$i][1]; ?></strong></a></p>
            <p>Trình bày: <a class="singer" title="Tìm kiếm bài hát của ca sĩ <? echo $singer_name; ?>" href="<? echo $singer_url; ?>"><? echo $singer_name; ?></a></p>
            <p>Thể loại: <? echo GetTheLoai($arr_song[$i][6]);?></a></p>
        </div>
        <div class="x_s2" align="right"><a href="<? echo $download;?>" target="_blank" title="Tải bài hát <? echo $arr_song[$i][1];?> về máy"><img border="0" src="images/media/down.gif" /></a></div>
        <div class="clr"></div>
        </div>
<?	} ?>
        <div class="pages"><? echo $phantrang; ?></div>
        <? } if($page >= 20) { ?>
			<div class="error_yeu_thich"><? echo NAMEWEB;?> chỉ hiển thị 20 trang kết quả. Để có nhiều kết quả hơn, vui lòng sử dụng chức năng tìm kiếm</div>	
		<? } ?> 
        </div>
                </td>
                <td width="300" valign="top">
                <div class="box">
                    	<h1>Mẹo tìm kiếm</h1>
                        <div style="padding: 10px;">
		<p>Nếu chỉ nhớ một đoạn trong lời bài hát, hãy nhập:<br /> <strong><span class="f14" style="color:#18538C !important">Lyrics:</span></strong> <span class="f14">Lời Bài Hát</span> để tìm kiếm.</p>

		<p>Nếu muốn tìm kiếm bài hát của ca sĩ hoặc nhạc sĩ nào đó. Chỉ cần nhập <span class="f14">Tên Ca Sĩ</span> hoặc <span class="f14">Tên Nhạc Sĩ</span> để tìm kiếm.</p>
		<p>Nếu muốn tìm bài hát do 2 ca sĩ cùng thể hiện, hãy nhập:<br /> <span class="f14">Tên Ca Sĩ 1</span> <span class="f14" style="color:#18538C !important" ><strong>ft.</strong></span> <span class="f14">Tên Ca sĩ 2</span> để tìm kiếm.</p>
                        </div>
                </div>
                <?=BANNER('tim_kiem','300');?>
                </td>
            </tr>
        </table> 
     </div>
    <? include("./theme/ip_footer.php");?>
</div>
</body>
</html>