@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <a href="{{ url('thread') }}"><button type="button" class="btn btn-default btn-block">Create Thread</button></a>
        </div>
        <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Board</div>
                    <div class="panel-body">
                        <!-- Display pinned threads -->
                        @if (count($pinned) > 0)
                            <div class="list-group">
                                @foreach ($pinned as $thread)
                                    <a href="{{ url('thread/'.$thread->id) }}" class="list-group-item">
                                        <span class="label label-success">Pinned</span>
                                        @if ($thread->new_posts)
                                            <span class="label label-primary">New</span>
                                        @endif
                                        {{ $thread->title }}<small>{{ $thread->created_at->format('Y-m-d') }}</small>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <!-- Display Threads -->
                        @if (count($threads) > 0)
                            <div class="list-group">
                                @foreach ($threads as $thread)
                                    <a href="{{ url('thread/'.$thread->id) }}" class="list-group-item">
                                        @if ($thread->new_posts)
                                            <span class="label label-primary">New</span>
                                        @endif
                                        {{ $thread->title }}<small>{{ $thread->created_at->format('Y-m-d') }}</small>
                                    </a>
                                @endforeach
                            </div>

                            <div align="center">
                                {{ $threads->links() }}
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">No threads found</div>
                            <div align="center">Create a thread now?</div>
                        @endif
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
