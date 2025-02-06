document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const nameInput = document.getElementById('name-input');
    const messageInput = document.getElementById('message-input');

    // Load existing messages
    function loadMessages() {
        const messages = JSON.parse(localStorage.getItem('chatMessages') || '[]');
        chatMessages.innerHTML = messages
            .map(msg => `<p><strong>${msg.name}:</strong> ${msg.text}</p>`)
            .join('');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Save new message
    function saveMessage(name, text) {
        const messages = JSON.parse(localStorage.getItem('chatMessages') || '[]');
        messages.push({ name, text, timestamp: new Date().toISOString() });
        localStorage.setItem('chatMessages', JSON.stringify(messages));
    }

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = nameInput.value.trim();
        const text = messageInput.value.trim();
        
        if (name && text) {
            saveMessage(name, text);
            messageInput.value = '';
            loadMessages();
        }
    });

    // Initial load of messages
    loadMessages();
});
