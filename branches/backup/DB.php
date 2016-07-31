<?php
class DBCONNECT
{
	/*** mysql hostname ***/
	private $hostname = '120.76.42.245'; // Put your host name here
	/*** mysql username ***/
	private $username = 'root'; // Put your MySQL User name here
	/*** mysql password ***/
	private $password = 'zmartec20160701'; // Put Your MySQL Password here
	/*** mysql password ***/
	private $dbName = 'mifi'; // Put Your MySQL Database name here
	/*** database resource ***/
	public $dbh = NULL; // Database handler
	public function __construct() // Default Constructor
	{
		try
		{
			$this->dbh = new PDO("mysql:host=$this->hostname;dbname=$this->dbName", $this->username, $this->password);
			/*** echo a message saying we have connected ***/
			//echo 'Connected to database'; // Test with this string
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
	}
	public function __destruct()
	{
		$this->dbh = NULL; // Setting the handler to NULL closes the connection propperly
	}
	public function runQuery($sql)
	{
		try
		{
			//echo $sql;
			$count = $this->dbh->exec($sql) or print_r($this->dbh->errorInfo());
		}
		catch(PDOException $e)
		{
			echo __LINE__.$e->getMessage();
		}
	}
	public function getQuery($sql)
	{
// 		$stmt = $this->dbh->prepare($sql);
	    
		$stmt = $this->dbh->query($sql);
		
		
		
// 		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		foreach ($stmt as $k=>$v){
// 		    $update_time = $v['update_time'];
// 		    $gps_latitude = $v['gps_latitude'];
// 		    $gps_longtitude = $v['gps_longtitude'];
// 		    $gps_altitude = $v['gps_altitude'];
		    
		    $stmt = $v;
		}
		
// 		$stmt = $this->dbh->fetchAll();

// 		$stmt = $stmt->fetchAll();
	    if( $this->dbh->errorCode() != '00000' ){
	        file_put_contents('/tmp/res_test.txt', json_encode($this->dbh->errorInfo()));
	    }
// 		return array('Latitude'=>$gps_latitude, 'Longtitude'=>$gps_longtitude, 'gps_altitude'=>$gps_altitude, 'time'=>$update_time); // Returns an associative array that can be diectly accessed or looped through with While or Foreach

	    return $stmt;
	}
}
?>