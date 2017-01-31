@extends('layout.master')

@section('content')

    <?php $storages = $event->storages()->orderBy('depot', 'DESC')->get(); ?>


    @if($errors->all() != null || count($errors->all()) > 0)
        <div class="alert-danger alert">
            <strong>Error! </strong> Error occured, please check modal
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
        <div class="col-md-9">
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
                        @foreach ($event->products()->get() as $product)
                        <tr>
                            <td>{{ $product->name }}</td>

                            @foreach ($event->storages()->get() as $storage)
                                <?php $prod = $storage->products->where('id', $product->id)->first(); ?>

                                @if($prod->pivot->amount < 5)
                                    <td class="bg-danger">
                                @elseif ($prod->pivot->amount > 5 && $prod->pivot->amount < 30)
                                    <td class="bg-warning">
                                @else
                                    <td class="bg-success">
                                @endif
                                    @if ($prod != null)
                                        {{ $prod->pivot->amount }} (<small>{{$prod->pivot->sold_amount}}</small>)
                                    @endif
                                    </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-3">
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

        <div class="col-md-3">
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
    </div>
@endsection