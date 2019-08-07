<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/style.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
  <body>
<div class="container">
<?php
//method
class Request
{
  const TYPE_NONE   = 0;
  const TYPE_ALPHA  = 1;
  const TYPE_DIGIT  = 2;
  const TYPE_ALNUM  = 3;
  const GET         = 10;
  const POST        = 11;
  const COOKIE      = 12;
  const SESSION     = 13;
  public static function get($name, $method = null, $validation = null)
  {
    if ($method < self::GET && $validation === null) {
      $validation = $method;
      $method = self::GET;
    } else if ($method === null ) {
      $method = self::GET;
    }
    if ($validation === null) {
      $validation = self::TYPE_NONE;
    }
    $holder = null;
    switch ($method) {
      case self::GET:
        $holder = $_GET;
        break;
      case self::POST:
        $holder = $_POST;
        break;
      case self::COOKIE:
        $holder = $_COOKIE;
        break;
      case self::SESSION:
        $holder = $_SESSION;
        break;
    }
    if (!isset($holder[$name])) {
      return false;
    }
    $validator = null;
    switch ($validation) {
      case self::TYPE_ALNUM:
        $validator = 'alnum';
        break;
      case self::TYPE_DIGIT:
        $validator = 'digit';
        break;
      case self::TYPE_ALPHA:
        $validator = 'alpha';
        break;
    }
    $ret_val = $holder[$name];
    $valid_func = 'ctype_' . $validator;
    return (($validator === null) ? $ret_val : (( $valid_func($ret_val) ) ? $ret_val : null ) );
  }
}
//end method

//viewtable
date_default_timezone_set("Asia/Bangkok");
class Database
{   
    private $host = "139.59.225.151";
    private $db_name = "admin_channel";
    private $username = "admin_channel";
    private $password = "eMfbrKr07i";
	private $charset = "utf8";
    public $conn;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" .$this->charset, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}

class Action
{	

	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }	

	public function GetLink($tid,$img){
		try {
			$stmt = $this->conn->prepare("SELECT * FROM pw_stream WHERE tid=:val1 LIMIT 1");
			$stmt->execute(array(':val1'=>$tid));
			if($stmt->rowCount() === 1){
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			$f=array($result['link1'],$result['link2'],$result['link3']);
			return $this->CheckLink($f,$tid,$img);
			}else{
			return '';
			}
		} catch (PDOException $e) {
			return $e->getMessage();
		}
	}

	private function CheckLink($f,$tid,$img){
		$h="";
		$count=count($f);
		for($i=0;$i<$count;$i++){
			if($f[$i]!="#"){
				if($f[$i]!=""){
			$h.="<a target='_top' href=\"?id=".$tid."&l=".$i."\"><img style='border-width:1px;border-style: solid;' src='".$img."' /></a>";
				}
			}
		}
		return $h;
	}

	public function PlayLink($tid,$l){
		switch($l){
			case 1:$link="link2";break;
			case 2:$link="link3";break;
			default:$link="link1";
		}
		try {
			$stmt = $this->conn->prepare("SELECT * FROM pw_stream WHERE tid=:val1 LIMIT 1");
			$stmt->execute(array(':val1'=>$tid));
			if($stmt->rowCount() === 1){
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
$today = gmdate("n/j/Y g:i:s A");
$ip = $_SERVER['REMOTE_ADDR'];
$key = "FvUtgvkc8iivm,k/kd"; //enter your key here
$validminutes = 20;
$str2hash = $ip . $key . $today . $validminutes . $signed_stream;
$md5raw = md5($str2hash, true);
$base64hash = base64_encode($md5raw);
$urlsignature = "server_time=" . $today ."&hash_value=" . $base64hash. "&validminutes=$validminutes" . "&strm_len=" . strlen($signed_stream);
$base64urlsignature = base64_encode($urlsignature);
$channel2 = "$result[$link]?wmsAuthSign=$base64urlsignature";

    $a = ['https://live1.', 'https://live2.', 'https://live3.', 'https://live4.'];
    $server = $a[mt_rand(0, count($a) - 1)];

echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/flowplayer/7.2.7/flowplayer.js'></script>
<script src='http://releases.flowplayer.org/hlsjs/flowplayer.hlsjs.min.js'></script>
<link rel='stylesheet' href='//releases.flowplayer.org/7.2.1/skin/skin.css'>
<div class='flowplayer fp-slim' data-aspect-ratio='16:9'>
<video data-title='กดปุ่ม play เพื่อเล่น'>
<source type='application/x-mpegurl' src='".$server."".$channel2."'>
</video>
</div>";
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

}

//end viewtable
$tid=Request::get('id', Request::GET);
$l=Request::get('l', Request::GET);
$action = new Action;
if($tid!="" || $l!=""){
$action->PlayLink($tid,$l);
}
?>
<div class="channel" id="channel">
<h2 class="head-title"><i class="fa fa-calendar-check-o" style="color:"></i> ตารางถ่ายทอดสด</h2>
<?php
$data = array('api_user' => 'linkdooball', 'api_pass' => 'test');
		$handle = curl_init("https://linkdooball.com/channel/dealer/api.php");
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		curl_setopt($handle,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT ,0); 
		curl_setopt($handle, CURLOPT_TIMEOUT, 10);
		$json=curl_exec($handle);
		curl_close($handle);
		$d=str_replace("][",",",$json);
		$jsonDecode = json_decode($d, true);
		foreach ($jsonDecode as $result){
			echo '<div class="accordion">
<div class="divTable mainrow">
<div class="divTableBody">
<div class="divTableRow">
<div class="divTableCell date pc-only"><span class="sp_date">'.$result['date_th'].'</span></div>
<div class="divTableCell team1">'.$result['vs'].'  <span class="logo-team"><img src="'.$result['teama'].'"></span></div>
<div class="divTableCell time"><span class="sp_time"><i class="fa fa-clock-o"> : </i>'.$result['timestart'].'</span></div>
<div class="divTableCell team2"><span class="logo-team"></span><span class="logo-team"><img src="'.$result['teamb'].'"></span> '.$result['vsii'].'</div>
<div class="divTableCell title pc-only">'.$result['league'].'</div>
<div class="divTableCell link-icon"><img src="https://linkdooball.com/img/link.gif"></div>
</div>
</div>
</div>
</div>
<div class="panel logo-chaanel">'.$action->GetLink($result['link1'],$result['img']).'</div>';
	}
?>
</div>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
</script>
</div>
</body>
</html>