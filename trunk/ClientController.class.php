<?php
namespace Home\Controller;
// use Think\Controller;
require '/var/www/html/mifi_api/ThinkPHP/Library/Think/Controller.class.php';
class ClientController extends Controller {
    public function index(){
        $confing = array(
            'persistent' => false,
            'host' => '120.76.42.245',
            'protocol' => 'tcp',
            'port' => 10012,
            'timeout' => 60
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
    }
    
    /* public function server(){
        //报错级别
        error_reporting(E_ALL);
        //设置长链接
        set_time_limit(0);
        //ip
        $address = "120.76.42.245";
        //端口
        $port = 10006;
        //创建一个套接字
        if( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) ===false){
            echo "创建一个套接字 失败" . "\n";
        }
        //启动套接字
        if(socket_bind($sock, $address,$port)===false){
            echo "启动套接字 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        //监听端口
        if(socket_listen($sock,5) === false){
            echo "监听端口 失败" . socket_strerror(socket_last_error($sock)) . "\n";
        }
        do {
            //似乎是接收客户端传来的消息
            if(($msgsock=socket_accept($sock))===false){
                echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }
            //echo "读取客户端传来的消息"."\n";
            $buf = socket_read($msgsock, 8192);
            $talkback = $buf."\n";
            if(false=== socket_write($msgsock, $talkback)){
                echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
            }else{
                echo "return info msg ku fu duan success"."\n";
            }
            socket_close($msgsock);
        }while (true);
        socket_close($sock);
    } */
    
    public function client(){
        
    }
    
    
    public function dis() {
        $this->display();
    }
}