var net = require('net');
var server = net.createServer();

//Because you’re creating a chat server in which you have to broadcast the user data to everyone, the
//first step is to store all the connections in a central place.
var sockets = [];

server.on('connection', function(socket) {
	
	console.log('got a new connection');
	
	//store
	sockets.push(socket);
	
	socket.on('data', function(data) {
		console.log('got data: ',data.toString());
		
//		if (data.toString().toLowerCase() === 'zigbee'){
//			socket.write('hello!');
//			console.log('output is hello!');
//			return socket.end();
//		}
		if (data.trim().toLowerCase() === 'quit') {
			
			socket.write('Bye bye!');
			
			return socket.end();
		}
		
		socket.write(data);
		
//		socket.end();
		
		sockets.forEach(function(otherSocket) {
			
			if ( otherSocket !== socket ) {
				otherSocket.write(data);
			}
			
		});
	});
	
	//removing close connections
	socket.on('colse', function() {
		console.log('connection closed');
		var index = sockets.indexOf(socket);
		sockets.splice(index, 1);
		
	});
	
});

server.on('error', function(err) {
	console.log('Server error:', err.message);
});
server.on('close', function() {
	console.log('Server closed');
});
server.listen(4002);