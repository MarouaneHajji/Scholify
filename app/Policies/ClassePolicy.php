<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Classe;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the class.
     */
    public function view(User $user, Classe $class)
    {
        // Simply return true if the teacher has this class
        return $user->teacher && $user->teacher->classes->contains($class->id);
    }

    /**
     * Determine whether the user can update grades for the class.
     */
    public function update(User $user, Classe $class)
    {
        return $user->teacher && $user->teacher->classes->contains($class->id);
    }
} 