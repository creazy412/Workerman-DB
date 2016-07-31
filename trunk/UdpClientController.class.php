<?php
// namespace Home\Controller;
// use Think\Controller;
// require '/var/www/html/mifi_api/ThinkPHP/Library/Think/Controller.class.php';
// require_once getcwd().'/../ThinkPHP/Library/Think/Controller.class.php';

// print_r(getcwd());exit("123");
// class IndexController { //extends Controller
//     public function index(){
//         $this->show('xxx','utf-8');
//     }
    
//     public function index(){


/**
 * mysql connect
 */
 include('./DB.php');
 
 function udpGet($sendMsg = '', $ip = '127.0.0.1', $port = '9998'){
     $handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
     if( !$handle ){
         die("ERROR: {$errno} - {$errstr}\n");
     }
     fwrite($handle, $sendMsg."\n");
     $result = fread($handle, 1024);
     fclose($handle);
     return $result;
 }
 
 $result = udpGet('Hello World');
 echo $result;