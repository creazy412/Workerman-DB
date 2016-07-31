var db = require('mysql');

var client = db.createConnection({
	 host: '120.76.42.245',
	 user: 'root',
	 password: 'zmartec552'
});

var Model = function(){};

client.query('USE mifi');

Model.prototype.getleadermifipos = function(data , callback){

	var sql = 'SELECT * FROM device_lastposition WHERE deviceId=(?)';

	qr = client.query(sql , data.mifiid, 
	 function(err, results, fields) {
			 if (err) 
			 {
			 	//throw err;
			 	error.errcode = 101;
			 	error.msg = err.message;
			 	error.response = 'fail';
			 	callback(error);
			 }else{
			 	callback('mifipos,gpspos' , results);
			 }
		     
	   }
	);
}

Model.prototype.registerposition = function(data , callback){
	var sql_position = "INSERT INTO device_position(deviceId , latitude , longitude , altitude , recorded_time) VALUE( (?) ,(?) ,(?) ,(?) ,(?))";
	
	var sql_last_position = 'UPDATE device_lastposition set last_latitude = (?) , last_longitude = (?) , last_altitude = (?) , recorded_time = (?) WHERE deviceId=(?)';
	
	insertRecord = client.query(sql_position , [data.mifiid, data.gpspos.latitude , data.gpspos.longitude , data.gpspos.altitude , data.gpspos.time],
	function(err, results, fields) {
	 		var error={};

			 if (err) 
			 {
			 	error.errcode = 201;
			 	error.msg = err.message;
			 	error.response = 'fail';
			 	callback(error);
			 }
			 else{
			 	callback('CREATE' , results);
			 }   
	   }
	);

	updateRecord = client.query(sql_last_position , [data.gpspos.latitude , data.gpspos.longitude , data.gpspos.altitude , data.gpspos.time , data.mifiid],
	function(err, results, fields) {
			 if (err) 
			 {
			 	error.errcode = 201;
			 	error.msg = err.message;
			 	error.response = 'fail';
			 	callback(error);
			 }
			 else{
				callback('UPDATE' , results);
			 }
	   }
	);
}

Model.prototype.reportposition = function(data , callback){
	Model.prototype.registerposition(data , function(return_string , results){/* Empty CallBack! */});

	var sql = "SELECT last_latitude as latitude , last_longitude as longitude , last_altitude as altitude , recorded_time as time FROM device_lastposition WHERE deviceId=(SELECT deviceId FROM device_group UG WHERE group_id = (SELECT group_id FROM device_group WHERE deviceId=(?)) AND user_type=1 limit 1)";

	 qr = client.query(sql , data.mifiid, 
	 function(err, results, fields) {
	 		 var error={};

			 if (err) 
			 {
			 	//throw err;
			 	error.errcode = 101;
			 	error.msg = err.message;
			 	error.response = 'fail';
			 	callback(error);
			 }else{
			 	callback('leadermifipos,gpspos' , results);
			 }
		     
	   }
	);
}

Model.prototype.getError = function(reason , next){
	var str = "Error On The Eval Function Because : " + reason;
	next(str);
}

module.exports = Model;