<?php

namespace App\Traits;

trait WithEditAction {
    public function edit($id) {
        $this->isUpdate = true;
        $this->form->getDetail($id);
    }

    public function editCabang($id) {
        $this->isUpdate = true;
        $this->formCabang->getDetail($id);
    }

    public function editAccess($id) {
        $this->isUpdate = true;
        $this->form->getDetailAccess($id);
    }
}
