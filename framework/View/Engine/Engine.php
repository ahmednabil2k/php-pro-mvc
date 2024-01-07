<?php

namespace Framework\View\Engine;
use Framework\View\Manager;

interface Engine
{
    /**
     * @param View $view
     * @return string
     */
    public function render(View $view): string;

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setManager(Manager $manager): static;

}