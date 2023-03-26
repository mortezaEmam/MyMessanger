<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('chat-list') }}
        </h2>
    </x-slot>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="chat_container">
        <div class="chat_list_container test">
            @livewire('chat.chat-list')
        </div>
        <div class="chat_box_container">
            @livewire('chat.chat-box')
            @livewire('chat.send-message')
        </div>
    </div>
</div>
