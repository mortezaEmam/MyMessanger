<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateChat extends Component
{
    public $users;
    public $message = 'hello friend';

    public function checkconversation($receiverId)
    {
        $checkCoversation = Conversation::where('receiver_id', Auth::user()->id)->where('sender_id', $receiverId)->orwhere('receiver_id', $receiverId)->where('sender_id', Auth::user()->id)->get();
        if (count($checkCoversation) == 0) {

            $createConversation = Conversation::query()->create(['receiver_id' => $receiverId, 'sender_id' => Auth::user()->id]);
            $createMessage = Message::query()->create(['conversation_id' => $createConversation->id, 'sender_id' => Auth::user()->id, 'receiver_id' => $receiverId, 'body' => $this->message]);
            $createConversation->last_time_message = $createMessage->created_at;
            $createConversation->save();
        } elseif (count($checkCoversation) >= 1) {
            dd('yes conversation');
        }
    }

    public function render()
    {
        $this->users = User::where('id', '!=', Auth::user()->id)->get();
        return view('livewire.chat.create-chat');
    }
}
