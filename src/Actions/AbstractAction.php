<?php

namespace App\Actions;

use App\Contracts\ActionContract;

abstract class AbstractAction implements ActionContract
{
    protected bool $success;
    protected string $message;
    protected array $data = [];

    public function isSuccess(): bool
    {
        return $this->success;
    }

    protected function setSuccess(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    protected function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function setData(array $data)
    {
        $this->data = $data;
    }
}
