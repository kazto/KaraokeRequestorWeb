<?php

if (!isset($_SERVER['PHP_AUTH_USER'])){
    header('WWW-Authenticate: Basic realm="Please use username admin to open Configuration page. "');
    die('このページを見るにはログインが必要です');
}

if ($_SERVER['PHP_AUTH_USER'] !== 'admin'){
    header('WWW-Authenticate: Basic realm="You can use username only admin."');
    die('このページを見るにはログインが必要です');
}

require_once 'commonfunc.php';

if(array_key_exists("filename", $_REQUEST)) {
    $newdb = $_REQUEST["filename"];
}

if(array_key_exists("playmode", $_REQUEST)) {
    $newplaymode = $_REQUEST["playmode"];
}

if(array_key_exists("playerpath_any", $_REQUEST)) {
    $newplayerpath = $_REQUEST["playerpath_any"];
    echo "set newplayerpath from any".$newplayerpath."\n";
    if(empty($newplayerpath)){
        $newplayerpath = $_REQUEST["playerpath"];
        echo "set newplayerpath from tmpl".$newplayerpath."\n";
    }    
}

if(array_key_exists("foobarpath", $_REQUEST)) {
    $newfoobarpath = $_REQUEST["foobarpath"];
}

if(array_key_exists("requestcomment", $_REQUEST)) {
    $newrequestcomment = $_REQUEST["requestcomment"];
}

if(array_key_exists("clearauth", $_REQUEST)) {
    header('HTTP/1.0 401 Unauthorized');
}

//include 'kara_config.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
<meta name="viewport" content="width=width,initial-scale=1.0,minimum-scale=1.0">
<title>DBファイル名設定画面</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
</head>
<body>


<?php
if (! empty($newdb)){
    $dbname = $newdb;
    $config_ini = array_merge($config_ini,array("dbname" => $dbname));
    $fp = fopen($configfile, 'w');
    foreach ($config_ini as $k => $i) fputs($fp, "$k=$i\n");
    fclose($fp);
    print "DB ファイル名を".$dbname."に変更しました。";
}

if (! empty($newplaymode)){
    $playmode = $newplaymode;
    $config_ini = array_merge($config_ini,array("playmode" => $playmode));
    $fp = fopen($configfile, 'w');
    foreach ($config_ini as $k => $i) fputs($fp, "$k=$i\n");
    fclose($fp);
    print "動作モードを".$playmode."に変更しました。<br><br>";
}

if (! empty($newplayerpath)){
    $playerpath = $newplayerpath;
    $config_ini = array_merge($config_ini,array("playerpath" => urlencode($playerpath)));
    $fp = fopen($configfile, 'w');
    foreach ($config_ini as $k => $i) fputs($fp, "$k=$i\n");
    fclose($fp);
    print "MPCのPATHを".$playerpath."に変更しました。<br><br>";
}

if (! empty($newfoobarpath)){
    $foobarpath = $newfoobarpath;
    $config_ini = array_merge($config_ini,array("foobarpath" => urlencode($foobarpath)));
    $fp = fopen($configfile, 'w');
    foreach ($config_ini as $k => $i) fputs($fp, "$k=$i\n");
    fclose($fp);
    print "foobar2000のPATHを".$foobarpath."に変更しました。<br><br>";
}

if (! empty($newrequestcomment)){
    $requestcomment = $newrequestcomment;
    $config_ini = array_merge($config_ini,array("requestcomment" => urlencode($requestcomment)));
    $fp = fopen($configfile, 'w');
    foreach ($config_ini as $k => $i) fputs($fp, "$k=$i\n");
    fclose($fp);
    print "リクエスト画面のコメント欄の説明を変更しました。<br><br>";
}else {
    // $requestcomment = "雑談とかどうぞ。その他見つからなかった曲とか、ダウンロードしておいてほしいカラオケ動画のURLとかあれば書いておいてもらえるとそのうち増えてるかも";
}


?>
 
現在のDBファイル名 : 
<?php
print $dbname;
?>
<br>
現在の動作モード(1: 自動再生開始モード, 2: 手動再生開始モード, 3: 手動プレイリスト登録モード, 4: BGMモード(ジュークボックスモード), 5: BGMモード(フルランダムモード) ) : 
<?php
print $playmode;
?>
<br>
現在のMediaPlayerClassic PATH :
<?php
print $playerpath;
?>
<br>
現在のfoobar2000 PATH :
<?php
print $foobarpath;


print $requestcomment;
?>

<br>

<hr>

新しいファイル名　
<form method="post" action="init.php">
<input type="text" size=20 name="filename" id="filename" value=<?php echo $dbname; ?> >
<input type="submit" value="OK" />
</form>

動作モード選択　
<form method="post" action="init.php">
<select name="playmode" id="playmode" >  
<option value="1" <?php print selectedcheck("1",$playmode); ?> >自動再生開始モード</option>
<option value="2" <?php print selectedcheck("2",$playmode); ?> >手動再生開始モード</option>
<option value="3" <?php print selectedcheck("3",$playmode); ?> >手動プレイリスト登録モード</option>
<option value="4" <?php print selectedcheck("4",$playmode); ?> >BGMモード(ジュークボックスモード)</option>
<option value="5" <?php print selectedcheck("5",$playmode); ?> >BGMモード(フルランダムモード)</option>
</select>
<input type="submit" value="OK" />
</form>

MediaPlayerClassic PATH設定　
<form method="post" action="init.php">
<select name="playerpath" id="playerpath" >  
<option <?php print selectedcheck("C:\Program Files (x86)\MPC-BE\mpc-be.exe",$playerpath); ?> value="C:\Program Files (x86)\MPC-BE\mpc-be.exe" >C:\Program Files (x86)\MPC-BE\mpc-be.exe (MPC-BE:64bitOSで32bit版)</option>
<option <?php print selectedcheck("C:\Program Files\MPC-BE\mpc-be.exe",$playerpath); ?> value="C:\Program Files\MPC-BE\mpc-be.exe" >C:\Program Files\MPC-BE\mpc-be.exe (32bitOSでMPC-BE32bit版 or MPC-BE64bit版)</option>
</select>
<br />
&nbsp;(任意のPATH選択):
<input type="text" name="playerpath_any" size="100" class="playerpath_any" />
<input type="submit" value="OK" />
</form>

foobar2000 PATH設定　
<form method="post" action="init.php">
任意のPATH選択 :
<input type="text" name="foobarpath" size="100" class="foobarpath" value="<?php echo $foobarpath; ?>" />
<input type="submit" value="OK" />
</form>
<hr />

リクエスト画面の説明書き
<form method="post" action="init.php">
<textarea name="requestcomment" id="comment" rows="4" wrap="soft" style="width:100%" >
<?php print htmlspecialchars($requestcomment); ?>
</textarea>
<input type="submit" value="OK" />
</form>

<hr />
<a href ="listexport.php" > リクエストリストのダウンロード </a>
<form action="listimport.php" method="post" enctype="multipart/form-data">
リクエストリストのインポート(csvより)
<input type="file" name="dbcsv" accept="text/comma-separated-values" />
<select name="importtype" id="importtype" > 
<option value="new" >新規</option>
<option value="add" >追加</option>
</select>

<input type="submit" value="Send" />  
</form>
<a href ="listclear.php" > リクエストリストの全消去 </a>

<hr />
<form method="post" action="delete.php">
<input type="submit" name="resettsatus" value="全て未再生化" />
</form>
<hr />
BGMモード用
&nbsp;
<a href ="listtimesclear.php?times=0" > 再生回数0クリア </a>
&nbsp;
<a href ="listtimesclear.php?times=1" > 再生回数1クリア </a>

<hr />

<p>
<a href ="init.php?clearauth=1" > ログイン情報クリア </a>
</p>

<a href="request.php" > リクエストTOP画面に戻る　</a>

</body>
</html>

