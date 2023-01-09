<?php
//check if zip extension is loaded
if (!extension_loaded('zip')) {
    die('Zip extension is not loaded');
}
//make a zip file
$zip = new ZipArchive();
// $zip->open('simplezip.zip', ZipArchive::CREATE);
//add a file to the zip file
// $zip->addFile('example.txt');

//add folder to zip
// $zip->addGlob('sample/*'); // for single folder

//add multiple folders to zip
// $dirName = "sample";
// $realpath = realpath($dirName);
// //instance of recursive
// $iterator = new RecursiveIteratorIterator(
//     new RecursiveDirectoryIterator($realpath),
//     RecursiveIteratorIterator::LEAVES_ONLY
// );
// //loop through the files
// foreach ($iterator as $key => $file) {
//     //skip directories
//     if (!$file->isDir()) {
//         $realfilepath = $file->getRealPath();
//         $explode = explode($realpath, $realfilepath);
//         $finalDir = $explode[1];
//         //add dirName to final directory
//         $finalDir = $dirName . $finalDir;
//         //add file to zip
//         $zip->addFile($realfilepath, $finalDir);
//     }
// }

//close the zip file
// $zip->close();
// echo 'Zip file created';

//extract zip file
$zip->open('simplezip.zip');
$zip->extractTo('extracted');
$zip->close();
echo 'Zip file extracted';
