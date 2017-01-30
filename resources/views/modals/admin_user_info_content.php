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
        if(!$user->admin):
            echo BootForm::open()->post()->action(route('admin.promote_user', $user->id));
            echo BootForm::hidden('promote_user', '_action');
            echo Button::submit()->success()->withValue('Promote');
            echo BootForm::close();
        else:
            echo BootForm::open()->post()->action(route('admin.demote_user', $user->id));
            echo BootForm::hidden('demote_user', '_action');
            echo Button::submit()->danger()->withValue('Demote');
            echo BootForm::close();
        endif;
        ?>
    </div>
</div><!-- /.modal-content -->