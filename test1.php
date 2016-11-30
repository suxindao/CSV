<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$newFileLines = array(); //生成新文件的数组，一行一条数据

$lines = file('csv.txt');
foreach ($lines as $line) {
//    echo $line;
    $findQuot = stripos($line, "\"");
    if ($findQuot === FALSE) { //如果这一行没有引号
        $arr = explode(",", $line);
    } else {
        //将字符串按照双引号部分分割为三部分，第一个引号左侧为$strLeft，最后一个引号右侧部分为$strRight，中间部分为$strmid
        //处理$strLeft
        $strLeft = substr($line, 0, $findQuot - 1);
        $arr = explode(",", $strLeft);

        //查找最后一个引号的位置
        $findQuot2 = strripos($line, "\"");
//        echo "\nfindQuot = " . $findQuot . "\n";
//        echo "\nfindQuot2 = " . $findQuot2 . "\n";
        //处理$strmid，去掉两边的引号
        $strmid = substr($line, $findQuot + 1, $findQuot2 - $findQuot - 1);
        //还原两个引号为一个引号
        $strmid = str_replace("\"\"", "\"", $strmid);
//        echo "\nstrmid = " . $strmid . "\n";
        $arr[] = $strmid;

        //处理$strRight
        $strRight = substr($line, $findQuot2 + 2);
//        echo "\nstrRight = " . $strRight . "\n";
        $strRightArray = explode(",", $strRight);
        $arr = array_merge($arr, $strRightArray);
    }

    //格式化处理
    if (count($arr) == 5) {
        $arr[0] = intval($arr[0]);
        $arr[1] = "'" . $arr[1] . "'";
        $arr[2] = "'" . $arr[2] . "'";
        $arr[3] = floatval($arr[3]);
        $arr[4] = date('Y/m/d', strtotime($arr[4]));
    }
    array_push($newFileLines, $arr);
}
print_r($newFileLines);

$file = fopen('output.txt', "w+");
foreach ($newFileLines as $lineArray) {
    $jsString = "";
    foreach ($lineArray as $arr) {
        $jsString .= $arr . "\t";
    }
//    $jsString = substr($jsString, 0, strlen($jsString) - 1);
//    $jsString = substr($jsString, 0, -1);
    $jsString = rtrim($jsString, "\t");
    $jsString .= "\r\n";
    fwrite($file, $jsString);
}

fclose($file);

