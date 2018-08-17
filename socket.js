
var server =  require('http').Server();

var io = require('socket.io')(server);

/// for redis
var Redis = require('ioredis');

var redis = new Redis();

var rooms = [];

redis.psubscribe('*',function(err, count) {});

redis.on('pmessage',function(subscribe,channel,message){
    message = JSON.parse(message);
    io.emit(channel,message);
});


//set server to listen on port 3000
server.listen(3000);


function findOrAddRoom(channel){
    
    if(!rooms.indexOf(channel) <0){
        rooms.push(channel);
    }
}