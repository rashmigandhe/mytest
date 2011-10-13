<?php

require("Constant.php");

$image_title = $_POST['image_title'];
//echo "Testing image title=".$image_title;
$latitude = $_POST['latitude'];
$longitude = utf8_encode($_POST['longitude']);
$description = utf8_encode($_POST['description']); ;
$historicalimgpath = utf8_encode($_POST['historicalimgpath']);
$markerimgpath = utf8_encode($_POST['markerimgpath']);
$EXIFData = utf8_encode($_POST['EXIFData']);
$created_date = date("Y-m-d G:i:s");		//$_POST['created_date'];
$lastupdated_date = date("Y-m-d G:i:s");		//$_POST['lastupdated_date'];
$isHitorical = utf8_encode($_POST['isHistorical']);
$recId = utf8_encode($_POST['rec_id']);
ini_set('max_upload_filesize', 8388608); 
$file = basename($_FILES['image']['name']);
if($file !=""){
// Db connection 
	$con = mysql_connect($host,$username,$password );
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($database) or die(mysql_error()); 
///////////////////////

// find record id of new recode
		 $newrecId="";
		 $getmaxId = "select max(id)+1 as id from imagedata";
		 $data_2 = mysql_query($getmaxId);
		 print_r($data_2);
		 while($info = mysql_fetch_array( $data_2 )) 
		 {	
			$newrecId = $info['id'];	
		 } 
		 
///////////////////////////////



//updating image name recordid_title 
if($isHitorical=="false")
$file =$newrecId."_".$image_title.strrchr($file, '.'); 
else
$file =($newrecId - 1)."_Historical_".$image_title.strrchr($file, '.'); 

$uploadfile = $uploaddir.$file;
// uploding file in upoder dir define in constant.
if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
        //echo "http://192.168.91.39/iphone/{$file}";
}


$query ="";

if($isHitorical=="false"){
		$markerimgpath =$uploadfile;
		$query = "INSERT INTO imagedata(image_title, latitude, longitude,description,historicalimgpath,markerimgpath,EXIFData,created_date,lastupdated_date)
		VALUES ('".$image_title.'\',\''.$latitude.'\',\''.$longitude.'\',\''.$description .'\',\''.$historicalimgpath.'\',\''.$markerimgpath.'\',\''.$EXIFData.'\',\''.$created_date.'\',\''.$lastupdated_date."')";
		
			$data = mysql_query($query);
			if (!$data) {
				die('Invalid query: ' . mysql_error());
			}
		
		 $newrecId;
		 $getmaxId = "select max(id) as id from imagedata";
		 $data_2 = mysql_query($getmaxId);
		 while($info = mysql_fetch_array( $data_2 )) 
		 {	
			$newrecId = $info['id'];	
		 } 
		 
				
 }
else
{
		/*$markerimgpath =$uploadfile;
		$query = "INSERT INTO imagedata(image_title, latitude, longitude,description,historicalimgpath,markerimgpath,EXIFData,created_date,lastupdated_date)
		VALUES ('".$image_title.'\',\''.$latitude.'\',\''.$longitude.'\',\''.$description .'\',\''.$historicalimgpath.'\',\''.$markerimgpath.'\',\''.$EXIFData.'\',\''.$created_date.'\',\''.$lastupdated_date."')";
			
			$data = mysql_query($query);
			if (!$data) {
				die('Invalid query: ' . mysql_error());
			}
		*/
		$historicalimgpath =$uploadfile;
		$query1 = "Update imagedata set historicalimgpath='".$historicalimgpath."' where id='".$recId."'";
			$data1 = mysql_query($query1);
			if (!$data1) {
				die('Invalid query: ' . mysql_error());
			}
		$newrecId = $recId;
}


$obj_ret->recordId = $newrecId;
 echo json_encode($obj_ret);
 mysql_close($con);
}else
{
  $obj_ret->recordId = "-1";
  $obj_ret->Error = "No image is found";
  echo json_encode($obj_ret);
}
?>
