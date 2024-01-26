// Conectar-se ao servidor de WebSocket
const socket = io();

// Obter elementos do DOM
const chatMessages = document.getElementById('chatMessages');
const messageInput = document.getElementById('messageInput');
const sendMessageButton = document.getElementById('sendMessageButton');

// Função para exibir uma mensagem de bate-papo
function displayMessage(sender, message) {
  const messageElement = document.createElement('div');
  messageElement.innerHTML = `<strong>${sender}</strong>: ${message}`;
  chatMessages.appendChild(messageElement);
}

// Enviar mensagem quando o botão "Enviar" for clicado
sendMessageButton.addEventListener('click', () => {
  const message = messageInput.value.trim();
  if (message !== '') {
    socket.emit('chatMessage', message);
    messageInput.value = '';
  }
});

// Receber mensagens de bate-papo do servidor
socket.on('chatMessage', ({ sender, message }) => {
  displayMessage(sender, message);
});
