@extends('layout.master')

@section('content')
    <?php $storages = $event->storages()->orderBy('depot', 'DESC')->with('products')->get(); $user = Auth::user(); ?>

    @if(count($errors->all()) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <div class="row">
        <div class="col-md-10 min-height">
            <div class="panel panel-default">
                <div class="panel-heading">Overview</div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th> </th>
                        @foreach ($storages as $storage)
                            <th> {{$storage->name }} </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($event->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            @foreach ($storages as $storage)
                                <?php $prod = $storage->products->where('id', $product->id)->first(); ?>

                                @if($prod->pivot->amount < 5)
                                    <td class="bg-danger" data-productID="{{ $prod->id }}" data-storageID="{{ $storage->id }}">
                                @elseif ($prod->pivot->amount >= 5 && $prod->pivot->amount < 30)
                                    <td class="bg-warning" data-productID="{{ $prod->id }}" data-storageID="{{ $storage->id }}">
                                @else
                                    <td class="bg-success" data-productID="{{ $prod->id }}" data-storageID="{{ $storage->id }}">
                                @endif
                                    @if ($prod != null)
                                        <strong class="amount">{{ $prod->pivot->amount }}</strong>
                                        @if (!$storage->depot)
                                            <small class="pull-right sold-amount">({{$prod->pivot->sold_amount}})</small>
                                        @endif
                                    @endif
                                    </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($user->event()->active)
            <div class="col-md-2">
                <div class="jumbotron">
                    <?php echo Modal::named('stockStorage')
                            ->withTitle('Stock Storage')
                            ->withButton(Button::info('Stock Storage')->block())
                            ->withBody(view('modals.event_stock_storage')
                                    ->with('event', Auth::user()->Event())
                                    ->render())
                    ?>
                </div>
            </div>

            <div class="col-md-2">
                <div class="jumbotron">
                    <?php echo Modal::named('moveProduct')
                            ->withTitle('Move Product')
                            ->withButton(Button::info('Move Product')->block())
                            ->withBody(view('modals.event_move_product')
                                    ->with('event', Auth::user()->Event())
                                    ->render())
                    ?>
                </div>
            </div>

            @if($user->admin)
                <div class="col-md-2">
                    <div class="jumbotron">
                        <?php echo Modal::named('updateSales')
                                ->withTitle('Update Sales')
                                ->withButton(Button::info('Update Sales')->block())
                                ->withBody(view('modals.event_update_sales')
                                        ->with('event', Auth::user()->Event())
                                        ->render())
                        ?>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@push('js')
<script src="{{ mix('js/app.js') }}"></script>
<script>

    var i = window.Echo.private('update.sales.{{$user->event()->id}}').listen('SalesUpdated', function(e) {
        var list = e.update.updateArray;

        for(var key in list) {
            var obj = list[key];

            //console.log("id: " + key + ", StorageID: " + obj.storageID + ", value: " + obj.soldAmount);

            //get element to update
            var td = $('td[data-storageID="' + obj.storageID + '"]' + '[data-productID="' + key + '"]');
            var amount = td.children('.amount');
            var sold_amount = td.children('.sold-amount');

            //get current text
            var currAmount = amount.text();
            var currSoldAmount = parseInt(sold_amount.text().replace(/\(|\)/g, '')); //regEx to remove ( and ) from text
            var updatedSoldAmount = currSoldAmount + obj.soldAmount;

            //clear current text
            amount.text(currAmount - obj.soldAmount);
            sold_amount.text("");
            sold_amount.text("(" + updatedSoldAmount + ")");

            //make background flash to mark it has been updated. Fadeout after 10sec
            td.removeClass().width();
            td.addClass("UpdateNotify");

            //set background based on currAmount
            var newV = currAmount - obj.soldAmount;
            if(newV <= 5)
                td.addClass("bg-danger");
            else if (newV <= 10)
                td.addClass("bg-warning");
            else
                td.addClass("bg-success");
        }
    });

    var ii = window.Echo.private('update.error.{{$user->event()->id}}').listen('SalesUpdated', function(e) {
        console.log(e);
    });

</script>
@endpush