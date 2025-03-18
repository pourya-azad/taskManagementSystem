<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>سیستم مدیریت وظایف</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

        <style>
            .draggable {
                cursor: move;
            }
        </style>
    </head>
    <body class="bg-gray-100 text-gray-900">

        <!-- Container -->
        <div class="container mx-auto p-8">


            <!-- Kanban Board -->
                @livewire('taskManagment')
        </div>

        @livewire('addTaskModal')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js" integrity="sha512-TelkP3PCMJv+viMWynjKcvLsQzx6dJHvIGhfqzFtZKgAjKM1YPqcwzzDEoTc/BHjf43PcPzTQOjuTr4YdE8lNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        @livewireScripts
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
        <x-livewire-alert::scripts />


        <script>
        var sortable0 = new Sortable(column0, {
            group: 'shared', // set both lists to same group
            animation: 150,
            // Called by any change to the list (add / update / remove)
            onSort: function (/**Event*/evt) {
                var order = sortable0.toArray();
                Livewire.dispatchTo('task-managment','reSortArray', { status: 'toDo', item: order })
            },
        });

        var sortable1 = new Sortable(column1, {
            group: 'shared',
            animation: 150,
            // Called by any change to the list (add / update / remove)
            onSort: function (/**Event*/evt) {
                var order = sortable1.toArray();
                Livewire.dispatchTo('task-managment','reSortArray', { status: 'inProgress', item: order })
                console.log(order);
            },
        });

        var sortable2 = new Sortable(column2, {
            group: 'shared',
            animation: 150,
            // Called by any change to the list (add / update / remove)
            onSort: function (/**Event*/evt) {
                var order = sortable2.toArray();
                Livewire.dispatchTo('task-managment','reSortArray', { status: 'done', item: order })
                },
        });


        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('f36ce1d18e1d0fd89329', {
            cluster: 'mt1'
        });

        var channel = pusher.subscribe('tasks');
        channel.bind('taskNotification', function(data) {

            //re render
            Livewire.dispatchTo('task-managment','taskUpdated', [JSON.stringify(data.message)] )
        });

        </script>
    </body>
</html>
