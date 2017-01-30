<div class="modal-body">
    <div class="row">
        <div class="col-md-4">
            <p>Name: <?php echo $product->name; ?></p>
            <p>Last update:
            <span data-toggle="tooltip" data-placement="top" title="<?=$product->updated_at ?>">
            	<?= Carbon\Carbon::parse($product->updated_at)->diffForHumans() ?>
            </span></p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('products.delete_product', $product->id));
        echo BootForm::hidden('delete_product', '_action');
        echo Button::submit()->danger()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->