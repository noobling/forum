@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>{{ $userProfile->name }}</h2>

            @can('update', $userProfile)
                <form action="{{ route('avatars', $userProfile) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="file" name="avatar">

                    <button type="submit" class="btn btn-secondary">Add Avatar</button>
                </form>

                <img src="{{ asset('/storage/' . auth()->user()->avatar()) }}" alt="" width="50" height="50">
            @endcan
        </div>

        @forelse($activities as $date => $activity)
            <h2>{{ $date }}</h2>
            @foreach($activity as $record)
                @include('profiles.activities.' . $record->type, ['activity' => $record])
            @endforeach
        @empty
            <h3>This user has no activity yet.</h3>
        @endforelse
    </div>

@endsection