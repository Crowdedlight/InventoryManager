<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-warning">This will update and add all products in your current Izettle to this application. Are you sure you want to do that?</div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <?php
        echo BootForm::open()->post()->action(route('izettle.getproducts', $eventID));
        echo BootForm::hidden('_action')->value('import_product');
        echo Button::submit()->info()->withValue('Are you Sure?');
        echo BootForm::close();
        ?>
    </div>
</div><!-- /.modal-content -->