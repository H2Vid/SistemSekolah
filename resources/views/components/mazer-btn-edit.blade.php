
<button
    title="Ubah"
    class="btn btn-warning"
    wire:click="edit({{$d->id }})"
    @click="new bootstrap.Modal(document.getElementById('mazer-modal')).show()"
>
    <i class="align-middle" data-feather="edit"></i>
</button>