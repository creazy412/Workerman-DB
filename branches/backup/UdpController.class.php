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
 
 

// $con = mysql_connect("120.76.42.245:3306","root","zmartec20160701");

// if(!$con)
// {
//     die('Could not connect: ' . mysql_error());
// }

// mysql_close($con);


//服务器信息
$server = 'udp://120.76.42.245:8889';
//消息结束符号
$msg_eof = "\n";
$socket = stream_socket_server($server, $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
    die("$errstr ($errno)");
}

do {
    //接收客户端发来的信息
    $inMsg = stream_socket_recvfrom($socket, 1024, 0, $peer);
    //服务端打印出相关信息
    echo "Client : $peer\n";
    echo "Receive : {$inMsg}";
    
//     $sss = gettype(json_decode($inMsg,true));
    
//     file_put_contents('/receive.txt', $sss);
    //给客户端发送信息
    //$outMsg = substr($inMsg, 0, (strrpos($inMsg, $msg_eof))).' -- '.date("D M j H:i:s Y\r\n");
    
    
    
    
    $data_socket = json_decode($inMsg, true);
    
    //功能分支
    if ( $data_socket['action'] ==  'reportposition'){
        
        $outMsg = reportPosition($data_socket);
        
    }elseif ( $data_socket['action'] == 'getleadermifipos' ){
        
        $device_id = $data_socket['mifi_id'];
        $outMsg = getLeaderMifiPos($device_id);
        
    }
    
    stream_socket_sendto($socket, $outMsg, 0, $peer);

} while ($inMsg !== false);


//领队的gps位置信息
function reportPosition($data_socket){
    $dataBase = new DBCONNECT;
    
    $device_id = $data_socket['mifi_id'];
    
    $sql = " SELECT gps_latitude,gps_longtitude,gps_altitude,update_time FROM mf_device_info WHERE `device_id` = 
            (SELECT `owener_id` FROM mf_group WHERE id = (SELECT `group_id` FROM mf_group_members  WHERE `member_id` = $device_id)) ";
    
    $data = $dataBase->getQuery($sql); // This will run the SQL statment and return and associative array.
    
    return json_encode(array('errcode'=>0, 'msg'=>'success', 'response'=>'leadermifipos', 'gpspons'=>$data));
    
    
}

//获取团长位置信息
function getLeaderMifiPos($device_id){
    $dataBase = new DBCONNECT();
    
    $sql = "  ";
    
    return true;
    
}










/* 
        //报错级别
        error_reporting(E_ALL);
        //设置长链接
        set_time_limit(0);
        //ip
        $address = "120.76.42.245";
        //端口
//         $tcp_port = 10013;
        $udp_port = 10015;
        //创建一个套接字
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_UDP);
        if( $sock ===false){
            echo "创建一个套接字 失败" . "\n";
        }
        //启动套接字
        if(socket_bind($sock, $address,$udp_port)===false){
            echo "启动套接字 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        //监听端口
        if(socket_listen($sock,5) === false){
            echo "监听端口 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        do {
            echo "recive client tran data.";
            //似乎是接收客户端传来的消息
            if(($msgsock=socket_accept($sock))===false){
                echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }
            echo "读取客户端传来的消息"."\n";
            $buf = socket_read($msgsock, 8192, 1);
            $talkback = $buf."\n";
            if(false=== socket_write($msgsock, $talkback, 1)){
                echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
            }else{
                echo "return info msg ku fu duan success"."\n";
            }
            sleep(1000);
            socket_close($msgsock);
        }while (true);
        socket_close($sock); */
       
            

        
   /*  $socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
    if ( $socket === false ) {
        echo "socket_create() failed:reason:" . socket_strerror( socket_last_error() ) . "\n";
    }
    $ok = socket_bind( $socket, '120.76.42.245', 10015 );
    if ( $ok === false ) {
        echo "socket_bind() failed:reason:" . socket_strerror( socket_last_error( $socket ) );
    }
    
    //监听端口
    if(socket_listen($socket,5) === false){
        echo "监听端口 失败" . socket_strerror(socket_last_error($sock)) . "\n";
    }
    
while ( true ) {
    
    $msgSocket = socket_accept($socket);var_dump($socket);die;
    if ( $msgSocket === false ){
        
        echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($socket)) . "\n";
                break;
        
    }
    echo "开始读取客户端传来的信息。";
    
    $buf = socket_read($msgSocket, 8192);
    $talkback = "success".$buf."\n";
    if(false === socket_write($msgSocket, $talkback)){
        echo "socket_write() failed reason:" . socket_strerror(socket_last_error($socket)) ."\n";
    }else{
    
        file_put_contents('/tmp/socket-udp.txt', json_encode($buf));
        socket_write($msgSocket, $talkback);
    }
    socket_close($msgSocket); 
    
//     $from = "";
//     $port = 0;
//     $msgRec = socket_recvfrom( $socket, $buf,1024, 0, $from, $port );
//     socket_write($msgRec, "success-fail");
//     echo $buf;
//     usleep( 1000 );
}*/
        
        
        
        
        
        
        
        
        
        
        
        
        
//     }
    
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