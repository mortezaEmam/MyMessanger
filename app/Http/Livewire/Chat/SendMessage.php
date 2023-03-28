<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SendMessage extends Component
{
    protected $listeners = ['updateSendMessage', 'dispatchMessageSent','resetComponent'];
    public $selectedConversation;
    public $receiverInstance;
    public $createMessage;
    public $body;


    public function resetComponent()
    {
        $this->selectedConversation =null;
        $this->receiverInstance=null;
    }
     function updateSendMessage(Conversation $conversation, User $receiver)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
    }

    public function sendMessage()
    {
        if ($this->body == null) {
            return null;
        }
        $this->createMessage = Message::query()->create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedConversation->receiver_id,
            'body' => $this->body
        ]);
        $this->selectedConversation->last_time_message = $this->createMessage->created_at;
        $this->selectedConversation->save();
        $this->emitTo('chat.chat-box', 'pushMessage', $this->createMessage->id);
        $this->emitTo('chat.chat-list', 'refresh');
        $this->reset('body');
        $this->emitSelf('dispatchMessageSent');
    }

    function dispatchMessageSent()
    {
        $receiver = User::find($this->selectedConversation->receiver_id);
        broadcast(new MessageSent(Auth::user(), $this->createMessage, $this->selectedConversation, $receiver));
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
