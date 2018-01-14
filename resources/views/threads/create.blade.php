@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
				<div class="panel-heading">Create a thread</div>

                <div class="panel-body">
					@if (count($errors))
						<ul class="alert alert-danger">
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					@endif
                    <form action="/threads" method="POST">
						{{ csrf_field() }}
						<div class="form-group">
							<label for="channel">Channel:</label>
							<select name="channel_id" id="channel_id" class="form-control" required>
								<option value="">Choose one ...</option>
								@foreach (App\Channel::all() as $channel)
									<option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label for="title">Title:</label>
							<input type="text" id="title" class="form-control" placeholder="Title" name="title" value="{{ old('title') }}" required>
						</div>

						<div class="form-group">
							<label for="body">Body:</label>
							<textarea name="body" id="body" cols="30" rows="10" class="form-control" placeholder="Enter something game changing" required>{{ old('body') }}</textarea>
						</div>

						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
