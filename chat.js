// Initialize Firebase (replace with your config)
const firebaseConfig = {
    apiKey: "your-api-key",
    authDomain: "your-auth-domain",
    databaseURL: "your-database-url",
    projectId: "your-project-id",
    storageBucket: "your-storage-bucket",
    messagingSenderId: "your-messaging-sender-id",
    appId: "your-app-id"
};

firebase.initializeApp(firebaseConfig);
const database = firebase.database();
const messagesRef = database.ref('messages');

document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const nameInput = document.getElementById('name-input');
    const messageInput = document.getElementById('message-input');

    // Listen for new messages
    messagesRef.on('value', (snapshot) => {
        chatMessages.innerHTML = '';
        snapshot.forEach((childSnapshot) => {
            const message = childSnapshot.val();
            const messageElement = document.createElement('p');
            messageElement.innerHTML = `<strong>${message.name}:</strong> ${message.text}`;
            chatMessages.appendChild(messageElement);
        });
        chatMessages.scrollTop = chatMessages.scrollHeight;
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = nameInput.value.trim();
        const text = messageInput.value.trim();
        
        if (name && text) {
            // Push new message to Firebase
            messagesRef.push({
                name: name,
                text: text,
                timestamp: firebase.database.ServerValue.TIMESTAMP
            });
            messageInput.value = '';
        }
    });
});
