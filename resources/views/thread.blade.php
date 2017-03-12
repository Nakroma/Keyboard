@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ url('board') }}"><button type="button" class="btn btn-default btn-block">Home</button></a>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Thread</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('thread') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-1 control-label">Title</label>

                                <div class="col-md-11">
                                    <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body" class="col-md-1 control-label">Body</label>

                                <div class="col-md-11">
                                    <textarea id="body" class="form-control" name="body" rows="5" required>{{ old('body') }}</textarea>

                                    @if ($errors->has('body'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if (Auth::user()->group >= config('_custom.permissions')['pinnedThread'])
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-1">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="pinned" {{ old('pinned') ? 'checked' : '' }}> Pin Thread
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Create Thread
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