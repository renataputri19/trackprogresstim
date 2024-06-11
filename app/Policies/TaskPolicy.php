<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    // Determine if the given task can be updated by the user
    public function update(User $user, Task $task)
    {
        return $user->id === $task->user_id;
    }

    // Determine if the given task can be deleted by the user
    public function delete(User $user, Task $task)
    {
        return $user->id === $task->user_id;
    }
}
