var server = require('net').createServer(function(socket) {
	console.log('new connection');
	socket.setEncoding('utf8');
	socket.write("Hello! You can start typing. Type 'quit' to exit.\n");
	socket.on('data', function(data) {
		console.log('got:', data.toString())
		if (data.trim().toLowerCase() === 'quit') {
			
			socket.write('Bye bye!');
			
//			var arr = [ 'zigbee', 'vedio' ];
//			var temp = new Buffer([ 'zigbee', 'vedio' ]);
//			var str = temp.toJSON();
//
//			socket.write(str);
//			console.log(arr);
			// socket.emit(str);
			return socket.end();
		}
//		var zz = socket.write('Very!');
//		socket.write(zz);
	});
	socket.on('end', function() {
		console.log('Client connection ended');
	});
}).listen(4001);

// var server = require('net').createServer();
// var port = 4001;
// server.on('listening', function() {
// console.log('Server is listening on port', port);
// });
// server.on('connection', function(socket) {
// console.log('Server has a new connection');
// socket.end();
// server.close();
// });
// server.on('close', function() {
// console.log('Server is now closed');
// });
// server.on('error', function(err) {
// console.log('Error occurred:', err.message);
//});
//server.listen(port);