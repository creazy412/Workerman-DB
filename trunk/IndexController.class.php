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
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if( $sock ===false){
            echo "创建一个套接字 失败" . "\n";
        }
        //启动套接字
        if(socket_bind($sock, $address, $tcp_port)===false){
            echo "启动套接字 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        //监听端口
        if(socket_listen($sock,5) === false){
            echo "监听端口 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        do {
            echo "recive client tran data.";
            //似乎是接收客户端传来的消息
            $msgsock = socket_accept($sock);
            if($msgsock === false){
                echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }
            
//             echo "读取客户端传来的消息"."\n";
            
            $buf = socket_read($msgsock, 8192);
            $data_socket_tcp = json_decode($buf, true);
            
            $talkback = "success".$buf."\n";
            
            if(false === socket_write($msgsock, $talkback)){
                echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
            }else{
                

                //功能分支
                if ( $data_socket_tcp['action'] == 'leader call' ){
                    $device_id = $data_socket_tcp['mifi_id'];
                    
                    $result = leaderCall($device_id);
                    
                }elseif ( $data_socket_tcp['action'] == 'get wifi config' ){
                    $device_id = $data_socket_tcp['mifi_id'];
                    
                    $result = getWifiConfig($device_id);
                    
                }elseif ( $data_socket_tcp['action'] == 'change wifi config' ){
                    
                    $result = changeWifiConfig($data_socket_tcp);
                    
                }elseif ( $data_socket_tcp['action'] == 'sos call' ){
                    $device_id = $data_socket_tcp['mifi_id'];
                    
                    $result = sosCall($device_id);
                    
                }elseif ( $data_socket_tcp['action' == 'getmifipos'] ){
                    $device_id = $data_socket_tcp['mifi_id'];
                    
                    $result = getSpecialMifiPos($device_id);
                    
                }
                socket_write($msgsock, $result);
            }
            
            socket_close($msgsock);
        }while (true);
        socket_close($sock);
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