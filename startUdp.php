<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
//use DBCONNECT;
require_once './Workerman/Autoloader.php';
require_once './DB.php';


//处理逻辑、数据
class UdpLogicProcess{
    
    
    //交换获得导游的位置信息
    public function reportPosition($mifi_id){
        
        $DB = new DBCONNECT;
        
        
        $sql = " SELECT di.`gps_altitude`,di.`gps_latitude`,di.`gps_longtitude`,di.`flag`,g.`radius` FROM mf_group g,mf_group_members m,mf_device_info di WHERE g.id = m.`group_id` AND di.`device_id` = m.`member_id` AND m.`member_id` = '$mifi_id' ";
        
        $data = $DB->getQuery($sql); // This will run the SQL statment and return and associative array.
        
        return json_encode(array('errcode'=>0, 'msg'=>'success', 'response'=>'leadermifipos', 'gpspons'=>$data));
        
        
    }
    
    //
    public function getLeaderMifiPos(){
        
        return TRUE;
        
        
    }
    
    
}





$worker = new Worker('udp://0.0.0.0:8889');

$worker->onWorkerStart = function($worker){
    
    // 定时，每10秒一次
    Timer::add(10, function()use($worker)
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
        $connection->send('worker reloading');
    }
};

//本次要发送的数据仍然会被放入发送缓冲区
$worker->onBufferFull = function (){
    echo "缓冲区已满，不能再次发送数据";
};
$worker->onBufferDrain = function($connection)
{
    echo "buffer drain and continue send\n";
};

$worker->onMessage = function($connection, $data)
{
    $reciveData = json_decode($data, TRUE);
    
    // send 时会自动调用$connection->protocol::encode()，打包数据后再发送
//     print_r(json_decode($data, TRUE));

    if ( $reciveData['action'] == 'reportposition' ){//获取导游、团长的位置信息
        $mifi_id = $reciveData['mifiid'];
        
        
        $aa = new UdpLogicProcess();
        $reponseData = $aa->reportPosition($mifi_id);
        
    }elseif ( $reciveData['action'] == 'getleadermifipos' ){
        
        $reponseData = true;
        
    }
    
    $connection->send($reponseData);
};

// 运行worker
Worker::runAll();