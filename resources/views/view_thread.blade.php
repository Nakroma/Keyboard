@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ url('board') }}"><button type="button" class="btn btn-default btn-block">Home</button></a>
                @if ((Auth::id() == $thread->author) || (Auth::user()->group >= config('_custom.permissions')['deleteThread']))
                    <br>
                    <form action="{{ url('thread/delete/'.$thread->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger btn-block">Delete Thread</button>
                    </form>
                @endif
                @if (Auth::user()->group >= config('_custom.permissions')['revealModStatus'])
                    <br>
                    <a href="{{ url('mod/'.$thread->id) }}"><button class="btn btn-block btn-success">Reveal Mod Status</button></a>
                @endif
            </div>
            <div class="col-md-8">
                <!-- Thread header -->
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $thread->title }} <small>by {{ $callnames[$thread->author] }}
                            @if($moderator[$thread->author])<span class="label label-success">Mod</span>@endif @ {{ $thread->created_at }}</small></div>
                    <div class="panel-body">
                        {!! $thread->body !!}
                    </div>
                </div>

                <!-- Posts -->
                @if (count($posts) > 0)
                <div class="panel panel-default">
                    <div class="panel-body"> <!-- TODO: Remove this hack -->
                        @foreach ($posts as $post)
                            <div class="panel panel-default" style="margin-bottom:5px;">
                                <div class="panel-heading"><small>{{ $callnames[$post->author] }} @if($moderator[$post->author])<span class="label label-success">Mod</span>@endif @ {{ $post->created_at }}
                                    @if (Auth::user()->group >= config('_custom.permissions')['deletePost'])
                                        <a href="{{ url('post/delete/'.$post->id) }}" class="del-post">Delete</a>
                                    @endif
                                    </small></div>
                                <div class="panel-body">
                                    {!! $post->body !!}
                                </div>
                            </div>
                        @endforeach
                        <div align="center">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Post field -->
                <div class="panel panel-default">
                    <div class="panel-heading">Create Post</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('post') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body" class="col-md-1 control-label">Body</label>

                                <div class="col-md-11">
                                    <input type="hidden" name="thread" value="{{ $thread->id }}">

                                    <textarea id="body" class="form-control" name="body" rows="3" required>{{ old('body') }}</textarea>

                                    @if ($errors->has('body'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Create Post
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection