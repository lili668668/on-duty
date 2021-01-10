<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;

class GroupRepo
{
    /**
     * 
     * @return Collection<mixed, Group> 
     */
    public function getGroups()
    {
        return Group::all();
    }
}
