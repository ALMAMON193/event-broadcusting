@extends('layouts.app')

@section('content')
    <style>
        .whatsapp-container {
            background: #111b21;
            min-height: 100vh;
            padding: 0;
            margin: 0;
        }

        .chat-wrapper {
            width: 100%;
            height: 100vh;
            margin: 0;
            background: #111b21;
            border-radius: 0;
            box-shadow: none;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            color: white;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            min-height: 60px;
            flex-shrink: 0;
        }

        .chat-header i {
            font-size: 24px;
        }

        .chat-header h4 {
            margin: 0;
            font-weight: 500;
            font-size: 20px;
        }

        .chat-body {
            display: flex;
            flex: 1;
            height: calc(100vh - 80px); /* Subtract header height */
        }

        .chat-sidebar {
            width: 350px;
            background: #202c33;
            border-right: 1px solid #313d45;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #313d45;
            background: #2a3942;
        }

        .sidebar-header h6 {
            color: #e9edef;
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .contacts-list {
            flex: 1;
            overflow-y: auto;
        }

        .contact-item {
            padding: 15px 20px;
            border-bottom: 1px solid #313d45;
            cursor: pointer;
            transition: background-color 0.2s;
            color: #e9edef;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .contact-item:hover {
            background: #2a3942;
        }

        .contact-item.active {
            background: #2a3942;
            border-right: 3px solid #00a884;
        }

        .contact-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 168, 132, 0.3);
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-weight: 500;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .contact-type {
            font-size: 12px;
            color: #8696a0;
            background: #374045;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #0b141a;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: linear-gradient(45deg, #0b141a 0%, #121b22 100%);
            position: relative;
        }

        .chat-messages::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="20" cy="20" r="1" fill="%23ffffff" fill-opacity="0.02"/><circle cx="80" cy="40" r="1" fill="%23ffffff" fill-opacity="0.02"/><circle cx="40" cy="80" r="1" fill="%23ffffff" fill-opacity="0.02"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }

        .message-item {
            margin-bottom: 15px;
            animation: slideIn 0.3s ease-out;
            display: flex;
            flex-direction: column;
            max-width: 70%;
            width: fit-content;
        }

        /* Sent messages (right side) */
        .message-item.sent {
            align-self: flex-end;
            align-items: flex-end;
        }

        .message-item.sent .message-content {
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            color: white;
            border-bottom-right-radius: 5px;
            margin-left: 0;
            margin-right: 10px;
        }

        .message-item.sent .message-sender {
            flex-direction: row-reverse;
            text-align: right;
        }

        .message-item.sent .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Received messages (left side) */
        .message-item.received {
            align-self: flex-start;
            align-items: flex-start;
        }

        .message-item.received .message-content {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: #e9edef;
            border-bottom-left-radius: 5px;
            margin-left: 10px;
            margin-right: 0;
        }

        .message-item.received .message-sender {
            flex-direction: row;
        }

        .message-item.received .message-time {
            color: #8696a0;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-sender {
            color: #00a884;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 168, 132, 0.3);
        }

        .sender-info {
            flex: 1;
        }

        .message-content {
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 15px;
            line-height: 1.4;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            word-wrap: break-word;
        }

        .message-time {
            font-size: 11px;
            margin-top: 5px;
            text-align: right;
        }

        /* Chat messages container alignment */
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: linear-gradient(45deg, #0b141a 0%, #121b22 100%);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .no-messages {
            text-align: center;
            color: #8696a0;
            font-style: italic;
            margin-top: 50px;
        }

        .chat-input-area {
            background: #202c33;
            padding: 20px;
            border-top: 1px solid #313d45;
        }

        .message-form {
            display: flex;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
        }

        .receiver-select {
            min-width: 200px;
        }

        .receiver-select select {
            background: #2a3942;
            border: 1px solid #313d45;
            color: #e9edef;
            border-radius: 25px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .receiver-select select:focus {
            border-color: #00a884;
            box-shadow: 0 0 0 0.2rem rgba(0, 168, 132, 0.25);
            background: #2a3942;
            color: #e9edef;
        }

        .message-input {
            flex: 1;
        }

        .message-input input {
            background: #2a3942;
            border: 1px solid #313d45;
            color: #e9edef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 15px;
            width: 100%;
        }

        .message-input input:focus {
            border-color: #00a884;
            box-shadow: 0 0 0 0.2rem rgba(0, 168, 132, 0.25);
            background: #2a3942;
            color: #e9edef;
        }

        .message-input input::placeholder {
            color: #8696a0;
        }

        .send-btn {
            background: linear-gradient(135deg, #00a884 0%, #008069 100%);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 168, 132, 0.3);
        }

        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 168, 132, 0.4);
            background: linear-gradient(135deg, #00bf9a 0%, #009579 100%);
        }

        .send-btn:active {
            transform: translateY(0);
        }

        .alert {
            margin: 0;
            border-radius: 0;
            border: none;
            flex-shrink: 0;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* Custom scrollbar */
        .chat-messages::-webkit-scrollbar,
        .contacts-list::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track,
        .contacts-list::-webkit-scrollbar-track {
            background: #1e2a32;
        }

        .chat-messages::-webkit-scrollbar-thumb,
        .contacts-list::-webkit-scrollbar-thumb {
            background: #374045;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover,
        .contacts-list::-webkit-scrollbar-thumb:hover {
            background: #8696a0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .chat-body {
                height: calc(100vh - 60px);
            }

            .chat-sidebar {
                width: 280px;
            }

            .message-form {
                flex-direction: column;
                gap: 10px;
            }

            .receiver-select {
                min-width: auto;
                width: 100%;
            }

            .send-btn {
                width: 100%;
                border-radius: 25px;
                height: 45px;
            }

            .message-item {
                max-width: 85%;
            }
        }

        @media (max-width: 576px) {
            .chat-sidebar {
                display: none;
            }

            .chat-body {
                height: calc(100vh - 60px);
            }

            .message-item {
                max-width: 90%;
            }

            .chat-header {
                padding: 12px 15px;
            }

            .chat-header h4 {
                font-size: 18px;
            }
        }

        /* Loading animation for new messages */
        .message-loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #374045;
            border-radius: 50%;
            border-top-color: #00a884;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="whatsapp-container">
        <div class="chat-wrapper">
            <div class="chat-header">
                <i class="fas fa-comments"></i>
                <h4>Chatting System</h4>
            </div>

            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0 ms-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="chat-body">
                {{-- Sidebar with contacts --}}
                <div class="chat-sidebar">
                    <div class="sidebar-header">
                        <h6><i class="fas fa-users me-2"></i>Available Contacts</h6>
                    </div>
                    <div class="contacts-list">
                        @foreach($allReceivers as $receiver)
                            <div class="contact-item"
                                 data-receiver-id="{{ $receiver->id }}"
                                 data-receiver-type="{{ $receiver->type }}">
                                <div class="contact-avatar">
                                    {{ strtoupper(substr($receiver->name, 0, 1)) }}
                                </div>
                                <div class="contact-info">
                                    <div class="contact-name">{{ $receiver->name }}</div>
                                    <span class="contact-type">{{ ucfirst($receiver->type) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Main chat area --}}
                <div class="chat-main">
                    <div id="chat-box" class="chat-messages">
                        @forelse($chats as $chat)
                            @php
                                $currentUserId = auth()->id();
                                $isSent = ($chat->sender_id == $currentUserId);
                            @endphp
                            <div class="message-item {{ $isSent ? 'sent' : 'received' }}">
                                <div class="message-sender">
                                    <div class="message-avatar">
                                        {{ strtoupper(substr($chat->sender_type, 0, 1)) }}
                                    </div>
                                    <div class="sender-info">
                                        {{ ucfirst($chat->sender_type) }} (ID: {{ $chat->sender_id }})
                                    </div>
                                </div>
                                <div class="message-content">
                                    {{ $chat->message }}
                                    <div class="message-time">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $chat->created_at->format('d M Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="no-messages">
                                <i class="fas fa-comments fa-3x mb-3" style="color: #374045;"></i>
                                <p>No messages yet. Start a conversation!</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="chat-input-area">
                        <form method="POST" action="{{ route('chat.store') }}" class="message-form">
                            @csrf

                            {{-- Receiver ID --}}
                            <input type="hidden" name="receiver_type" id="receiver_type">

                            <div class="form-group receiver-select">
                                <select name="receiver_id" id="receiver_id" class="form-control" required>
                                    <option value="">Select Recipient</option>
                                    @foreach($allReceivers as $receiver)
                                        <option value="{{ $receiver->id }}" data-type="{{ $receiver->type }}">
                                            {{ $receiver->name }} ({{ ucfirst($receiver->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group message-input">
                                <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                            </div>

                            <button type="submit" class="send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        const userRole = '{{ auth()->user()->role }}';
        const userId = {{ auth()->id() }};
        const chatBox = document.getElementById('chat-box');

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        scrollToBottom();

        // Handle Echo
        window.Echo.private(`chat.${userRole}.${userId}`)
            .listen('.message.sent', (data) => {
                const isSentByCurrentUser = data.sender_id == userId;
                const messageClass = isSentByCurrentUser ? 'sent' : 'received';

                const messageHtml = `
                <div class="message-item ${messageClass}">
                    <div class="message-sender">
                        <div class="message-avatar">${data.sender_type.charAt(0).toUpperCase()}</div>
                        <div class="sender-info">${capitalize(data.sender_type)} (ID: ${data.sender_id})</div>
                    </div>
                    <div class="message-content">
                        ${data.message}
                        <div class="message-time"><i class="fas fa-clock me-1"></i> ${data.created_at}</div>
                    </div>
                </div>
            `;
                chatBox.insertAdjacentHTML('beforeend', messageHtml);
                scrollToBottom();
            });

        // Utility to capitalize first letter
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        // Click on contact in sidebar
        document.querySelectorAll('.contact-item').forEach(contact => {
            contact.addEventListener('click', function() {
                document.querySelectorAll('.contact-item').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const receiverId = this.dataset.receiverId;
                const receiverType = this.dataset.receiverType;

                document.getElementById('receiver_id').value = receiverId;
                document.getElementById('receiver_type').value = receiverType;

                document.querySelector('input[name="message"]').focus();
            });
        });

        // Change from dropdown — update hidden receiver_type
        document.getElementById('receiver_id').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const receiverType = selectedOption.getAttribute('data-type');
            document.getElementById('receiver_type').value = receiverType;
        });

        // Submit form feedback
        document.querySelector('.message-form').addEventListener('submit', function(e) {
            const messageInput = this.querySelector('input[name="message"]');
            const sendBtn = this.querySelector('.send-btn');

            if (!document.getElementById('receiver_id').value || !document.getElementById('receiver_type').value) {
                alert("Please select a recipient.");
                e.preventDefault();
                return;
            }

            sendBtn.innerHTML = '<div class="spinner-border spinner-border-sm"></div>';
            sendBtn.disabled = true;

            setTimeout(() => {
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                sendBtn.disabled = false;
                messageInput.value = '';
            }, 1000);
        });

        // Send on Enter key
        document.querySelector('input[name="message"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('.message-form').dispatchEvent(new Event('submit'));
            }
        });
    </script>
@endsection
