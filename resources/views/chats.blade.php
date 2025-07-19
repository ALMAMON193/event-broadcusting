@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-comments"></i> Chat System</div>

                    <div class="card-body">
                        {{-- Success message --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Validation errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Chat messages box --}}
                        <div id="chat-box" class="border rounded p-3 mb-4" style="height: 300px; overflow-y: scroll;">
                            @forelse($chats as $chat)
                                <div class="mb-2">
                                    <strong>{{ ucfirst($chat->sender_type) }} (ID: {{ $chat->sender_id }}):</strong>
                                    {{ $chat->message }}
                                    <small class="text-muted d-block">{{ $chat->created_at->format('d M Y h:i A') }}</small>
                                </div>
                            @empty
                                <p class="text-muted">No messages yet.</p>
                            @endforelse
                        </div>

                        {{-- Message form --}}
                        <form method="POST" action="{{ route('chat.store') }}">
                            @csrf

                            {{-- Receiver select --}}
                            <div class="mb-3">
                                <label for="receiver_id" class="form-label">Send To</label>
                                <select name="receiver_id" id="receiver_id" class="form-control" required>
                                    <option value="">-- Select Recipient --</option>
                                    @foreach($allReceivers as $receiver)
                                        <option value="{{ $receiver->id }}">
                                            {{ $receiver->name }} ({{ ucfirst($receiver->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Message input --}}
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-paper-plane"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">


        console.log(`Subscribing to channel: chat.${userRole}.${userId}`);

        window.Echo.private(`chat.${userRole}.${userId}`)
            .listen('.message.sent', (data) => {
                console.log('New message received:', data);

                const messageHtml = `
                <div>
                    <strong>${capitalize(data.sender_type)} (ID: ${data.sender_id}):</strong> ${data.message}
                    <small>${data.created_at}</small>
                </div>
            `;

                chatBox.insertAdjacentHTML('beforeend', messageHtml);
                chatBox.scrollTop = chatBox.scrollHeight;
            });

        // Capitalize utility
        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
@endsection
