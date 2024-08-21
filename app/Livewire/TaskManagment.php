<?php

namespace App\Livewire;

use App\Events\reRender;
use App\Events\TaskUpdated;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use mysql_xdevapi\Exception;


class TaskManagment extends Component
{
    use LivewireAlert;
    protected $listeners = [
    'taskCreated' => 'render',
    'taskUpdated'=>'flashMessage',
    'reSortArray'=>'reSortArray',
    'flashMessage'=>'flashMessage'
    ];

    public $itemId;
    public $mode = 'create'; // Mode to determine if we're creating or editing
    public $itemData = []; // Data to send to modal for editing

    public function addItem($status)
    {
        $this->mode = 'create';
        $this->itemData = [];
        $this->dispatch('openModal', [$this->mode, $status] );
    }



    public function taskEdit($itemId)
    {
        $this->mode = 'edit';
        $this->itemId = $itemId;
        $this->itemData = Task::find($itemId)->toArray();
        $this->dispatch('openModal', [$this->mode, $this->itemData] );
    }


    public function reSortArray($status, $item)
    {
        Task::whereIn('id', $item)->update(['status' => $status]);

        event(new TaskUpdated('reRender'));
        $this->reset();
    }

    public function taskDelete($id)
    {
        try {
            Task::findOrFail($id)->delete();
        }catch (Exception $e){
            Log::error($e);

            $this->alert('error', 'خطا', [
                'position' => 'top',
                'timer' => 3000,
                'toast' => true,
                'text' => 'تسک از قبل حذف شده است',
                'timerProgressBar' => false,
            ]);
            $this->render();
        }

        event(new TaskUpdated('reRender'));
        $this->reset();
    }

    public function flashMessage($msg)
    {
        $msg = trim($msg, '"');

        if($msg != 'reRender'){
        $this->alert('warning','اطلاعیه', [
            'position' => 'top',
            'timer' => 3000,
            'toast' => true,
            'text' => 'کار '. $msg . ' جدید ایجاد شده است',
        ]);
        }
        $this->render();
    }

    public function render()
    {
        $tasks = array(
            'toDo'=> Task::where('status', 'toDo')->orderByRaw("case priority when 'high' then 1 when 'medium' then 2 when 'low' then 3 end")->get(),
            'inProgress'=> Task::where('status', 'inProgress')->orderByRaw("case priority when 'high' then 1 when 'medium' then 2 when 'low' then 3 end")->get(),
            'done'=>  Task::where('status', 'done')->orderByRaw("case priority when 'high' then 1 when 'medium' then 2 when 'low' then 3 end")->get(),
        );

        return view('livewire.task-managment', compact('tasks'));
    }
}
