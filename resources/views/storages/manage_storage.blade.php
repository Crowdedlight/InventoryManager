@extends('layout.master')

@section('content')

    <div class="row">
        <div class="col-md-9">
            <div class="jumbotron">
                <h1>Manage Storages</h1>

                <?php echo BootForm::text('search_storage', 'Search Storage') ?>
                <ul class="list-group" id="found_storage">
                </ul>
            </div>
        </div>

        <div class="col-md-3">
            <div class="jumbotron">
                <?php echo Modal::named('addStorage')
                        ->withTitle('Add Storage')
                        ->withButton(Button::info('Add Storage')->block())
                        ->withBody(view('modals.storages_add_storage')
                                ->with('eventID', Auth::user()->Event()->id)
                                ->render())
                ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">All Storages</div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Created By</th>
                <th>Created</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>

            @foreach($storages as $storage)
                <tr>
                    <td> {{ $storage->name }} </td>
                    <td> {{ $storage->createdBy }}</td>
                    <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{ $storage->created_at }}">
                            {{ Carbon\Carbon::parse($storage->created_at)->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <?php echo Modal::named('delete_' . $storage->id)
                                ->withTitle('Delete ' . $storage->name)
                                ->withButton(Button::danger('delete')->setSize('btn-xs'))
                                ->withBody(view('modals.storages_delete_storage')->with('storage', $storage)->render());
                        ?>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

