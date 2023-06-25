@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card navbar-nav me-auto">
                <div class="card-header">{{ __('Friends') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
        <ul class="navbar-nav me-auto">
            @foreach ($friends as $friend)
                <a href="/dm/{{$friend->id}}">{{ $friend->name }}</a>
            @endforeach
        </ul>

<ul class="navbar-nav ms-auto">
</ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
