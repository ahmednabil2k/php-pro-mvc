<?php

namespace Framework\View;
trait HasManager
{
    protected Manager $manager;
    public function setManager(Manager $manager): static
    {
        $this->manager = $manager;
        return $this;
    }
}