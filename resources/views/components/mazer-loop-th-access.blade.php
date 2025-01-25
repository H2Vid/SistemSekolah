@foreach($searchByMenu as $th)
    @if(!isset($th['hide']))
        <x-mazer-th :orderby="$orderByMenu" :order="$orderMenu" :field="$th['field']">
            {{ $th['name'] }}
        </x-maz-th>
    @endif
@endforeach
