<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-$reply->id" class="panel panel-default">
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
        <div v-if="editing">
            <div class="form-group">
                 <textarea class="form-control" v-model="body">

                </textarea>
                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>
            </div>

        </div>
        <div v-else>
            <div class="panel-body" v-text="body">
            </div>
        </div>

        <div class="panel-footer level">
            @can('update', $reply)
                <form method="POST" action="/replies/{{ $reply->id }}">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <button type="submit" class="btn btn-danger mr-1">Delete</button>
                </form>
                <button class="btn btn-warning" @click="editing = true">Update</button>
            @endcan
        </div>
    </div>
</reply>