@extends('layouts.app')

@section('content')
    <div class="card">
        @include('partials.discussion-header')
        <div class="card-body">
            <div class="text-center">
                <strong>{{$discussion->title}}</strong>
            </div>
            <hr>
            {!!$discussion->content!!}
            @if($discussion->bestReply)
                <card class="card bg-success my-5 text-white">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <img src="{{Gravatar::src($discussion->bestReply->owner->email)}}" width="40px" height="40px" style="border-radius: 50%;">
                                <span style="font-weight: bold;" class="ml-2">{{$discussion->bestReply->owner->name}}</span>
                            </div>
                            <div style="font-weight: bold;">Best Reply</div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!!$discussion->bestReply->content!!}
                    </div>
                </card>
            @endif
        </div>
    </div>

    @foreach($discussion->replies()->paginate(2) as $reply)
        <div class="card my-5">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <img src="{{Gravatar::src($reply->owner->email)}}" width="40px" height="40px" style="border-radius: 50%;">
                        <span style="font-weight: bold;" class="ml-2">{{$reply->owner->name}}</span>
                    </div>
                    <div>
                        @auth
                            @if(auth()->user()->id == $discussion->user_id)
                                <form action="{{route('discussions.best-reply', ["discussion" => $discussion->slug, "reply" => $reply->id])}}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Mark as best reply</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="card-body">
                {!!$reply->content!!}
            </div>
        </div>
    @endforeach
    {{$discussion->replies()->paginate(2)->links()}}

    <div class="card my-5">
        <div class="card-header">Add a reply</div>
        <div class="card-body">
            @auth
                <form action="{{route('replies.store', $discussion->slug)}}" method="post">
                    @csrf
                    <input id="content" type="hidden" name="content">
                    <trix-editor input="content"></trix-editor>
                    <button type="submit" class="btn btn-success btn-sm my-2">Add Reply</button>
                </form>
            @else
                <a href="{{route('login')}}" class="btn btn-info">Sign In to Add a Reply</a>
            @endauth
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.css">
@endsection

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.0/trix.js"></script>
@endsection
