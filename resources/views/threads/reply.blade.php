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
                @can('update', $reply)
                    <favourite :reply="{{ $reply }}"></favourite>
                @endcan
            </div>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                     <textarea class="form-control" v-model="body">

                    </textarea>
                </div>

                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>

            </div>
            <div v-else>
                <div v-text="body">
                </div>
            </div>
        </div>


        <div class="panel-footer level">
            @can('update', $reply)
                <button type="submit" class="btn btn-danger mr-1" @click="destroy">Delete</button>
                <button class="btn btn-warning" @click="editing = true">Update</button>
            @endcan
        </div>
    </div>
</reply>