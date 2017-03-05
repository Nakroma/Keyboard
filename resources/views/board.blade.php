@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a href="/thread"><button type="button" class="btn btn-default btn-block">Create Thread</button></a>
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
                        <div align="center">Create a thread now?</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
