@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @forelse ($threads as $thread)
                    <div class="panel panel-default">

                        <div class="panel-heading">
                            <div class="level">
                                <h4 class="flex">
                                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                        <strong> <a href="{{ $thread->path() }}">{{ $thread->title }}</a></strong>
                                    @else
                                        <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                                    @endif
                                </h4>
                                <strong>
                                    <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</a>
                                </strong>
                            </div>
                        </div>

                        <div class="panel-body">
                            <p>{{ $thread->body }}</p>
                        </div>
                    </div>

                @empty
                    <div class="text-center">
                        <h2>No relevant results.</h2>
                    </div>
                @endforelse
            </div>
        </div>
@endsection
