<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function update(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    public function delete(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }
}
