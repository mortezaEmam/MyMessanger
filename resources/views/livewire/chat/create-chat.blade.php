<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <ul class="list-group w-75 mx-auto mt-2 container-fluid">
    @foreach($users as $user)

            <li class="list-group-item list-group-item-action" wire:click="checkconversation({{$user->id}})">{{$user->name}}</li>

    @endforeach
    </ul>
</div>
