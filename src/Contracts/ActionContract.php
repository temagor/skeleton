<?php

namespace App\Contracts;

interface ActionContract
{
    public function handle();
    public function isSuccess(): bool;
    public function getMessage(): string;
    public function getData(): array;
}
