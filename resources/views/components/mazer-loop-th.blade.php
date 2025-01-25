@foreach($searchBy as $th)
    @if(!isset($th['hide']))
        <x-mazer-th :orderby="$orderBy" :order="$order" :field="$th['field']">
            {{ $th['name'] }}
        </x-maz-th>
    @endif
@endforeach
