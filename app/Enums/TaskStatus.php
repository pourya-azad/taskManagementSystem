<?php

namespace App\Enums;

enum TaskStatus : string
{
    case DONE = "done";
    case INPROGRESS = "inProgress";
    case TODO = "toDo";
}
