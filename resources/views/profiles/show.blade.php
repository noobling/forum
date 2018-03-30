@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">

            <avatars-form :user="{{ $userProfile }}"></avatars-form>

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