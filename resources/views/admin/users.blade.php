@extends('layout.master')

@section('content')

<div class="row">
    <div class="col-md-9">
        <div class="jumbotron">
            <h1>Manage Users</h1>
            <?php echo BootForm::open() ?>
            <?php echo BootForm::text('Search User', 'search_user') ?>
            <?php echo BootForm::close() ?>
            <ul class="list-group" id="found_users">
            </ul>
        </div>
    </div>

    <div class="col-md-3">
        <div class="jumbotron">
            <?php echo Modal::named('addUser')
                ->withTitle('Add User')
                ->withButton(Button::info('Add User')->block())
                ->withBody(view('modals.admin_add_user')->render())
            ?>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">All Users</div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Last Login</th>
            <th>Admin</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $admin)
            @if(Auth::user()->id == $admin->id) @continue; @endif
            <tr>
                <td> {{ $admin->username }} </td>
                <td> {{ $admin->name }}</td>
                <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{ $admin->updated_at }}">
                            {{ Carbon\Carbon::parse($admin->updated_at)->diffForHumans() }}
                        </span>
                </td>
                <td>
                    @if ($admin->admin)
                        <?php echo Modal::named('demote_' . $admin->id)
                            ->withTitle('Demote ' . $admin->name)
                            ->withButton(Button::danger('demote')->setSize('btn-xs'))
                            ->withBody(view('modals.admin_user_info_content')->with('user', $admin)->render());
                        ?>
                    @else
                        <?php echo Modal::named('demote_' . $admin->id)
                            ->withTitle('Promote ' . $admin->name)
                            ->withButton(Button::success('promote')->setSize('btn-xs'))
                            ->withBody(view('modals.admin_user_info_content')->with('user', $admin)->render());
                        ?>
                    @endif
                </td>
                <td>
                    <?php echo Modal::named('delete_' . $admin->id)
                        ->withTitle('Delete ' . $admin->name)
                        ->withButton(Button::danger('delete')->setSize('btn-xs'))
                        ->withBody(view('modals.admin_delete_user')->with('user', $admin)->render());
                    ?>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<?php echo Modal::named('user_info'); ?>

@endsection
