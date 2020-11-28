<?php

if(php_sapi_name() !== 'cli') exit('This script can only be called via CLI');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
$files = getDirContents(ROOT.DS.'content');

$uploads = json_decode(file_get_contents(ROOT.DS.'content'.DS.'uploads.json'),true);

foreach($files as $file)
{
    $path = ROOT.$file;
    $sha1 = sha1_file($path);
    if($file=='/content/g19.mp4') continue;
    if(!$uploads[$sha1])
    {
        echo "[U] $file\n";
        $data = pictshareUploadImage($path);
        if($data['hash'])
        {
            $uploads[$sha1] = $data['url'];
        }
        else{ var_dump($data);exit("ERROR AT UPLOAD OF $file\n");}
    }
    file_put_contents(ROOT.DS.'content'.DS.'uploads.json',json_encode($uploads));
}



//file_put_contents(ROOT.DS.'content'.DS.'uploads.json',json_encode($uploads));




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

function pictshareHashExists($hash)
{
    $url = 'https://pictshare.net/'.$hash;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
    curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT,10);
    $output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode!=200) return false;
    return true;
}

function pictshareUploadImage($path,$hash=false)
{
    $request = curl_init('https://pictshare.net/api/upload.php');

    // send a file
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt(
        $request,
        CURLOPT_POSTFIELDS,
        array(
        'file' => curl_file_create($path),
        'hash'=>$hash
        ));

    // output the response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $json = json_decode(curl_exec($request).PHP_EOL,true);

    // close the session
    curl_close($request);

    return $json;
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