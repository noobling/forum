@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $thread->creator->name }} said: {{ $thread->title }}</div>
                <div class="panel-body">
                   {{ $thread->body }}
                </div>
            </div>
        </div>
		</div>
		<div class="row">
        <div class="col-md-8 col-md-offset-2">
					@foreach ($thread->replies as $reply)
            @include ('threads.reply')
					@endforeach
        </div>
		</div>
		
		@if (auth()->check())
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<form action="{{ $thread->path() }}/replies" method="POST">
						{{ csrf_field() }}
						<div class="form-group">
								<textarea class="form-control" name="body" id="" cols="30" rows="10" placeholder="Have something to say?"></textarea>
						</div>
						
						<button type="submit" class="btn btn-default">Submit</button>
					</form>	
				</div>
			</div>
		@else
			<p class="text-center">Please sign in to reply to thread <a href="{{ route('login') }}">here</a></p>
		@endif
</div>
@endsection
