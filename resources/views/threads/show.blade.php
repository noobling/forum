@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" :data-locked="{{ $thread->locked  }}"inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <div class="flex">
                                    <a href="{{ route('profiles', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                                    said: {{ $thread->title }}
                                </div>
                                @can('update', $thread)
                                    <form method="post" action="{{ $thread->path() }}">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}

                                        <button type="submit" class="btn btn-primary-default">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>

                    <replies :data="{{ $thread->replies }}"
                             @removed="repliesCount--"
                             @added="repliesCount++"></replies>

                    {{--@foreach ($replies as $reply)--}}
                    {{--@include ('threads.reply')--}}
                    {{--@endforeach--}}

                    {{--{{ $replies->links() }}--}}

                    {{--@if (auth()->check())--}}

                    {{--<form action="{{ $thread->path() }}/replies" method="POST">--}}
                    {{--{{ csrf_field() }}--}}
                    {{--<div class="form-group">--}}
                    {{--<textarea class="form-control" name="body" id="" cols="30" rows="10"--}}
                    {{--placeholder="Have something to say?"></textarea>--}}
                    {{--</div>--}}

                    {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                    {{--</form>--}}

                    {{--@else--}}
                    {{--<p class="text-center">Please sign in to reply to thread <a href="{{ route('login') }}">here</a></p>--}}
                    {{--@endif--}}
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ $thread->created_at->diffForHumans() }} by <a
                                    href="{{ route('profiles', $thread->creator->name) }}">{{ $thread->creator->name }}</a>
                            with <span v-text="repliesCount"></span> {{ str_plural('reply', $thread->replies_count) }}.

                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                            <button class="btn btn-default" v-if="authorize('isAdmin') && !locked" @click="lock">Lock Thread</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </thread-view>

@endsection
