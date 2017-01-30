@extends('layout.master')

@section('content')

    <div class="row">
        <div class="col-md-9">
            <div class="jumbotron">
                <h1>Manage Products</h1>
                <?php echo BootForm::open() ?>
                <?php echo BootForm::text('Search Product', 'search_product') ?>
                <?php echo BootForm::close() ?>
                <ul class="list-group" id="found_products">
                </ul>
            </div>
        </div>

        <div class="col-md-3">
            <div class="jumbotron">
                <?php echo Modal::named('addProduct')
                        ->withTitle('Add Product')
                        ->withButton(Button::info('Add Product')->block())
                        ->withBody(view('modals.products_add_product')
                                ->with('eventID', Auth::user()->Event()->id)
                                ->render())
                ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">All Products</div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Created By</th>
                <th>Created</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>

            @foreach($products as $product)
                <tr>
                    <td> {{ $product->name }} </td>
                    <td> {{ $product->createdBy }}</td>
                    <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{ $product->created_at }}">
                            {{ Carbon\Carbon::parse($product->created_at)->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <?php echo Modal::named('delete_' . $product->id)
                                ->withTitle('Delete ' . $product->name)
                                ->withButton(Button::danger('delete')->setSize('btn-xs'))
                                ->withBody(view('modals.products_delete_product')->with('product', $product)->render());
                        ?>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

