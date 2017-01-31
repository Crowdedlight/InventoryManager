<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <h4><?php echo $user->username; ?></h4>
            <p>Name: <?php echo $user->name; ?></p>
            <p>Email: <?php echo $user->email; ?></p>
            <p>Last login:
            <span data-toggle="tooltip" data-placement="top" title="<?=$user->updated_at ?>">
            	<?= Carbon\Carbon::parse($user->updated_at)->diffForHumans() ?>
            </span></p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('admin.delete_user', $user->id));
        echo BootForm::hidden('_action')->value('delete_user');
        echo Button::submit()->danger()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->