<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$dir = $argv[1];
if (substr($dir, -1, 1) != "/") {
    echo "\n\n" . "Input directory must end with a slash \n\n";
    exit;
}
if (!is_dir($dir)) {
    echo "\n\n" . "Can't find input directory " . $dir . "\n\n";
    exit;
}

$output_dir = $argv[2];
if (substr($output_dir, -1, 1) != "/") {
    echo "\n\n" . "Output directory must end with a slash \n\n";
    exit;
}
if (!is_dir($output_dir)) {
    echo "\n\n" . "Can't find output directory " . $output_dir . "\n\n";
    exit;
}
$resolutions = [];
$resolutions_raw = $argv[3];
$resolutions_raw = explode(",", $resolutions_raw);
foreach ($resolutions_raw as $resolution) {
    $resolution = explode("x", $resolution);
    if (!($resolution[0] > 0 && $resolution[1] > 0)) {
        echo "\n\n" . "incorrect resolution " . $argv[2] . ". Expect 1234x4567,1234x4567.\n\n";
        exit;
    } else {
        $resolutions[] = $resolution[0] . "x" . $resolution[1];
        $resolutions[] = $resolution[1] . "x" . $resolution[0];
    }
}
$copy = ($argv[4] == "copy");

function move_file($path)
{
    global $output_dir, $dir, $copy;
    $new_path = str_replace($dir, $output_dir, $path);
    $new_path_dir = dirname($new_path);
    if (!is_dir($new_path_dir)) {
        mkdir($new_path_dir, 0777, true);
    }

    if ($copy) {
        echo "\n" . $path . " + " . $new_path;
        copy($path, $new_path);
    } else {
        echo "\n" . $path . " > " . $new_path;
        rename($path, $new_path);
    }
}

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    $ext = strtolower(substr($path, -4, 4));
    if (
        $ext == ".jpg"
        || $ext == ".jpeg"
    ) {
        list($width, $height, $type, $attr) = getimagesize($path);

        if (in_array($width . "x" . $height, $resolutions)) {
            move_file($path);
        }
    }


    if (
        $ext == ".mp4"
    ) {
        $r = exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 \"$path\"");

        if (in_array($r, $resolutions)) {
            move_file($path);
        }
    }
}
