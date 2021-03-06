<?php

if(array_key_exists("filename", $_REQUEST)) {
    $filename = $_REQUEST["filename"];
}

$fullpath = "";
if(array_key_exists("fullpath", $_REQUEST)) {
    $fullpath = $_REQUEST["fullpath"];
}

$shop_karaoke = 0;
if(array_key_exists("shop_karaoke", $_REQUEST)) {
    $shop_karaoke = $_REQUEST["shop_karaoke"];
}
    


include 'kara_config.php';

$sql = "SELECT * FROM requesttable ORDER BY reqorder DESC";
$select = $db->query($sql);
$allrequest = $select->fetchAll(PDO::FETCH_ASSOC);
$select->closeCursor();

function pickupsinger($rt)
{
   $singerlist = array();
   foreach($rt as $row)
   {
       $foundflg = 0;
       foreach($singerlist as $esinger ){
           if( $esinger === $row['singer']){
               $foundflg = 1;
               break;
           }
       }
       if($foundflg === 0){
           $singerlist[] = $row['singer'];
       }
   }
   
   return $singerlist;
}

function selectedcheck($rt,$singer){
    $rt_i = array_reverse($rt);
    foreach($rt_i as $row){
        if($row['singer'] === $singer){
          if($row['clientip'] === $_SERVER["REMOTE_ADDR"] ) {
            if($row['clientua'] === $_SERVER["HTTP_USER_AGENT"] ) {
                return TRUE;
            }
          }
        }
    }
    
//    $singerlist = pickupsinger($rt);
//    if ($singerlist[count($singerlist) - 1] === $singer )
//        return TRUE;
    return FALSE;
}



?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="viewport" content="width=width,initial-scale=1.0,minimum-scale=1.0">


<title>カラオケ動画リクエスト確認画面</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script type="text/javascript">

function check(selectf){
flg=(document.getElementById('singer').selectedIndex==0);
if (!flg) document.getElementById('freesinger').value='';
document.getElementById('freesinger').parentNode.style.visibility=flg?'visible':'hidden';
}
</script>
</head>
<body>

<form method="post" action="exec.php">



<form method="post" action="exec.php">
<div CLASS="itemname">
曲名(ファイル名)<br>

<textarea name="filename" id="filename" rows="4" wrap="soft" style="width:100%" >
<?php
    if (empty($filename)){
      echo "曲名";
    }else{
      echo "$filename";
    }
?>
</textarea>

    <input type="hidden" name="fullpath" id="fullpath" style="width:100%" value=<?php echo '"'.$fullpath.'"'; ?> />
</div>

<div CLASS="singer">
リクエスト者<br>
<select name="singer" onchange="check(this.form)" onfocus="check(this.form)" id="singer">
<option value="0">新規入力↓</option>
<?php
$num = 1;

$selectedcounter = 0;
$singerlist = pickupsinger($allrequest);
foreach($singerlist as $singer){
{
  print "<option value=\"";
  print $singer;
  print "\"";
  if( selectedcheck($allrequest,$singer) && $selectedcounter === 0 ) 
  {
      print " selected ";
      $selectedcounter = $selectedcounter + 1 ;
  }
  print "> ";
  print htmlspecialchars($singer);
  print "</option>";
}
}
?>



</select>
<?php
if($selectedcounter === 0){
print('<span style="visibility:visible;">');
}else{
print('<span style="visibility:hidden;">');
}
?>
<input type="text" name="freesinger" id="freesinger" style="width:100%" placeholder="名前を書いてね。２回目からは上のドロップダウンから選べるようになります。" >
</span>
</div>

<div CLASS="comment">
コメント<br>
<textarea name="comment" id="comment" rows="4" wrap="soft" placeholder="<?php print htmlspecialchars($requestcomment);?>" style="width:100%" >
</textarea>
</div>

<div CLASS="method">
再生方法<br>
<select name="kind">
 <option value="動画" <?php if($shop_karaoke == 0) print 'selected';?> >動画 </option>
 <option value="カラオケ配信" <?php if($shop_karaoke == 1) print 'selected';?> >カラオケ配信 </option>
 </select>
</div>

<div CLASS="pushbtn">
<input type="submit" value="実行"/>
</div>

</form>
<a href="search.php" >通常検索に戻る </a>
&nbsp; 
<a href="request.php" >トップに戻る </a>

</body>
</html>
