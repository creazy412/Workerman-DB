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

/*
 +-------------------------------
 *    @socket连接整个过程
 +-------------------------------
 *    @socket_create
 *    @socket_connect
 *    @socket_write
 *    @socket_read
 *    @socket_close
 +--------------------------------
 */
include('./DB.php');
//     public function index(){
        //报错级别
        error_reporting(E_ALL);
        //设置长链接
        set_time_limit(0);
        //ip
        $address = "120.76.42.245";
        //端口
        $tcp_port = 8888;
//         $udp_port = 10015;
        //创建一个套接字
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if( $socket ===false){
            echo "创建一个套接字 失败" . "\n";
        }
        
        echo "试图连接 '$address' 端口 '$tcp_port'...\n";
        $result = socket_connect($socket, $address, $tcp_port);
        
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        }else {
            echo "连接OK\n";
        }
        
        $in = "Ho\r\n";
        $in .= "first blood\r\n";
        $out = '';
        
        if(!socket_write($socket, $in, strlen($in))) {
            echo "socket_write() failed: reason: " . socket_strerror($socket) . "\n";
        }else {
            echo "发送到服务器信息成功！\n";
            echo "发送的内容为:<font color='red'>$in</font> <br>";
        }
        
        $out = socket_read($socket, 8192);
        while($out) {
            echo "接收服务器回传信息成功！\n";
            echo "接受的内容为:",$out;
        }
        
        echo "关闭SOCKET...\n";
        socket_close($socket);
        echo "关闭OK\n";
        
//     }
    
        
        
        
        //领队呼叫
        function leaderCall($device_id){
            
            return true;
            
        }
        
        //的wifi 配置
        function getWifiConfig($device_id){
            
            return true;
            
        }
        
        //WIFI配置更改
        function changeWifiConfig($data_socket_tcp){
            
            return true;
            
        }
        
        //SOS呼叫
        function sosCall($device_id){
            
            return true;
            
        }
        
        //得到指定mifi位置
        function getSpecialMifiPos($device_id){
            
            return true;
            
        }
        
    /* public function client(){
        $confing = array(
            'persistent' => false,
            'host' => '120.76.42.245',
            'protocol' => 'tcp',
            'port' => 10006,
            'timeout' => 1800
        );
        $Socket = new \Components\Socket($confing);
        if (empty($_POST)){
            if($Socket->connect()){
                $tip = "Socket链接成功！<hr>";
            }else{
                $tip = "Socket链接失败！<hr>";
            }
            
            $this->assign("tip",$tip);
            
            $this->display();
        }else{
            $data = $_POST["msg"];
            $Socket->write($data);
            $read = $Socket->read();
            $this->assign("read",$read);
            //$read 是服务端还回的数据
            $this->display();
        }
        
        $Socket->disconnect();
    } */
    
    
//     public function dis() {
//         $this->display();
//     }
// }