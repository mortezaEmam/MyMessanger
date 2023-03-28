<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $auth_id;
    public $conversations;
    public $receiverInstance;
    public $name;
    public $selectedConversation;
protected $listeners=['chatUserSelected','refresh'=>'$refresh','resetComponent'];

    public function resetComponent()
    {
        $this->selectedConversation =null;
        $this->receiverInstance=null;
}
    public function chatUserSelected(Conversation $conversation,$receiverId)
    {
        $this->selectedConversation = $conversation;
        $receiverInstance =User::find($receiverId);
$this->emitTo('chat.chat-box','loadConversation',$this->selectedConversation,$receiverInstance);
$this->emitTo('chat.send-message','updateSendMessage',$this->selectedConversation,$this->receiverInstance);
    }

    public function getChatUsersInstance(Conversation $conversation ,$requset)
    {
        $this->auth_id = Auth::id();
        if($conversation->sender_id == $this->auth_id){
            $this->receiverInstance = User::query()->firstWhere('id',$conversation->receiver_id);
        }else{
            $this->receiverInstance = User::query()->firstWhere('id',$conversation->sender_id);

        }
        if(isset($requset))
        {
            return $this->receiverInstance->$requset;
        }

}
    public function mount()
    {
        $this->auth_id =Auth::id();
        $this->conversations = Conversation::query()->where('receiver_id',$this->auth_id)
            ->orWhere('sender_id',$this->auth_id)->orderBy('last_time_message','DESC')->get();
    }
    public function render()
    {
        return view('livewire.chat.chat-list')->layout('layouts.app');
    }
}
