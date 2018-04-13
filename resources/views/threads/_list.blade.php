@forelse ($threads as $thread)
    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong> <a href="{{ $thread->path() }}">{{ $thread->title }}</a></strong>
                        @else
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                        @endif
                    </h4>

                    <h5>
                        Posted by:
                        <a href="{{ route('profiles', $thread->creator) }}">{{ $thread->creator->name }}</a>
                        <img src="{{ $thread->creator->avatar_path }}" alt="" width="25" height="25">
                    </h5>
                </div>

                <strong>
                    <a href="{{ $thread->path() }}">{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</a>
                </strong>
            </div>
        </div>

        <div class="panel-body">
            <p>{{ $thread->body }}</p>
        </div>

        <div class="panel-footer">
            {{ $thread->visits()->count() }} {{ str_plural('Visit', $thread->visits()->count())}}
        </div>
    </div>

@empty
    <div class="text-center">
        <h2>No relevant results.</h2>
    </div>
@endforelse