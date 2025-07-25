<?php

namespace Filament\Forms\Components\Actions;

use Filament\Forms\Components\Component;

class ActionContainer extends Component
{
    protected string $view = 'filament-forms::components.actions.action-container';

    protected Action $action;

    final public function __construct(Action $action)
    {
        $this->action = $action;
        $this->registerActions([$action]);
    }

    public static function make(Action $action): static
    {
        return app(static::class, ['action' => $action]);
    }

    public function getKey(): string
    {
        return "{$this->action->getName()}Action";
    }

    public function isHidden(): bool
    {
        return $this->action->isHidden();
    }
}
