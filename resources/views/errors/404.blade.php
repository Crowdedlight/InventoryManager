@extends('layout.master')

@section('content')
    <div class="panel panel-danger">
        <div class="panel-heading"><h3 class="panel-title">404</h3></div>
        <div class="panel-body">
            <p>
                Page not found.
            </p>
            <p>
                <a href="{{route('home')}}">Go back</a>
            </p>

        </div>
    </div>
@endsection