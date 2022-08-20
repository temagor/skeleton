<?php

namespace App\Contracts;

use App\Entity\User;

interface UserActionContract extends ActionContract
{
    public function handle(): User;
}
