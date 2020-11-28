<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
header('Content-Type: application/json');

$dir = trim($_GET['dir']);

if(!$dir || strpos($dir,'/') || !is_dir(ROOT.DS.'content'.DS.$dir))
    exit(json_encode(array('status'=>'err')));


$uploads = json_decode(file_get_contents(ROOT.DS.'content'.DS.'uploads.json'),true);

$files = getDirContents(ROOT.DS.'content'.DS.$dir);

$images = [];
$videos = [];
foreach($files as $file)
{
    $ifile = basename(strtolower($file));
    $bname = basename($file);
    $sha1 = sha1_file(ROOT.$file);
    if($uploads[$sha1])
        $file = $uploads[$sha1];
    if(endsWith($ifile,'.jpg') || endsWith($ifile,'.png')|| endsWith($ifile,'.jpeg'))
    {
        if(startsWith($ifile,'preview_')) continue;
        else if(strpos($ifile,'bildunterschrift')!==false)
        {
            $pos = strpos($ifile,'bildunterschrift');
            $images[] = array('file'=>$file,'text'=>pathinfo(substr($bname,($pos+17)), PATHINFO_FILENAME));
        }
        else
            $images[] = array('file'=>$file);
    }
    else if(endsWith($ifile,'.mp4'))
    {
        $videos[] = $file.'/raw';
    }
}

//var_dump(array('status'=>'ok','headers'=>$headers,'images'=>$images,'videos'=>$videos));


if(!$files)
    exit(json_encode(array('status'=>'err')));
else
    exit(json_encode(array('status'=>'ok','images'=>$images,'videos'=>$videos)));



function getDirContents($dir, &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path) && (endsWith(strtolower($path),'.jpg') || endsWith(strtolower($path),'.png')|| endsWith(strtolower($path),'.jpeg')|| endsWith(strtolower($path),'.mp4'))) {
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