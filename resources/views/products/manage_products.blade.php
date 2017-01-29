@extends('layout.master')

@section('content')

    <div class="row">
        <div class="col-md-9">
            <div class="jumbotron">
                <h1>Manage Products</h1>

                <?php echo BootForm::text('search_product', 'Search Product') ?>
                <ul class="list-group" id="found_products">
                </ul>
            </div>
        </div>

        <div class="col-md-3">
            <div class="jumbotron">
                <?php echo Modal::named('addProduct')
                        ->withTitle('Add Product')
                        ->withButton(Button::info('Add Product')->block())
                        ->withBody(view('modals.add_product')
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
                    <td> {{ $product->modifiedBy }}</td>
                    <td>
                        <span data-toggle="tooltip" data-placement="top" title="{{ $product->created_at }}">
                            {{ Carbon\Carbon::parse($product->created_at)->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <?php echo Modal::named('delete_' . $product->id)
                                ->withTitle('Delete ' . $product->name)
                                ->withButton(Button::danger('delete')->setSize('btn-xs'))
                                ->withBody(view('modals.product_delete_product')->with('product', $product)->render());
                        ?>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

