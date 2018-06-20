<?php

/**
 * 文件下载
 * @param $file_url [文件绝对路径]
 * @param $filename [文件名称需要标记文件后缀名]
 * @return bool
 */
function download($file_url,$filename){
    if (!file_exists($file_url)){
        return false;
    } else {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_url));
        @readfile($file_url);
    }
}