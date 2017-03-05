@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="/board"><button type="button" class="btn btn-default btn-block">Home</button></a>
                <a href="/post"><button type="button" class="btn btn-default btn-block">Create Post</button></a>
                @if (Auth::id() == $thread->author)
                    <br><a href="/thread/delete/{{ $thread->id }}"><button type="button" class="btn btn-danger btn-block">Delete Thread</button></a>
                @endif
            </div>
            <div class="col-md-8">
                <!-- Thread header -->
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $thread->title }}</div>
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <!-- Post field -->
                <div class="panel panel-default">
                    <div class="panel-heading">Create Post</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('post') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body" class="col-md-1 control-label">Body</label>

                                <div class="col-md-11">
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