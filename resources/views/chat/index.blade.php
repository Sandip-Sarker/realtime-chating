<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex h-[600px]">
                <!-- Sidebar -->
                <div class="w-1/3 border-r border-gray-200 flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Messages</h2>
                        <button class="p-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors shadow-sm" title="New Group">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <div class="p-3 bg-white border-b border-gray-100">
                        <div class="relative">
                            <input type="text" placeholder="Search contacts..." class="w-full bg-gray-50 border-gray-200 rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 py-2 pl-8">
                            <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto">
                        <!-- Users/Contacts List -->
                        <div class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacts</div>
                        @foreach($users as $user)
                            <a href="{{ route('chat.start', $user) }}" class="flex items-center p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <div class="w-12 h-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg mr-3 shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-baseline">
                                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate italic">Start a conversation...</p>
                                </div>
                            </a>
                        @endforeach

                        <!-- Groups List -->
                        <div class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4">Groups</div>
                        @forelse($groups->where('is_group', true) as $group)
                            <a href="{{ route('chat.show', $group) }}" class="flex items-center p-4 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                <div class="w-12 h-12 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-lg mr-3 shadow-sm">
                                    {{ substr($group->displayName, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-baseline">
                                        <span class="font-medium text-gray-900">{{ $group->displayName }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncateitalic">Group Chat</p>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-sm text-gray-400 italic text-center">No groups yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Window -->
                <div class="flex-1 flex flex-col bg-gray-50/30">
                    <div class="flex-1 flex items-center justify-center text-gray-400 flex-col">
                        <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-lg">Select a conversation to start chatting</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
