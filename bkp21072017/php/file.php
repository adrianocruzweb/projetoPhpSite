<?php
	//Post do requisição http do angular
	include('funcao.php');
	include('seLiga.php');

	if ($_FILES["file"]["error"] > 0){
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
    else{
        /*
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        */

        if (file_exists("../nimg/" . $_FILES["file"]["name"])){
            echo $_FILES["file"]["name"];
        }
        else{
        	/*
            $title = "ImgNews";
            mkdir("../nimg/".$title, 0700);
            */
            move_uploaded_file(
            	$_FILES["file"]["tmp_name"],
            	"../nimg/".$_FILES["file"]["name"]
            );
            //echo "Stored in: " ."../nimg/".$title . $_FILES["file"]["name"];
            echo $_FILES["file"]["name"];
        }
    }

	die();
?>