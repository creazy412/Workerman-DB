
var net = require('net');
var Model = require('./zipgee_model.js');

//var tcp_port = 3159;

var tcp_port = 4000;
var tcp_server = net.createServer();

var Model = new Model();


tcp_server.on('connection', function(socket) {
	 console.log('got a new connection');
	 socket.write("hello! fuck");
	 socket.on('data', function(data) {

	 	console.log('got data:', data.toString());
	 	
	 	if(data.toString() == 'zigbee'){
	 		socket.write('zigbee\n');
	 		console.log('client send zigbee!');
	 		
	 		return socket.end();
	 	}
	 	
	 	//socket.write('Hello From 阿里云'); 
 	});
	 
	 socket.write('rec cool\n');
	 socket.write('bye\n');
	 	
	 socket.end();
});

tcp_server.on('error', function(err) {
 console.log('Server error:', err.message);
});

tcp_server.on('close', function() {
 console.log('Server closed');
});

//tcp_server.emit('pushToWebClient', 'xyz');

tcp_server.listen(tcp_port);



