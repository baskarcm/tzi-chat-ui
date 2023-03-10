<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="FS">
    <meta name="apple-mobile-web-app-title" content="FS">
    <meta name="theme-color" content="#343a40">
    <meta name="msapplication-navbutton-color" content="#343a40">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('vendor/messenger/images/android-chrome-192x192.png')}}">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{asset('vendor/messenger/images/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('vendor/messenger/images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('vendor/messenger/images/favicon-16x16.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="@yield('title', config('messenger-ui.site_name'))">
    <title>@yield('title', config('messenger-ui.site_name'))</title>
    <meta name="auth-check" content="{{ (Auth::check()) ? Auth::id() : '' }}">
    <meta name="audio" content="{{ (Auth::check()) ? Auth::user()->audio : '' }}">
    <meta name="upload" content="{{ (Auth::check()) ? Auth::user()->upload : '' }}">
    @auth
        <link id="main_css" href="{{ asset(mix(messenger()->getProviderMessenger()->dark_mode ? 'dark.css' : 'app.css', 'vendor/messenger')) }}" rel="stylesheet">
    @else
        <link id="main_css" href="{{ asset(mix('dark.css', 'vendor/messenger')) }}" rel="stylesheet">
    @endauth
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.1/css/all.min.css">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    @php
    $sender_msg_bg_color = Auth::user()->sender_msg_bg_color?Auth::user()->sender_msg_bg_color:0;
    $sender_msg_color = Auth::user()->sender_msg_color?Auth::user()->sender_msg_color:0;
    $receiver_msg_bg_color = Auth::user()->receiver_msg_bg_color?Auth::user()->receiver_msg_bg_color:0;
    $receiver_msg_color = Auth::user()->receiver_msg_color?Auth::user()->receiver_msg_color:0;
    @endphp
    <style>
        #message_text_input {
            height: 50px;
            resize: none;
        }
        .inline_send_msg_btn_2 {
            bottom: 8px;
            right:  25px;
        }
        textarea#message_text_input::-webkit-scrollbar, textarea#message_text_input::-webkit-scrollbar-track {
            background-color: #444!important;
        }
        textarea#message_text_input::-webkit-scrollbar {
            width: 7px;
            height: 7px;
            background-color: #eee;
        }
        textarea#message_text_input::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgb(0 0 0 / 30%);
        }
        .bg-light {
            /* min-height: 73px; */
        }
        #thread_header_area i.fas.fa-check-circle.fa-2x ,#thread_header_area i.fas.fa-times-circle.fa-2x{
            font-size: 1em;
        }
        #thread_header_area .dropdown.float-right {
            top: 10px;
        }
        .card.messages-panel .chat-body {
            height: calc(100vh - 155px);
        }
        .message {
            width: 98%;
        }
        .message .message-info {
            padding-right: 1px;
        }
        .message.info.d-flex.justify-content-end:before{
            float: right;
            left: 99.2%;
            border-right: none !important;
            border-left: 13px solid {{$sender_msg_bg_color==0?"#d5e1f3":$sender_msg_bg_color}};
        }
        .message.info.d-flex.justify-content-end .message-body, .message.info.d-flex.justify-content-end .message-info>h4, .message.info.d-flex.justify-content-end .message-info>h5, .message.info.d-flex.justify-content-end  .message-info>h5>i{
            background-color: {{$sender_msg_bg_color==0?'#d5e1f3':$sender_msg_bg_color}} !important;
        }
        .message.info.d-flex.justify-content-end .message-body, .message.info.d-flex.justify-content-end .message-info>h4, .message.info.d-flex.justify-content-end .message-info>h5, .message.info.d-flex.justify-content-end  .message-info>h5>i{
            color: {{$sender_msg_color==0?'#263238':$sender_msg_color}} !important;
        }
        
        .message.info:before{
            left: 46px;
            border-right: 13px solid {{$receiver_msg_bg_color==0?'#d5e1f3':$receiver_msg_bg_color}} !important;
        }
        .message.info .message-body, .message.info .message-info>h4, .message.info .message-info>h5, .message.info  .message-info>h5>i{
            background-color: {{$receiver_msg_bg_color==0?'#d5e1f3':$receiver_msg_bg_color}} !important;
        }
        .message.info .message-body, .message.info .message-info>h4, .message.info .message-info>h5, .message.info  .message-info>h5>i{
            color: {{$receiver_msg_color==0?'#263238':$receiver_msg_color}} !important;
        }
        .message{

        }
    </style>
    @stack('css')
</head>
<body>
<wrapper class="d-flex flex-column">
    {{-- <nav id="FS_navbar" class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{url('/')}}">
            <img src="{{ asset('vendor/messenger/images/messenger.png') }}" width="30" height="30" class="d-inline-block align-top" alt="Messenger">
            {{config('messenger-ui.site_name')}}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="badge badge-pill badge-danger mr-n2" id="nav_mobile_total_count"></span>
        </button>
        <div id="navbarNavDropdown" class="navbar-collapse collapse">
            @auth
                @include('messenger::nav')
            @endauth
        </div>
    </nav> --}}
    <main id="FS_main_section" class="pt-5 mt-4 flex-fill">
        <div id="app">
            @yield('content')
        </div>
    </main>
</wrapper>
@include('messenger::scripts')
<script>
    function getCaret(el) { 
    if (el.selectionStart) { 
        return el.selectionStart; 
    } else if (document.selection) { 
        el.focus();
        var r = document.selection.createRange(); 
        if (r == null) { 
            return 0;
        }
        var re = el.createTextRange(), rc = re.duplicate();
        re.moveToBookmark(r.getBookmark());
        rc.setEndPoint('EndToStart', re);
        return rc.text.length;
    }  
    return 0; 
}
$(document).on('keyup','textarea',function (event) {
    event.preventDefault();
    if (event.keyCode == 13) {
        var content = this.value;  
        var caret = getCaret(this);          
        if(event.shiftKey){
            this.value = content.substring(0, caret - 1) + "\n" + content.substring(caret, content.length);
            event.stopPropagation();
        } else {
            this.value = content.substring(0, caret - 1) + content.substring(caret, content.length);
            $('form').submit();
        }
    }
});
</script>
</body>
</html>