<?php
require("Constant.php");

$con = mysql_connect($host,$username,$password );
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

$p_radius = $Radius;
$p_log = $_POST['longitude'];
$p_lat = $_POST['latitude'];

mysql_select_db("Circa") or die(mysql_error()); 

$query = 'Select ID,image_title,description,historicalimgpath,markerimgpath,EXIFData,latitude,longitude
		  From (select ID,image_title,description,historicalimgpath,markerimgpath,EXIFData,( 3959 * acos( cos( radians('.$p_lat.') )* cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$p_log.') )+ sin( radians('.$p_lat.') ) * sin( radians( latitude ) ) ) ) AS distance,latitude,longitude FROM imagedata HAVING ifnull(distance,0) < '.$p_radius.' AND ( latitude!=0 AND longitude != 0) ORDER BY distance ) a';
 
 $data = mysql_query($query);
 $imgArray = array();
 $i = 0;
 while($info = mysql_fetch_array( $data )) 
 { 
		$details = array();
		
		$details['id'] = $info['ID'] ;
		$details['image_title'] = $info['image_title'] ;
		$details['description'] = $info['description'] ;
		$details['historicalimgpath'] = $info['historicalimgpath'] ;
		$details['markerimgpath'] = $info['markerimgpath'] ;
		$details['EXIFData'] = $info['EXIFData'] ;
		$details['latitude'] = $info['latitude'] ;
		$details['longitude'] = $info['longitude'] ;		
		$imgArray[$i] = $details;
		$i++;
		
 } 
		$responseimgArray = array();
		$responseimgArray['imagedetails'] = $imgArray;
		echo(json_encode($responseimgArray));
 
mysql_close($con);


?>
