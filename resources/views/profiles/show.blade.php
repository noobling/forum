@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h2>{{ $userProfile->name }}</h2>
        </div>

        @foreach($activities as $date => $activity)
            <h2>{{ $date }}</h2>
            @foreach($activity as $record)
                @include('profiles.activities.' . $record->type, ['activity' => $record])
            @endforeach
        @endforeach
    </div>

@endsection