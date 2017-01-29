@extends('layout.master')

@section('content')

    <?php $storages = $event->storages(); ?>


<table class="table table-striped">
    <thead>
    <tr>
        <th></th>
        @foreach ($storages as $storage)
            <th> {{$storage->name }} </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach ($event->products() as $product)
        <tr>
            <td>{{ $product->name }}</td>

            @foreach ($event->storages() as $storage)
                <td>
                    <?php $prod = $storage->products->where('id', $product->id)->first(); ?>

                    @if ($prod != null)
                        {{ $prod->pivot->amount }} <small>{{$prod->pivot->sold_amount}}</small>
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

@endsection