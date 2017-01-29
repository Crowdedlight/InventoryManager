@extends('layout.master')

@section('content')
    <div class="panel panel-danger">
        <div class="panel-heading"><h3 class="panel-title">No access</h3></div>
        <div class="panel-body">
            <p>
                No access. Don't snoop around where you aren't meant to.
            </p>
            <p>
                <a href="{{route('home')}}">Go back</a>
            </p>

        </div>
    </div>
@endsection