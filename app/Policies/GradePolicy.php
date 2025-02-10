<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grade;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the grade.
     */
    public function update(User $user, Grade $grade)
    {
        // Check if the user is a teacher and teaches the subject
        if ($user->teacher) {
            return $user->teacher->subjects()
                ->whereHas('grades', function($query) use ($grade) {
                    $query->where('id', $grade->id);
                })
                ->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create grades.
     */
    public function create(User $user)
    {
        // Allow if the user is a teacher
        return $user->teacher !== null;
    }

    /**
     * Determine whether the user can delete grades.
     */
    public function delete(User $user, Grade $grade)
    {
        return $this->update($user, $grade);
    }
} 