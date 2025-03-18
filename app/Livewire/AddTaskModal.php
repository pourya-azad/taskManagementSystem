<?php

namespace App\Livewire;

use App\Events\TaskUpdated;
use App\Jobs\criticalJob;
use App\Models\Task;
use Livewire\Attributes\Rule;
use Livewire\Component;

class AddTaskModal extends Component
{
    public $mode = 'create';
    protected $listeners = ['openModal' => 'handleOpenModal'];
    public $showModal = false;
    public $status = 'toDo';
    public $itemId;

    #[Rule('required|min:5|max:255')]
    public $taskTitle;

    #[Rule('nullable')]
    public $taskDescription;

    #[Rule('nullable|date')]
    public $taskDueDate;

    #[Rule('required|string')]
    public $taskPriority = 'medium';


    public function handleOpenModal($arr)
    {
        $this->showModal = true;
        $this->mode = $arr[0];


        if ($arr[0] === 'create'){
            switch ($arr[1]){
                case 0:
                    $this->status = 'toDo';
                    break;
                case 1:
                    $this->status = 'inProgress';
                    break;
                case 2:
                    $this->status = 'done';
                    break;
            }
        } else {
            $this->itemId = $arr[1]['id'];
            $this->taskTitle = $arr[1]['title'];
            $this->taskDescription = $arr[1]['desc'] ?? null;
            $this->taskDueDate = $arr[1]['date'] ?? null;
            $this->taskPriority = $arr[1]['priority'];
        }
    }

    public function closeModal()
    {
        $this->reset();
    }

    public function addTask()
    {
        $validated = $this->validate();

        if ($this->mode === 'create') {
            // آیتم جدید را ایجاد کن
            $task = Task::create([
                'title' => $validated['taskTitle'],
                'desc' => $validated['taskDescription'],
                'priority' => $validated['taskPriority'],
                'created_at' => $validated['taskDueDate'],
                'status' => $this->status,
            ]);
 
        } elseif ($this->mode === 'edit') {
            // آیتم موجود را آپدیت کن
            $task = Task::find($this->itemId);

            $task->update([
            'title' => $validated['taskTitle'],
            'desc' => $validated['taskDescription'],
            'priority' => $validated['taskPriority'],
            'created_at' => $validated['taskDueDate'],
            ]);
        }

        if ($task->priority === 'high')
            dispatch((new criticalJob($task)))->onQueue('critical');
        else
            dispatch((new criticalJob($task)))->onQueue('default');



        $this->dispatch('taskCreated');
        $this->closeModal();
        $this->dispatch('taskAdded'); // Optional: Emit an event to update task lists
    }

    public function render()
    {
        return view('livewire.add-task-modal');
    }

}


