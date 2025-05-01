@extends('layouts.app')

@section('title', 'Chat')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <style>
        .chat-container {
            background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
        }

        .emoji-container {
            position: relative;
            transition: all 0.3s ease;
        }

        .emoji-container em-emoji-picker {
            position: absolute;
            bottom: 0px;
            left: 20px;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 1rem;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .emoji-container em-emoji-picker.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .message-bubble {
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .message-bubble.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .message-bubble-sent {
            background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
        }

        .message-bubble-received {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .message-timestamp {
            font-size: 0.75rem;
            opacity: 0.8;
            transition: opacity 0.2s ease;
        }

        .message-bubble:hover .message-timestamp {
            opacity: 1;
        }

        .chat-input {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }

        .chat-button {
            transition: all 0.2s ease;
        }

        .chat-button:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }

        .sidebar-chat {
            transition: all 0.2s ease;
        }

        .sidebar-chat:hover {
            transform: translateX(4px);
        }

        .active-chat {
            background: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 4px 8px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            margin-top: 8px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            background: #4F46E5;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) {
            animation-delay: 200ms;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 300ms;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 400ms;
        }
        html {
            scroll-behavior: smooth;
        }

        @keyframes typing {

            0%,
            50%,
            100% {
                transform: translateY(0);
            }

            25% {
                transform: translateY(-4px);
            }
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(8px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.visible {
            opacity: 1;
        }

        .modal img {
            max-width: 90%;
            max-height: 90vh;
            object-fit: contain;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.visible img {
            transform: scale(1);
        }

        .modal-button {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            border: none;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            margin: 0 10px;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .modal-button:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #218838;
        }
    </style>

    {{-- <div class="container mx-auto px-4 py-6 h-screen chat-container"> --}}
        <div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-2xl overflow-hidden h-[calc(100vh-6rem)]">
            <div class="w-full md:w-1/3 lg:w-1/4 bg-gradient-to-br from-indigo-600 to-indigo-900 p-4">
                <h2 class="text-white text-xl font-semibold mb-6 px-2 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Messages
                </h2>
                <div class="overflow-y-auto h-[calc(100%-4rem)]">
                    <ul class="space-y-2">
                        @foreach ($chats as $chatItem)
                            <li>
                                <a href="{{ route($userType . '.chats.show', $chatItem->id) }}"
                                    class="sidebar-chat flex items-center text-white p-3 rounded-xl transition-all duration-200 hover:bg-white/10
                                      {{ $chat->id === $chatItem->id ? 'active-chat' : '' }}">
                                    <div class="relative">
                                        <img src="{{ asset('profile_image/' . ($userType === 'customer' ? $chatItem->pharmacy->profile_image : $chatItem->customer->profile_image)) }}"
                                            alt="Avatar"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-white/20">
                                        <div
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white">
                                        </div>
                                    </div>
                                    <div class="ml-3 overflow-hidden">
                                        <span class="font-medium block truncate">
                                            {{ $userType === 'customer' ? $chatItem->pharmacy->name : $chatItem->customer->name }}
                                        </span>
                                        <span class="text-sm text-indigo-200 truncate block">
                                            {{ $chatItem->lastMessage->message ?? 'No messages yet' }}
                                        </span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex-1 flex flex-col bg-gray-50">
                <div class="p-4 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <img src="{{ asset('profile_image/' . ($userType === 'customer' ? $chat->pharmacy->profile_image : $chat->customer->profile_image)) }}"
                                    alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                <div
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-white">
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $userType === 'customer' ? $chat->pharmacy->name : $chat->customer->name }}
                                </h3>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="mr-2">Active Now</span>
                                    <div class="typing-indicator">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
                    @foreach ($messages as $message)
                        <div
                            class="flex {{ $message->sender_id == Auth::id() && get_class(Auth::user()) == $message->sender_type ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="message-bubble max-w-[70%] rounded-2xl px-4 py-3
                                {{ $message->sender_id == Auth::id() && get_class(Auth::user()) == $message->sender_type
                                    ? 'message-bubble-sent text-white'
                                    : 'message-bubble-received text-gray-900' }}">

                                <p class="text-sm">{{ $message->message }}</p>

                                @if (isset($message->file_path))
                                    <img src="{{ asset('storage/' . $message->file_path) }}"
                                        class="mt-2 rounded-lg max-w-xs cursor-pointer clickable-image hover:opacity-90 transition-opacity" />
                                @endif

                                @if (isset($message->audio))
                                    <audio controls src="{{ asset('storage/' . $message->audio) }}"
                                        class="mt-2 w-full"></audio>
                                @endif

                                @if ($message->payment_button && get_class(Auth::user()) === 'App\\Models\\Customer')
                                    <a href="{{ route('customer.pay', [
                                        'chatId' => $chat->id,
                                        'messageId' => $message->id,
                                    ]) }}?total={{ number_format($message->total, 2, '.', '') }}"
                                        class="mt-3 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded text-sm">
                                        Pay Now - Rs.{{ number_format($message->total, 2) }}
                                    </a>
                                @endif

                                <span class="message-timestamp block mt-1">
                                    {{ $message->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route($userType . '.chats.storeMessage', $chat->id) }}"
                        enctype="multipart/form-data" id="chat-form" class="space-y-4">
                        @csrf
                        <div id="image-preview" class="flex gap-2"></div>
                        <div class="emoji-container" id="emoji-container"></div>
                        <div class="chat-input flex items-center gap-2 rounded-full p-2">
                            <button type="button" id="emoji-button" class="chat-button p-2 hover:bg-gray-100 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" width="24" height="24" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" />
                                    <circle cx="12" cy="12" r="9" />
                                    <line x1="9" y1="10" x2="9.01" y2="10" />
                                    <line x1="15" y1="10" x2="15.01" y2="10" />
                                    <path d="M9.5 15a3.5 3.5 0 0 0 5 0" />
                                </svg>
                            </button>
                            @if ($userType === 'pharmacy')
                                <button type="button" id="offer-button"
                                    class="chat-button p-2 hover:bg-gray-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 4H15C16.1 4 17 4.9 17 6V20L15 19L13 20L11 19L9 20L7 19L5 20V6C5 4.9 5.9 4 7 4H9Z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9H16" />

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 13H14" />
                                    </svg>
                                </button>
                            @endif


                            <input type="file" name="file" id="file-input" class="hidden" accept="image/*">
                            <label for="file-input" class="chat-button p-2 hover:bg-gray-100 rounded-full cursor-pointer">
                                <svg class="h-6 w-6 text-indigo-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                                </svg>
                            </label>

                            <input type="text" id="message-input" name="message" placeholder="Type a message"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-gray-900 placeholder-gray-500">

                            <input type="hidden" name="audio" id="audio-input">

                            <button type="submit"
                                class="chat-button p-2 bg-indigo-600 hover:bg-indigo-700 rounded-full transition-colors">
                                <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13" />
                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                </svg>
                            </button>

                            <button type="button" id="voice-button"
                                class="chat-button p-2 hover:bg-gray-100 rounded-full">
                                <svg class="h-6 w-6 text-indigo-600" width="24" height="24" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" />
                                    <rect x="9" y="2" width="6" height="11" rx="3" />
                                    <path d="M5 10a7 7 0 0 0 14 0" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div id="offer-modal" class="modal">
                        <div class="bg-white rounded-xl p-6 w-full max-w-xl mx-auto mt-20">
                            <h3 class="text-xl font-semibold mb-4">Medicine Bill</h3>

                            <form id="offer-form">
                                <div id="medicine-items" class="space-y-4 max-h-64 overflow-y-auto">
                                </div>

                                <button type="button" onclick="addMedicineRow()"
                                    class="text-sm text-blue-600 hover:underline my-2">+ Add another medicine</button>

                                <div class="font-semibold text-lg mt-4">
                                    Total: Rs.<span id="offer-total">0.00</span>
                                </div>

                                <div class="flex justify-end space-x-3 mt-6">
                                    <button type="button" onclick="closeOfferModal()"
                                        class="modal-button">Cancel</button>
                                    <button type="submit" class="modal-button">Send Bill</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="image-modal" class="modal">
        <span class="close" id="close-modal">&times;</span>
        <img id="modal-image" src="" alt="Image Preview">
        <div class="fixed bottom-8 left-0 right-0 flex justify-center space-x-4">
            <button class="modal-button" id="prev-button">Previous</button>
            <button class="modal-button" id="next-button">Next</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <script>
        const pickerOptions = {
            onEmojiSelect: addEmoji,
            theme: 'light',
            set: 'apple'
        };
        const picker = new EmojiMart.Picker(pickerOptions);

        document.getElementById('emoji-button').addEventListener('click', () => {
            const emojiContainer = document.getElementById('emoji-container');
            if (emojiContainer.contains(picker)) {
                picker.classList.remove('visible');
                setTimeout(() => {
                    emojiContainer.removeChild(picker);
                }, 300);
            } else {
                emojiContainer.appendChild(picker);
                setTimeout(() => {
                    picker.classList.add('visible');
                }, 10);
            }
        });

        function addEmoji(emoji) {
            const messageInput = document.getElementById('message-input');
            messageInput.value += emoji.native;
            messageInput.focus();
        }

        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewElement = document.createElement('div');
                    previewElement.className = 'relative inline-block animate-fade-in';
                    previewElement.innerHTML = `
                    <div class="group relative">
                        <img src="${e.target.result}" class="h-20 w-20 object-cover rounded-lg shadow-lg transition-transform transform hover:scale-105">
                        <button type="button" onclick="removePreview(this.parentElement.parentElement)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5
                                       hover:bg-red-600 transition-all transform hover:scale-110 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>`;
                    previewContainer.appendChild(previewElement);
                };
                reader.readAsDataURL(file);
            }
        });

        function removePreview(element) {
            element.classList.add('animate-fade-out');
            setTimeout(() => {
                element.remove();
            }, 300);
        }

        function animateMessages() {
            const messages = document.querySelectorAll('.message-bubble:not(.visible)');
            messages.forEach((message, index) => {
                setTimeout(() => {
                    message.classList.add('visible');
                }, index * 100);
            });
        }

        const imageUrls = [];
        let currentIndex = 0;

        document.querySelectorAll('.clickable-image').forEach((image, index) => {
            imageUrls.push(image.src);
            image.addEventListener('click', function() {
                openModal(index);
            });
        });

        function openModal(index) {
            const modal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');
            currentIndex = index;
            modalImage.src = imageUrls[currentIndex];
            modal.style.display = "flex";
            setTimeout(() => {
                modal.classList.add('visible');
            }, 10);
        }

        document.getElementById('close-modal').addEventListener('click', closeModal);

        function closeModal() {
            const modal = document.getElementById('image-modal');
            modal.classList.remove('visible');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }

        document.getElementById('prev-button').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + imageUrls.length) % imageUrls.length;
            document.getElementById('modal-image').src = imageUrls[currentIndex];
        });

        document.getElementById('next-button').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % imageUrls.length;
            document.getElementById('modal-image').src = imageUrls[currentIndex];
        });

        let mediaRecorder;
        let audioChunks = [];
        const voiceButton = document.getElementById('voice-button');

        voiceButton.addEventListener('click', toggleRecording);

        async function toggleRecording() {
            if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(audioChunks, {
                            type: 'audio/wav'
                        });
                        const formData = new FormData();
                        formData.append('audio', audioBlob);

                        try {
                            const response = await fetch('/upload-audio', {
                                method: 'POST',
                                body: formData
                            });
                            const data = await response.json();
                            document.getElementById('audio-input').value = data.path;
                        } catch (error) {
                            console.error('Error uploading audio:', error);
                        }

                        audioChunks = [];
                    };

                    mediaRecorder.start();
                    voiceButton.classList.add('bg-red-500');
                    voiceButton.querySelector('svg').classList.add('text-white');
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                }
            } else {
                mediaRecorder.stop();
                voiceButton.classList.remove('bg-red-500');
                voiceButton.querySelector('svg').classList.remove('text-white');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            animateMessages();
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });

        const chatForm = document.getElementById('chat-form');
        chatForm.addEventListener('submit', () => {
            setTimeout(() => {
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        });
        const chatId = "{{ $chat->id }}";

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('offer-button').addEventListener('click', openOfferModal);
            document.getElementById('offer-form').addEventListener('submit', handleOfferSubmit);

            addMedicineRow();
        });

        function openOfferModal() {
            document.getElementById('offer-modal').style.display = 'block';
            setTimeout(() => document.getElementById('offer-modal').classList.add('visible'), 10);
        }

        function closeOfferModal() {
            document.getElementById('offer-modal').classList.remove('visible');
            setTimeout(() => {
                document.getElementById('offer-modal').style.display = 'none';
                document.getElementById('medicine-items').innerHTML = '';
                addMedicineRow();
                document.getElementById('offer-total').textContent = '0.00';
            }, 300);
        }

        function addMedicineRow() {
            const container = document.getElementById('medicine-items');

            const row = document.createElement('div');
            row.classList.add('medicine-row', 'flex', 'space-x-2', 'items-end');

            row.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Medicine Name</label>
                <input type="text" name="medicines[][name]" required class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium mb-1">Price</label>
                <input type="number" name="medicines[][price]" step="0.01" required class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <div class="w-28">
                <label class="block text-sm font-medium mb-1">Qty</label>
                <input type="number" name="medicines[][quantity]" required class="w-full px-3 py-2 border rounded-lg" />
            </div>
            <button type="button" onclick="removeMedicineRow(this)" class="text-red-600 text-sm hover:underline">Remove</button>
        `;

            container.appendChild(row);

            row.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', calculateTotal);
            });
        }

        function removeMedicineRow(button) {
            const row = button.closest('.medicine-row');
            row.remove();
            calculateTotal();
        }

        function calculateTotal() {
            const rows = document.querySelectorAll('.medicine-row');
            let total = 0;

            rows.forEach(row => {
                const price = parseFloat(row.querySelector('[name$="[price]"]').value) || 0;
                const qty = parseFloat(row.querySelector('[name$="[quantity]"]').value) || 0;
                total += price * qty;
            });

            document.getElementById('offer-total').textContent = total.toFixed(2);
        }

        async function handleOfferSubmit(e) {
            e.preventDefault();
            console.log('Submitting offer...');
            const rows = document.querySelectorAll('.medicine-row');
            const medicines = [];

            rows.forEach(row => {
                const name = row.querySelector('[name$="[name]"]').value;
                const price = parseFloat(row.querySelector('[name$="[price]"]').value);
                const quantity = parseInt(row.querySelector('[name$="[quantity]"]').value);

                if (name && price && quantity) {
                    medicines.push({
                        name,
                        price,
                        quantity
                    });
                }
            });

            const offerData = {
                medicines,
                total: document.getElementById('offer-total').textContent
            };

            try {
                const response = await fetch(`/pharmacy/chats/${chatId}/send-offer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(offerData)
                });

                const data = await response.json();

                if (response.ok && data.message === 'Offer sent successfully!') {
                    const messageContainer = document.querySelector('.chat-container');
                    const newMessage = document.createElement('div');
                    newMessage.classList.add('message');

                    let medicineListHtml = offerData.medicines.map(
                        med => `${med.name} ($${med.price} × ${med.quantity})`
                    ).join(', ');

                    newMessage.innerHTML = `
                    <strong>Pharmacy:</strong> Offer: ${medicineListHtml} | Total: $${offerData.total}
                `;

                    if (data.payment_button) {
                        const paymentButton = document.createElement('button');
                        paymentButton.textContent = 'Proceed to Payment';
                        paymentButton.classList.add('payment-button');
                        paymentButton.addEventListener('click', () => {
                            window.location.href = `/chats/${chatId}/message/${data.message_id}/pay`;
                        });
                        newMessage.appendChild(paymentButton);
                    }

                    messageContainer.appendChild(newMessage);
                    messageContainer.scrollTop = messageContainer.scrollHeight;

                    closeOfferModal();
                }
            } catch (error) {
                console.error('Error sending offer:', error);
            }
        }

        function openPaymentModal(offer) {
            const paymentDetails = document.getElementById('payment-details');
            paymentDetails.innerHTML = `
        <div class="offer-details">
            <div><span>Medicine:</span><span>${offer.name}</span></div>
            <div><span>Price per Unit:</span><span>$${offer.price}</span></div>
            <div><span>Quantity:</span><span>${offer.quantity}</span></div>
            <div class="pt-4 border-t mt-4">
                <span>Total Amount:</span>
                <span class="text-indigo-600">$${offer.total}</span>
            </div>
        </div>
    `;

            document.getElementById('payment-modal').style.display = 'block';
            setTimeout(() => document.getElementById('payment-modal').classList.add('visible'), 10);
        }

        function closePaymentModal() {
            document.getElementById('payment-modal').classList.remove('visible');
            setTimeout(() => document.getElementById('payment-modal').style.display = 'none', 300);
        }

        document.getElementById('confirm-payment').addEventListener('click', async function() {
            alert('Payment processed successfully!');
            closePaymentModal();
        });
    </script>
@endsection
