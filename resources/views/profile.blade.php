@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="/board"><button type="button" class="btn btn-default btn-block">Home</button></a>
            </div>
            <div class="col-md-8">

                <!-- Thread header -->
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

            </div>
        </div>
    </div>
@endsection