<?php 

namespace App\Helpers;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;
use App\Models\Comment;
use App\Models\Task;

class NotificationHelper
{
    public static function getUsersToNotify(Model $model)
    {
        $adminUsers = User::where('role', 'admin')->get();

        // Detect owner-like user
        if (method_exists($model, 'owner')) {
            $ownerOrAssignee = $model->owner;
        } elseif (method_exists($model, 'assignee')) {
            $ownerOrAssignee = $model->assignee;
        } else {
            $ownerOrAssignee = null;
        }

        // Detect members-like users
        if (method_exists($model, 'members')) {
            $users = $model->members;
        } elseif (method_exists($model, 'collaborators')) {
            $users = $model->collaborators;
        } else {
            $users = collect();
        }

        $allUsers = $adminUsers->merge($users);

        if ($ownerOrAssignee) {
            $allUsers->push($ownerOrAssignee);
        }

        return $allUsers->unique('id');
    }
}