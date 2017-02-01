<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <p>Name: <?php echo $event->name; ?></p>
            <p>Last update:
            <span data-toggle="tooltip" data-placement="top" title="<?=$event->updated_at ?>">
            	<?= Carbon\Carbon::parse($event->updated_at)->diffForHumans() ?>
            </span></p>
            <div class=""><strong>This will close the event, and you will no longer be able to modify the event</strong></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('events.close_event', $event->id));
        echo BootForm::hidden('_action')->value('close_event');
        echo Button::submit()->danger()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->