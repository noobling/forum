@component('profiles.activities.activity')
    @slot('heading')
        <span class="flex">
            <a href="{{ route('profiles', $userProfile->name) }}">
                {{ $userProfile->name }}
            </a> favourited
            <a href="{{ $activity->subject->favourited->path() }}">
                reply
            </a>
        </span>
        <span>
            {{ $activity->created_at->diffForHumans() }}
        </span>
    @endslot
    @slot('body')
        {{ $activity->subject->favourited->body }}
    @endslot
@endcomponent