const express = require('express');
const http = require('http');

const app = express();
const server = http.createServer(app);

// Add the following line to import and attach Socket.io
const io = require('socket.io')(server);

// Rest of your server-side code...

// Start the server
const port = 3000;
server.listen(port, () => {
  console.log(`Server is listening on port ${port}`);
});