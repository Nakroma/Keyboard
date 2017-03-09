@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="/board"><button type="button" class="btn btn-default btn-block">Home</button></a>
            </div>
            <div class="col-md-8">

                <!-- Profile -->
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>
                    <div class="panel-body">
                        <b>Username:</b> {{ $user->username }}<br>
                        <b>Personal Key:</b> {{ $key->key_value }} @if($key->used)
                            <span class="label label-danger">Used</span>@else
                            <span class="label label-success">Available</span>@endif<br>
                        <b>Group:</b> {{ config('_custom.groups')[$user->group] }}<br>
                        <b>Joined in:</b> {{ $user->created_at }}
                    </div>
                </div>

                <!-- Moderation Area -->
                @if ($mod_area)
                    <div class="panel panel-default">
                        <div class="panel-heading">Moderation Area</div>
                        <div class="panel-body">
                            @if ($user->group >= $prm['banUser'])
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('user/ban') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group" style="text-align: right;">
                                        <div class="col-md-2">
                                            <label for="thread" class="control-label">Thread ID</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input id="thread" type="text" class="form-control" name="thread" value="{{ old('thread') }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="callname" class="control-label">Callname</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input id="callname" type="text" class="form-control" name="callname" value="{{ old('callname') }}" required>
                                        </div>
                                        <div class="col-md-3" style="text-align: center;">
                                            <button type="submit" class="btn btn-danger">
                                                Ban User
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            @endif
                            @if ($user->group >= $prm['promoteUser'])
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('user/promote') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group" style="text-align: right;">
                                        <div class="col-md-2">
                                            <label for="group" class="control-label">Group</label>
                                        </div>
                                        <div class="col-md-2">
                                            <input id="group" type="text" class="form-control" name="group" value="{{ old('group') }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="username" class="control-label">Username</label>
                                        </div>
                                        <div class="col-md-3">
                                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                        </div>
                                        <div class="col-md-3" style="text-align: center;">
                                            <button type="submit" class="btn btn-success">
                                                Promote User
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            @endif
                            @if ($user->group >= $prm['createKey'])
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('key/generate') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group" style="text-align: right;">
                                        <div class="col-md-2 col-md-offset-6">
                                            <label for="number" class="control-label">Number</label>
                                        </div>
                                        <div class="col-md-1">
                                            <input id="number" type="text" class="form-control" name="number" value="{{ old('number') }}" required>
                                        </div>
                                        <div class="col-md-3" style="text-align: center;">
                                            <button type="submit" class="btn btn-primary">
                                                Generate Key(s)
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection