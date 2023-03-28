<div>
    {{-- The Master doesn't talk, he acts. --}}
    @if($selectedConversation)
        <form wire:submit.prevent="sendMessage">
            <div class="chatbox_footer">
                <div class="custom_form_group">
                    <input type="text" wire:model="body"  class="text-gray-900" id="txt" placeholder="write message ...">
                    <button type="submit" class="submit">send</button>
                </div>
            </div>
        </form>
    @endif

</div>
