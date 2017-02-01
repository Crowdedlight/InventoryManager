<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <p>Name: <?php echo $storage->name; ?></p>
            <p>Last update:
            <span data-toggle="tooltip" data-placement="top" title="<?=$storage->updated_at ?>">
            	<?= Carbon\Carbon::parse($storage->updated_at)->diffForHumans() ?>
            </span></p>
            <div class=""><strong>THIS WILL REMOVE ALL ENTRIES OF THIS STORAGE. SO YOU WILL ESSENTIAL REMOVE THIS STORAGE AND ALL ITS VALUES FROM EVERY PRODUCT IT HAS SOLD/KEPT</strong></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('storages.delete_storage',$storage->id));
        echo BootForm::hidden('_action')->value('delete_product');
        echo Button::submit()->danger()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->