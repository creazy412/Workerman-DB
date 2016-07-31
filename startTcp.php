<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
// use DBCONNECT;
require_once './Workerman/Autoloader.php';

$worker = new Worker('tcp://0.0.0.0:8899');
//worker实例1有4个进程，进程id编号将分别为0、1、2、3
$worker1 = new Worker('tcp://0.0.0.0:8989');

$worker2 = new Worker('tcp://0.0.0.0:8988');


//设置启动4个进程
$worker1->count = 4;

//设置启动2个进程
$worker->count = 2;


/**
 * 逻辑处理
 * @author Administrator
 *
 */
class TcpLogicProcess{
    
    //修改mifi配置
    public function changeWifiConfig($data){
        $db = new DBCONNECT();
        
        $decode = json_decode($data, TRUE);
        $ssid = $decode['ssid'];
        $encryption = $decode['encryption'];
        $passwd = $decode['passwd'];
        $mifi_id = $decode['mifi_id'];
        
        $sql = " UPDATE mf_device SET `ssid`='$ssid',`encryption`='$encryption',`passwd`='$passwd' WHERE sn = '$mifi_id' ";
        
        $data = $db->runQuery($sql);
        
        if ( $data ){
            return json_encode(array('errcode'=>0, 'msg'=>'success', 'response'=>'leadermifipos', 'gpspos'=>'SUCCESS'));
        }else {
            return json_encode(array('errcode'=>0, 'msg'=>'success', 'response'=>'leadermifipos', 'gpspos'=>'FAIL'));
        }
        
    }
    
    //获取mifi配置
    public function getWifiConfig($mifi_id){
        
        $db = new DBCONNECT();
        
        $sql = " SELECT ssid,encryption,passwd FROM mf_device WHERE sn = '$mifi_id' ";
        
        $data = $db->getQuery($sql);
        
        return json_encode(array('errcode'=>0, 'msg'=>'success', 'response'=>'leadermifipos', 'gpspos'=>$data));
    }
    
    //得到指定mifi位置
    public function getSpecialMifiPos($mifi_id){
        
        
        return TRUE;
    }
    
    
    
}














// 每个进程启动后打印当前进程id编号即 $worker1->id
$worker1->onWorkerStart = function ($worker1){
    
    //只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
    if ( $worker1->id === 0 ){
        
        Timer::add(500, function (){
            
            echo "4个worker进程，只在0号进程设置定时器\n";
            
        });
        
    }
    
};


$worker2->onConnect = function ($connection){
    
    echo $connection->id = 'mifiid';
    
    $connection->send($connection->id);
    
};

$worker->onWorkerStart = function($worker){

    // 定时，每10秒一次
    Timer::add(100, function()use($worker)
    {
        // 遍历当前进程所有的客户端连接，发送当前服务器的时间
        foreach($worker->connections as $connection)
        {
            $connection->send(time());
        }
    });

};

// 执行reload后告诉所有客户端服务端执行了reload
$worker->onWorkerReload = function($worker)
{
    foreach($worker->connections as $connection)
    {
        var_dump($connection);
        $connection->send('worker reloading');
    }
};

//
$worker->onConnect = function ($connection){
    
    echo $connection->id;
    
};

//本次要发送的数据仍然会被放入发送缓冲区
$worker->onBufferFull = function (){
    echo "缓冲区已满，不能再次发送数据";
};

$worker->onMessage = function($connection, $data)
{
    $reciveTcpData = json_decode($data, TRUE);
    
    if ( $reciveTcpData['action'] == 'change_wifi_config' ){
        
        $mifi_id = $reciveTcpData['mifi_id'];
        $logic = new TcpLogicProcess();
        $responseTcpData = $logic->changeWifiConfig($data);
        
    }elseif ( $reciveTcpData['action'] == 'get_wifi_config' ){
        
        $mifi_id = $reciveTcpData['mifi_id'];
        $logic = new TcpLogicProcess();
        $responseTcpData = $logic->getWifiConfig($mifi_id);
        
    }elseif ( $reciveTcpData['action'] == 'get_special_mifi_pos' ){
        
        $mifi_id = $reciveTcpData['mifi_id'];
        $logic = new TcpLogicProcess();
        $responseTcpData = $logic->getSpecialMifiPos($mifi_id);
        
    }
    
    // send 时会自动调用$connection->protocol::encode()，打包数据后再发送
//     $connection->send("hello");
    $connection->send($responseTcpData);
};
$worker->onBufferDrain = function($connection)
{
    echo "buffer drain and continue send\n";
};
//
Worker::runAll();