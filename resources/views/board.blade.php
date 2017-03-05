@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">

            <!-- Display Threads -->
            @if (count($threads) > 0)
                <div class="list-group">
                    @foreach ($threads as $thread)
                        <a href="#" class="list-group-item">{{ $thread->topic }}</a>
                    @endforeach
                </div>
            @else
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="alert alert-danger" role="alert">No threads found</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
