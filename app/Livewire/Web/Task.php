<?php

namespace App\Livewire\Web;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Task as TaskModel;

use Livewire\Component;

#[Layout('layouts.web')]
class Task extends Component
{
    #[Title('Task')]
    public $list = [];
    public function render()
    {
        if(session()->get('language_id') == null){
            $this->list = TaskModel::join('task_details','task_details.task_id', '=', 'tasks.id')
                                ->join('languages','task_details.language_id', '=', 'languages.id')
                                ->where('tasks.store_id', session()->get('store_id'))
                                ->where('languages.status', 1)
                                ->select('tasks.id','tasks.level','tasks.fee','tasks.total_member','task_details.name','task_details.description','tasks.created_at','tasks.updated_at')
                                ->orderBy('tasks.level', 'asc')
                                ->get();
        }else{
            $this->list = TaskModel::join('task_details','task_details.task_id', '=', 'tasks.id')
                                ->join('languages','task_details.language_id', '=', 'languages.id')
                                ->where('tasks.store_id', session()->get('store_id'))
                                ->where('languages.id', session()->get('language_id'))
                                ->select('tasks.id','tasks.level','tasks.fee','tasks.total_member','task_details.name','task_details.description','tasks.created_at','tasks.updated_at')
                                ->orderBy('tasks.level', 'asc')
                                ->get();
        }
        
        return view('livewire.web.task');
    }
}
