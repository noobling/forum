@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
            <a href="#">{{ $activity->subject->owner->name }}</a> replied to:
            <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
        </span>
        <span>
            {{ $activity->created_at->diffForHumans() }}
        </span>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent