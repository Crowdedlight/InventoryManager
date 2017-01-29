@extends('layout.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="jumbotron">
            <h1>Welcome to Inventory Manager</h1>

            <p>Here's a text with some explanations how to use this app.</p>

            <?php
            echo Modal::named('new_event')
                ->withTitle('Add New Event')
                ->withButton(Button::success('Add New Event')->setSize('btn-md'))
                ->withBody(view('modals.new_event')->render());
            ?>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Active Events</div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Last updated</th>
            <th>Created By</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($events as $event)
            <tr>
                <th><a href="{{ route('event.login', ['id' => $event->id]) }}">{{ $event->name }}</a></th>
                <td>{{ $event->date }}</td>
                <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{ $event->updated_at }}">
                            {{ Carbon\Carbon::parse($event->updated_at)->diffForHumans() }}
                        </span>
                </td>
                <td> {{ $event->createdBy }}</td>
                <td>
                        <span data-toggle="tooltip" data-placement="top" title=" {{ $event->created_at }}">
                            {{ Carbon\Carbon::parse($event->created_at)->diffForHumans() }}
                        </span>
                </td>
                <td>
                    @if($event->active)
                        <span class="label label-info" data-toggle="tooltip" data-placement="top" title="active">Active</span>
                    @endif

                    @if($event->active == false)
                        <span class="label label-danger" data-toggle="tooltip" data-placement="top" title="Finished">Finished</span>
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection