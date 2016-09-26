<?php

$file_folder = $_POST['file_path'];

if($file_folder == ""){
  echo "Path cannot be empty!";
  exit;
}
else {
  sleep(5);
  //exec("Rscript gen_medhacks.R $file_folder");

}

?>
