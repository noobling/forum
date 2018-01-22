<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profiles', $reply->owner->name) }}">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }}
            </h5>

            <form method="POST" action="/replies/{{ $reply->id }}/favourites">
                {{ csrf_field() }}
                <button class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                    {{ $reply->favourites_count }} {{ str_plural('Favourite', $reply->favourites_count) }}
                </button>
            </form>
        </div>

    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>