@foreach($searchByDetail as $th)
    @if(!isset($th['hide']))
        <x-mazer-th :orderby="$orderByDetail" :order="$orderDetail" :field="$th['field']">
            {{ $th['name'] }}
        </x-maz-th>
    @endif
@endforeach
