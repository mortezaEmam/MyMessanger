<div>
    <div class="chat_container">
        <div class="chat_list_container test">
            @livewire('chat.chat-list')
        </div>
        <div class="chat_box_container">
            @livewire('chat.chat-box')
            @livewire('chat.send-message')
        </div>
    </div>
    <script>
        // show when resize min and max size
        window.addEventListener('chatSelected',event=>{
            if(window.innerWidth<768){
                $('.chat_list_container').hide();
                $('.chat_box_container').show();
            }
            $('.chatbox_body').scrollTop($('.chatbox_body')[0].scrollHeight);
            let height = $('.chatbox_body')[0].scrollHeight;
            window.livewire.emit('updateHeight',{
                height:height,
            })
        });
        $(window).resize(function (){
            if(window.innerWidth>768){
                $('.chat_list_container').show();
                $('.chat_box_container').hide();
            }
        })
        // back page when click return
        $(document).on('click','.return',function () {
            $('.chat_list_container').show();
            $('.chat_box_container').hide();
        })
    </script>
</div>
