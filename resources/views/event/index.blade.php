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

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Last updated</th>
            <th>Created By</th>
            <th>Created</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <th><a href="<?= route('event.login', [$event->id]); ?>"><?= $event->name; ?></a></th>
                <td><?= $event->date ?></td>
                <td>
                            <span data-toggle="tooltip" data-placement="top" title="<?=$event->updated_at ?>">
                                <?= Carbon\Carbon::parse($event->updated_at)->diffForHumans() ?>
                            </span>
                </td>
                <td><?= $event->createdBy ?></td>
                <td>
                            <span data-toggle="tooltip" data-placement="top" title="<?=$event->created_at ?>">
                                <?= Carbon\Carbon::parse($event->created_at)->diffForHumans() ?>
                            </span>
                </td>
                <td>
                    @if($event->active)
                        <span class="label label-info" data-toggle="tooltip" data-placement="top" title="active">Active</span>
                    @endif

                    @if(!$event->active)
                        <span class="label label-danger" data-toggle="tooltip" data-placement="top" title="Finished">Finished</span>
                    @endif
                    @if($user->admin && $event->active)
                        <div class="pull-right">
                            <?php echo Modal::named('close_' . $event->id)
                                    ->withTitle('Close ' . $event->name)
                                    ->withButton(Button::danger('Close Event')->setSize('btn-xs'))
                                    ->withBody(view('modals.events_close_event')->with('event', $event)->render());
                            ?>
                        </div>
                    @endif
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

@endsection