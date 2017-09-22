<?php

$str = "this is a string that is just some text for you to test with";

function truncateString($str, $chars, $to_space, $replacement="...") {
   if($chars > strlen($str)) return $str;

   $str = substr($str, 0, $chars);
   $space_pos = strrpos($str, " ");
   if($to_space && $space_pos >= 0) 
       $str = substr($str, 0, strrpos($str, " "));

   return($str . $replacement);
}

print(truncateString($str, 20, false) . "\n");
print(truncateString($str, 22, false) . "\n");
print(truncateString($str, 24, true) . "\n");
print(truncateString($str, 26, true, " :)") . "\n");
print(truncateString($str, 28, true, "--") . "\n");
?>