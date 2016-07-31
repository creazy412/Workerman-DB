var dgram = require('dgram');
var net = require('net');
var Model = require('./mifi_model.js');

var upd_port = 7584;
var tcp_port = 3159;

var upd_server = dgram.createSocket('udp4');
var tcp_server = net.createServer();

Model = new Model();

upd_server.on('message', function(message, rinfo) {
	console.log('UDP server got message: %s from %s:%d',message, rinfo.address, rinfo.port);

	var response = check_message(message);

	if( response.error != null ){
		eval(response.function_name)(response.error , function(str){
			
			var msg = new Buffer(str);
			upd_server.send(msg, 0, msg.length, rinfo.port, rinfo.address);	
		
		});
	}else{
		eval(response.function_name)(response.data , function(data_pair , results){
				var json_response = {};

				if( results.errcode != null ){
					//Got An Error To Handle
					json_response = results;
				}else{
					
					if( results != null ){
						var response_datatype_pair = data_pair.split(',');

						json_response.errcode = 0;
						json_response.msg = 'Success';
						json_response.response = response_datatype_pair[0];
						
						if( Array.isArray(results) ){
							var data = [];
							for(var key in results ){
								
								if( typeof results[key] === 'object' ){
									var tmp = {};
									for( var subkey in results[key] ){
											eval('tmp.' + subkey + '="' + results[key][subkey] + '"');
									}

									data.push(tmp);
								}
							}
							if( data.length != 0 ){
								eval( 'json_response.' + response_datatype_pair[1] + '=' + JSON.stringify(data) );
							}
							
						}
					}else{
						json_response.errcode = 101;
						json_response.msg = 'Empty Results';
					}

				}

				var msg = new Buffer(JSON.stringify(json_response));
				upd_server.send(msg, 0, msg.length, rinfo.port, rinfo.address);	
		});
	}
});

upd_server.on('listening', function() {
	 var address = upd_server.address();
});

tcp_server.on('connection', function(socket) {
	 console.log('got a new connection');
	 

	 socket.on('data', function(data) {

	 	console.log('DATA ' + socket.remoteAddress + ': ' + socket.remotePort);
	 	socket.write('Hello From 阿里云');
 	});
});

tcp_server.on('error', function(err) {
 console.log('Server error:', err.message);
});

tcp_server.on('close', function() {
 console.log('Server closed');
});

upd_server.bind(upd_port);
tcp_server.listen(tcp_port);


function check_message(message){
	var function_name = _message = null;

	try{
		var message_string = JSON.parse(message);

		if( message_string.action != null ){

			function_name = 'Model.' + message_string.action;

			if( eval("typeof " + function_name) !== 'function' ){
				function_name = 'Model.getError';
				_message = "Invalid Function Name";
			}

		}else{
			_message = "JSON Request Doesn\'t contain an action parameter";
			function_name = 'Model.getError';
		}
	}catch(e){
		_message = "JSON Format Incorrect ";
		function_name = 'Model.getError';
	}

	return {
		"function_name" : function_name , 
		"error" : _message,
		"data": message_string
	}
}

