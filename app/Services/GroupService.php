<?php

namespace App\Services;

use App\Models\Group;
use App\Repositories\GroupRepo;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{
    /**
     * 
     * @var GroupRepo
     */
    protected GroupRepo $repo;

    /**
     * 
     * @param GroupRepo $repo 
     * @return void 
     */
    public function __construct(GroupRepo $repo) {
        $this->repo = $repo;
    }

    /**
     * 
     * @return Collection<mixed, Group> 
     */
    public function list()
    {
        return $this->repo->getGroups();
    }
}
