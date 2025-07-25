<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasNestedRecursiveValidationRules
{
    /**
     * @var array<mixed>
     */
    protected array $nestedRecursiveValidationRules = [];

    /**
     * @param  string | array<mixed>  $rules
     */
    public function nestedRecursiveRules(string | array $rules, bool | Closure $condition = true): static
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->nestedRecursiveValidationRules = array_merge(
            $this->nestedRecursiveValidationRules,
            array_map(static fn (string | object $rule) => [$rule, $condition], $rules),
        );

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getNestedRecursiveValidationRules(): array
    {
        $rules = [];

        foreach ($this->nestedRecursiveValidationRules as [$rule, $condition]) {
            if (is_numeric($rule)) {
                $rules[] = $this->evaluate($condition);
            } elseif ($this->evaluate($condition)) {
                $rules[] = $this->evaluate($rule);
            }
        }

        return $rules;
    }
}
