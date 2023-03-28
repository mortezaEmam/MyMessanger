<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $receiver;
    public $message_count;
    public $messages;
    public $height;
    public $receiverInstance;
    public $paginateVar = 10;
//    protected $listeners=['broadcastedMessageReceived',
//            'loadConversation','pushMessage','loadmore','updateHeight'];

    public function getListeners()
    {
        $auth_id = Auth::user()->id;
        return ["echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadConversation', 'pushMessage', 'loadmore', 'updateHeight','broadcastedMessageRead2','resetComponent'];

    }
    public function resetComponent()
    {
        $this->selectedConversation =null;
        $this->receiverInstance=null;
    }
    public function broadcastedMessageRead($event)
    {
        if ($this->selectedConversation) {
            if ((int)$this->selectedConversation->id === (int)$event['conversation_id']) {
                $this->dispatchBrowserEvent('MarkMessageAsRead');
            }
        }
    }
    /*----------------------------------------------------*/
    /*-------------broadcaste event function--------------*/
    /*----------------------------------------------------*/

    public function broadcastedMessageReceived($event)
    {
        $this->emitTo('chat.chat-list', 'refresh');
        $broadcastedMessage = Message::find($event['message']);
        if ($this->selectedConversation) {
            if ((int)$this->selectedConversation->id === (int)$event['conversation_id']) {
                $broadcastedMessage->read = 1;
                $broadcastedMessage->save();
                $this->pushMessage($broadcastedMessage->id);
                $this->emitSelf('broadcastedMessageRead2',);
            }
        }
    }

     function broadcastedMessageRead2()
    {

        broadcast(new MessageRead($this->selectedConversation->id, $this->receiverInstance->id));
    }
    /*----------------------------------------------------*/
    /*------------------push message to chat--------------*/
    /*----------------------------------------------------*/

    public function pushMessage($messageId)
    {
        $newMessage = Message::query()->find($messageId);
        $this->messages->push($newMessage);
        $this->dispatchBrowserEvent('rowChatToBottom');
    }
    /*----------------------------------------------------*/
    /*--------------------load more-----------------------*/
    /*----------------------------------------------------*/
    public function loadmore()
    {
        $this->paginateVar = $this->paginateVar + 10;
        $this->message_count = Message::query()->where('conversation_id', $this->selectedConversation->id)->count();
        $this->messages = Message::query()->where('conversation_id', $this->selectedConversation->id)
            ->skip($this->message_count - $this->paginateVar)->take($this->paginateVar)->get();
        $height = $this->height;
        $this->dispatchBrowserEvent('updateHeight', ($height));
    }
    /*----------------------------------------------------*/
    /*----------update height of messageBody--------------*/
    /*----------------------------------------------------*/
    function updateHeight($height)
    {
        $this->height = $height;
    }

    public function loadConversation(Conversation $conversation, User $receiver)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
//        dd($this->selectedConversation->id,Auth::user()->id);
        $this->message_count = Message::query()->where('conversation_id', $this->selectedConversation->id)->count();
        $this->messages = Message::query()->where('conversation_id', $this->selectedConversation->id)
            ->skip($this->message_count - $this->paginateVar)->take($this->paginateVar)->get();
        $this->dispatchBrowserEvent('chatSelected');

        Message::query()->where('conversation_id',$this->selectedConversation->id)
            ->where('receiver_id',Auth::user()->id)->update(['read'=>1]);
        $this->emitSelf('broadcastedMessageRead2');
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
