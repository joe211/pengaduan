<?php
$connect = mysqli_connect('localhost','root','','project_bkd');
$unduhan = mysqli_query($connect,"select * from unduhs");
while($r = mysqli_fetch_array($unduhan))
{
	if(!file_exists('uploads/unduhan/'.$r['file']))
	{
		mysqli_query($connect,"delete from unduhs where id='".$r['id']."'");
	}
}