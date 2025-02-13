// Ensure we're in the correct directory
const path = require('path');
const currentDir = path.resolve(__dirname);
process.chdir(currentDir);

console.log('=================================');
console.log('Starting Message Board Server...');
console.log('Working directory:', currentDir);
console.log('=================================');

const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"],
        credentials: true,
        transports: ['websocket', 'polling']
    },
    allowEIO3: true,
    maxHttpBufferSize: 1e8, // 100 MB max file size
    pingTimeout: 60000,
    pingInterval: 25000
});
const cors = require('cors');
const os = require('os');
const mongoose = require('mongoose');

// Set mongoose strictQuery to true
mongoose.set('strictQuery', true);

// Increase payload size limits
app.use(express.json({ limit: '100mb' }));
app.use(express.urlencoded({ extended: true, limit: '100mb' }));

// Update CORS settings
app.use(cors({
    origin: '*',
    methods: ['GET', 'POST'],
    allowedHeaders: ['Content-Type', 'Authorization'],
    credentials: true
}));

// Add these headers for better external access
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.header('Access-Control-Allow-Headers', 'Content-Type');
    next();
});

// Add MongoDB connection
mongoose.connect('mongodb://localhost:27017/messageboard', {
    useNewUrlParser: true,
    useUnifiedTopology: true
});

// Update Message model to include image and isLocal
const Message = mongoose.model('Message', {
    name: String,
    text: String,
    timestamp: String,
    image: String,
    isLocal: Boolean,
    senderId: String  // Add this to track who sent the message
});

console.log('Modules loaded successfully');

// Log the current directory and file locations
console.log('Current directory:', __dirname);
console.log('Index file location:', path.join(__dirname, 'index.html'));

// Get local IP address
const getLocalIP = () => {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return '127.0.0.1';
};

// Serve static files from current directory
app.use(express.static(__dirname));

// More specific route for the root path
app.get('/', (req, res) => res.sendFile(path.join(__dirname, 'index.html')));

// Add basic error handling
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send('Something broke!');
});

// Add users tracking
const onlineUsers = new Set();

// Update isAdmin check function
const isAdminConnection = (socket) => {
    return socket.handshake.headers.host.includes('localhost');
};

// Add at the top with other globals
let currentHost = null;

// Socket connection handling
io.on('connection', async (socket) => {
    const isAdmin = isAdminConnection(socket);
    console.log('A user connected');
    
    socket.on('user-join', (userName) => {
        const isAdmin = isAdminConnection(socket);
        
        // Handle host replacement
        if (isAdmin) {
            if (currentHost) {
                // Disconnect previous host
                const oldHost = Array.from(io.sockets.sockets.values())
                    .find(s => s.userName === 'Host' && s.id !== socket.id);
                if (oldHost) {
                    oldHost.disconnect();
                }
            }
            currentHost = socket.id;
            socket.userName = 'Host';
        } else {
            socket.userName = userName;
        }
        
        socket.isAdmin = isAdmin;
        
        // Update online users list
        const existingUser = Array.from(onlineUsers)
            .find(u => u.name === socket.userName);
        if (existingUser) {
            onlineUsers.delete(existingUser);
        }
        
        onlineUsers.add({ 
            name: socket.userName, 
            isAdmin: isAdmin,
            id: socket.id
        });

        io.emit('users-online', Array.from(onlineUsers).map(user => ({
            name: user.name,
            isAdmin: user.isAdmin
        })));

        io.emit('message', {
            name: 'System',
            text: `${socket.userName} has joined the chat`,
            timestamp: new Date().toISOString(),
            isSystem: true
        });
    });

    try {
        // Load last 50 messages
        const messages = await Message.find()
            .sort({ _id: -1 })
            .limit(50);
        socket.emit('load-messages', messages.reverse());
        
        socket.on('new-message', async (message) => {
            try {
                if (message.image && message.image.length > 1e8) {
                    socket.emit('error', 'Image too large (max 100MB)');
                    return;
                }
                
                const dbMessage = new Message({
                    ...message,
                    senderId: socket.id,
                    isSystem: message.name === 'System'  // Add isSystem flag
                });
                await dbMessage.save();
                io.emit('message', {
                    ...message,
                    _id: dbMessage._id,
                    senderId: socket.id,
                    isSystem: message.name === 'System'
                });
            } catch (error) {
                console.error('Error saving message:', error);
                socket.emit('error', 'Failed to save message');
            }
        });

        // Update delete message handler
        socket.on('delete-message', async (messageId) => {
            try {
                const message = await Message.findById(messageId);
                if (message && (isAdmin || message.senderId === socket.id)) {
                    await Message.findByIdAndDelete(messageId);
                    io.emit('message-deleted', messageId);
                    console.log('Message deleted:', messageId);
                }
            } catch (error) {
                console.error('Error deleting message:', error);
                socket.emit('error', 'Failed to delete message');
            }
        });
    } catch (error) {
        console.error('Error loading messages:', error);
    }

    socket.on('disconnect', () => {
        if (socket.userName) {
            const user = Array.from(onlineUsers)
                .find(u => u.id === socket.id);
            if (user) {
                onlineUsers.delete(user);
            }
            
            if (socket.id === currentHost) {
                currentHost = null;
            }
            
            io.emit('users-online', Array.from(onlineUsers).map(user => ({
                name: user.name,
                isAdmin: user.isAdmin
            })));
            
            io.emit('message', {
                name: 'System',
                text: `${socket.userName} has left the chat`,
                timestamp: new Date().toISOString(),
                isSystem: true
            });
        }
        console.log('User disconnected');
    });
});

const PORT = process.env.PORT || 3001; // Changed port to 3001
const HOST = '0.0.0.0'; // Listen on all network interfaces

// Immediate feedback
console.log(`Attempting to start server on ${HOST}:${PORT}`);

try {
    http.listen(PORT, HOST, async (err) => {
        if (err) {
            console.error('Server failed to start:', err);
            process.exit(1);
        }
        const localIP = getLocalIP();
        console.log('\n=================================');
        console.log(`✅ Server is running!`);
        console.log(`📡 Local access: http://localhost:${PORT}`);
        console.log(`🌐 Network access: http://${localIP}:${PORT}`);
        console.log(`\n🔄 To create a new public URL:`);
        console.log(`1. Open a new terminal`);
        console.log(`2. Run: ngrok http ${PORT}`);
        console.log(`3. Copy the new 'https://...' URL`);
        console.log(`Note: URL changes each time you restart ngrok`);
        console.log('=================================\n');
    });
} catch (error) {
    console.error('Critical server error:', error);
    process.exit(1);
}

// Improve error handling
process.on('uncaughtException', (err) => {
    console.error('Uncaught Exception:', err);
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
});

// Handle server errors
http.on('error', (error) => {
    if (error.code === 'EADDRINUSE') {
        console.error(`Port ${PORT} is already in use. Please try a different port.`);
    } else {
        console.error('Server error:', error);
    }
});
