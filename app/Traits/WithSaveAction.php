<?php

namespace App\Traits;

use App\Enums\Alert;
use Illuminate\Support\Facades\Request;

trait WithSaveAction {
    public $id_mata_pelajaran;

    public function save() {
        try {
            $this->form->save();

            $this->dispatch('closeModal', modal: 'mazer-modal');
            $this->dispatch('alert', type: Alert::success, message: $this->isUpdate ? 'Data Updated' : 'Data Created');
        } catch (\Exception $exception) {
            $this->dispatch('alert', type: Alert::error, message: $exception->getMessage());
        }
    }

    public function mount()
    {
        // Get the 'id' from the query string and assign it to the $id property
        $this->id_mata_pelajaran = request()->query('id');
    }

    public function savePredikat(){
        $this->form->save($this->id_mata_pelajaran);

        $this->dispatch('closeModal', modal: 'mazer-modal');
        $this->dispatch('alert', type: Alert::success, message: $this->isUpdate ? 'Data Updated' : 'Data Created');
    }


    public function saveCabang() {
        try {
            $this->formCabang->save();
            $this->dispatch('closeModal', modal: 'mazer-modal');
            $this->dispatch('alert', type: Alert::success, message: $this->isUpdate ? 'Data Updated' : 'Data Created');
        } catch (\Exception $exception) {
            $this->dispatch('alert', type: Alert::error, message: $exception->getMessage());
        }
    }
}
