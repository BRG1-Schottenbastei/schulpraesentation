<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
header('Content-Type: application/json');

$dir = trim($_GET['dir']);

if(!$dir || strpos($dir,'/') || !is_dir(ROOT.DS.'content'.DS.$dir))
    exit(json_encode(array('status'=>'err')));


$files = getDirContents(ROOT.DS.'content'.DS.$dir);

if(!$files)
    exit(json_encode(array('status'=>'err')));
else
    exit(json_encode(array('status'=>'ok','images'=>$files)));



function getDirContents($dir, &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path) && (endsWith($path,'.jpg') || endsWith($path,'.png'))) {
            $results[] = str_replace(ROOT,'',$path);
        } else if ($value != "." && $value != ".." && is_dir($path)) {
            getDirContents($path, $results);
        }
    }

    return $results;
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
   $length = strlen( $needle );
   if( !$length ) {
       return true;
   }
   return substr( $haystack, -$length ) === $needle;
}