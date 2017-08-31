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
                                    <td class="bg-danger">
                                @elseif ($prod->pivot->amount >= 5 && $prod->pivot->amount < 30)
                                    <td class="bg-warning">
                                @else
                                    <td class="bg-success">
                                @endif
                                    @if ($prod != null)
                                        <strong>{{ $prod->pivot->amount }}</strong>
                                        @if (!$storage->depot)
                                            <small class="pull-right">({{$prod->pivot->sold_amount}})</small>
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
                @if($user->Event()->activeAPI)
                    <div class="col-md-2">
                        <div class="jumbotron">
                            <?php
                            echo BootForm::open()->post()->action(route('izettle.deactivateAPI', $user->Event()->eventID));
                            echo BootForm::hidden('_action')->value('deactivate_api');
                            echo Button::submit()->danger()->withValue('Deactivate Sales API');
                            echo BootForm::close();
                            ?>
                        </div>
                    </div>
                @else
                    <div class="col-md-2">
                        <div class="jumbotron">
                            <?php
                            echo BootForm::open()->post()->action(route('izettle.activateAPI', $user->Event()->eventID));
                            echo BootForm::hidden('_action')->value('activate_api');
                            echo Button::submit()->danger()->withValue('Activate Sales API');
                            echo BootForm::close();
                            ?>
                        </div>
                    </div>
                @endif
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

    window.Echo.private('update.sales.{{$user->event()->id}}')
            .listen('SalesUpdated', (e) => {
                console.log(e);
    });

</script>
@endpush