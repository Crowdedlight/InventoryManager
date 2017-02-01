<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <p>Name: <?php echo $product->name; ?></p>
            <p>Last update:
            <span data-toggle="tooltip" data-placement="top" title="<?=$product->updated_at ?>">
            	<?= Carbon\Carbon::parse($product->updated_at)->diffForHumans() ?>
            </span></p>
            <div class=""><strong>THIS WILL REMOVE ALL ENTRIES IN EVERY STORAGE FOR THIS PRODUCT. SO YOU WILL ESSENTIAL REMOVE THIS PRODUCT AND ALL ITS VALUES FROM EVERY STORAGE</strong></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('products.delete_product', $product->id));
        echo BootForm::hidden('_action')->value('delete_product');
        echo Button::submit()->danger()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->