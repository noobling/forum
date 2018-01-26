@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
            <a href="{{ route('profiles', $activity->subject->creator->name) }}">{{ $activity->subject->creator->name }}</a> created the thread:
            <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
        </span>
        <span>
            {{ $activity->created_at->diffForHumans() }}
        </span>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent