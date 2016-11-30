<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$newFileLines = array(); //生成新文件的数组，一行一条数据

$lines = file('csv_test.txt');
foreach ($lines as $line) {
    $line = rtrim($line, "\r\n");
    $line .=",a";
    echo $line;
    $findQuot = stripos($line, "\"");
    if ($findQuot === FALSE) { //如果这一行没有引号
        $arr = explode(",", $line);
    } else {
        $arr = array();
        $lineArry = explode("\",", $line);
        foreach ($lineArry as $tempString) {
            $findQuot = stripos($tempString, "\"");
            if ($findQuot === FALSE) { //没有引号，则按，分割
                $arrTempArray = explode(",", $tempString);
                foreach ($arrTempArray as $arrTempString) {
                    array_push($arr, $arrTempString);
                }
            } else if ($findQuot == 0) { //如果引号在第一个,去掉引号并替换双引号
                $tempString = substr($tempString, 1);
                $tempString = str_replace("\"\"", "\"", $tempString);
                $arr[] = $tempString;
            } else {//有引号，则分割处理，引号左边字符直接按照"，"来分割
                $strLeft = substr($tempString, 0, $findQuot - 1);
                $arrTempArray = explode(",", $strLeft);
                foreach ($arrTempArray as $arrTempString) {
                    array_push($arr, $arrTempString);
                }
                //引号右边处理
                $strRight = substr($tempString, $findQuot + 1);
                $strRight = str_replace("\"\"", "\"", $strRight);
                $arr[] = $strRight;
            }
        }
    }
    array_pop($arr);
    //格式化处理
//    if (count($arr) == 5) {
//        $arr[0] = intval($arr[0]);
//        $arr[1] = "'" . $arr[1] . "'";
//        $arr[2] = "'" . $arr[2] . "'";
//        $arr[3] = floatval($arr[3]);
//        $arr[4] = date('Y/m/d', strtotime($arr[4]));
//    }
    array_push($newFileLines, $arr);
}
print_r($newFileLines);

$file = fopen('output3.txt', "w+");
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

