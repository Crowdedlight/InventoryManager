@extends('layout.master')

@section('content')

    <?php $error = Session::pull('error'); $user = Auth::user(); ?>
    @if($error != null)
        <div class="alert-danger alert">
            <strong>Error! </strong> {{$error}}
        </div>
    @endif

    @if(Session::pull('success') != null)
        <div class="alert-success alert" id="successMsg">
            <strong>Success! </strong>
        </div>
        <script type="application/javascript">
            setTimeout(
                    function() {
                        $('#successMsg').slideUp(1000);
                    }, 3000);
        </script>
    @endif

    <div class="row">
        <div class="col-md-9">
            <div class="jumbotron">
                <h1>Manage Storages</h1>
                <?php echo BootForm::open() ?>
                <?php echo BootForm::text('Search Storage', 'search_storage') ?>
                <?php echo BootForm::close() ?>
                <ul class="list-group" id="found_storage">
                </ul>
            </div>
        </div>

        @if($user->event()->active)
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
        @endif
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
                @if($user->admin && $user->event()->active)
                <th>Delete</th>
                @endif
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
                    @if($user->admin && $user->event()->active)
                    <td>
                        <?php echo Modal::named('delete_' . $storage->id)
                                ->withTitle('Delete ' . $storage->name)
                                ->withButton(Button::danger('delete')->setSize('btn-xs'))
                                ->withBody(view('modals.storages_delete_storage')->with('storage', $storage)->render());
                        ?>
                    </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

