<?php

namespace Filament\Forms\Components;

use Filament\Forms\Concerns\HasColumns;
use Filament\Forms\Concerns\HasStateBindingModifiers;
use Filament\Support\Components\ViewComponent;
use Filament\Support\Concerns\HasExtraAttributes;

class Component extends ViewComponent
{
    use Concerns\BelongsToContainer;
    use Concerns\BelongsToModel;
    use Concerns\CanBeConcealed;
    use Concerns\CanBeDisabled;
    use Concerns\CanBeHidden;
    use Concerns\CanSpanColumns;
    use Concerns\Cloneable;
    use Concerns\HasActions;
    use Concerns\HasChildComponents;
    use Concerns\HasFieldWrapper;
    use Concerns\HasInlineLabel;
    use Concerns\HasId;
    use Concerns\HasLabel;
    use Concerns\HasMaxWidth;
    use Concerns\HasMeta;
    use Concerns\HasState;
    use Concerns\ListensToEvents;
    use HasColumns;
    use HasExtraAttributes;
    use HasStateBindingModifiers;

    protected string $evaluationIdentifier = 'component';

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultEvaluationParameters(): array
    {
        $operation = $this->getContainer()->getOperation();

        return array_merge(parent::getDefaultEvaluationParameters(), [
            'context' => $operation,
            'get' => $this->getGetCallback(),
            'livewire' => $this->getLivewire(),
            'model' => $this->getModel(),
            'operation' => $operation,
            'record' => $this->getRecord(),
            'set' => $this->getSetCallback(),
            'state' => $this->resolveEvaluationParameter(
                'state',
                fn (): mixed => $this->getState(),
            ),
        ]);
    }

    public function getKey(): ?string
    {
        return null;
    }
}
