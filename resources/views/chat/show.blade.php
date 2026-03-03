<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex h-[600px]">
                <!-- Sidebar -->
                <div class="w-1/3 border-r border-gray-200 flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800">Messages</h2>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto">
                        <!-- Contacts List -->
                        <div class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacts</div>
                        @foreach($users as $u)
                            <a href="{{ route('chat.start', $u) }}" class="flex items-center p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 {{ isset($group) && !$group->is_group && $group->users->contains($u) ? 'bg-indigo-50 border-r-4 border-indigo-500' : '' }}">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm mr-3 shadow-sm">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline">
                                        <span class="font-medium text-gray-900 truncate">{{ $u->name }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <!-- Groups List -->
                        <div class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Groups</div>
                        @forelse($groups->where('is_group', true) as $g)
                            <a href="{{ route('chat.show', $g) }}" class="flex items-center p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 {{ isset($group) && $group->id === $g->id ? 'bg-indigo-50 border-r-4 border-indigo-500' : '' }}">
                                <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm mr-3 shadow-sm">
                                    {{ substr($g->displayName, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline">
                                        <span class="font-medium text-gray-900 truncate">{{ $g->displayName }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-sm text-gray-400 italic text-center">No groups yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Window -->
                <div class="flex-1 flex flex-col bg-slate-50 relative">
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 bg-white flex items-center justify-between shadow-sm z-10">
                        <div class="flex items-center">
                            @php
                                $colorClass = $group->is_group ? 'bg-emerald-500' : 'bg-indigo-500';
                            @endphp
                            <div class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center text-white font-bold text-sm mr-3">
                                {{ substr($group->displayName, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $group->displayName }}</h3>
                                <p class="text-xs text-gray-500">{{ $group->is_group ? 'Group Chat' : 'Private Message' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages List -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="message-container">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] group">
                                    <div class="flex items-center mb-1 space-x-2 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                        <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-tight">{{ $message->sender->name }}</span>
                                        <span class="text-[10px] text-gray-300">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                    <div class="px-4 py-2 rounded-2xl shadow-sm relative {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none' }}">
                                        @if($message->type === 'text')
                                            <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                                        @elseif($message->type === 'media')
                                            @if($message->content)
                                                <p class="text-sm mb-2 opacity-90 italic border-l-2 border-white/30 pl-2">{{ $message->content }}</p>
                                            @endif
                                            <div class="grid grid-cols-1 gap-2">
                                                @foreach($message->media as $media)
                                                    @if(Str::startsWith($media->file_type, 'image/'))
                                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="rounded-lg max-h-64 object-cover cursor-pointer hover:opacity-95 transition-opacity" alt="Image">
                                                    @else
                                                        <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="flex items-center p-2 bg-black/10 rounded-lg text-xs hover:bg-black/20 transition-colors">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            Attachment
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 bg-white border-t border-gray-100 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                        <form action="{{ route('chat.send', $group) }}" method="POST" enctype="multipart/form-data" class="flex items-end space-x-3">
                            @csrf
                            <div class="flex-1 bg-gray-50 rounded-2xl border border-gray-200 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100 transition-all p-2 flex flex-col">
                                <textarea name="content" rows="1" class="w-full bg-transparent border-none focus:ring-0 text-sm resize-none py-2 px-3" placeholder="Type your message..."></textarea>
                                <div class="flex items-center justify-between border-t border-gray-100 mt-1 pt-1 px-1">
                                    <label class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 rounded-full cursor-pointer transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <input type="file" name="media[]" multiple class="hidden">
                                    </label>
                                    <span class="text-[10px] text-gray-400 uppercase font-bold pr-2 tracking-widest italic">Enter to send</span>
                                </div>
                            </div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 active:scale-95 group">
                                <svg class="w-6 h-6 transform rotate-90 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Scroll to bottom of message container
        const container = document.getElementById('message-container');
        const scrollToBottom = () => {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        };
        scrollToBottom();

        // Simple textarea auto-resize
        const textarea = document.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Submit on Enter (without shift)
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.closest('form').submit();
                }
            });
        }

        // AJAX Form Submission
        const chatForm = document.querySelector('form');
        if (chatForm) {
            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const content = textarea.value.trim();
                const mediaFiles = document.querySelector('input[type="file"]').files;

                if (!content && mediaFiles.length === 0) return;

                // Reset input
                textarea.value = '';
                textarea.style.height = 'auto';
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        appendMessage(data.message, true);
                        this.reset();
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                }
            });
        }

        const appendMessage = (message, isMe = false) => {
            const messageHtml = `
                <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                    <div class="max-w-[70%] group">
                        <div class="flex items-center mb-1 space-x-2 ${isMe ? 'flex-row-reverse space-x-reverse' : ''}">
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-tight">${message.sender.name}</span>
                            <span class="text-[10px] text-gray-300">Just now</span>
                        </div>
                        <div class="px-4 py-2 rounded-2xl shadow-sm relative ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none'}">
                            ${message.type === 'text' ? `<p class="text-sm leading-relaxed">${message.content || ''}</p>` : ''}
                            ${message.type === 'media' ? `
                                ${message.content ? `<p class="text-sm mb-2 opacity-90 italic border-l-2 ${isMe ? 'border-white/30' : 'border-gray-200'} pl-2">${message.content}</p>` : ''}
                                <div class="grid grid-cols-1 gap-2">
                                    ${message.media.map(m => {
                                        if (m.file_type.startsWith('image/')) {
                                            return `<img src="/storage/${m.file_path}" class="rounded-lg max-h-64 object-cover cursor-pointer hover:opacity-95 transition-opacity" alt="Image">`;
                                        } else {
                                            return `
                                                <a href="/storage/${m.file_path}" target="_blank" class="flex items-center p-2 bg-black/5 rounded-lg text-xs hover:bg-black/10 transition-colors">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Attachment
                                                </a>`;
                                        }
                                    }).join('')}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', messageHtml);
            scrollToBottom();
        };

        // Real-time Chat with Echo
        window.addEventListener('load', () => {
            if (window.Echo) {
                console.log('Echo initialized, subscribing to chat.{{ $group->id }}');
                
                window.Echo.connector.pusher.connection.bind('state_change', (states) => {
                    console.log('Echo Connection State:', states.current);
                });

                window.Echo.private('chat.{{ $group->id }}')
                    .subscribed(() => {
                        console.log('Successfully subscribed to private channel');
                    })
                    .error((error) => {
                        console.error('Subscription error:', error);
                    })
                    .listen('.message.sent', (e) => {
                        console.log('Message received via Echo:', e);
                        const message = e.message;
                        if (message.sender_id == {{ auth()->id() }}) return;
                        appendMessage(message, false);
                    });
            } else {
                console.error('Echo is not defined correctly. Check if app.js is loaded.');
            }
        });
    </script>
</x-app-layout>
