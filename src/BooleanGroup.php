<?php

namespace Stepanenko3\NovaBooleanGroup;

use Illuminate\Support\Arr;
use Laravel\Nova\Contracts\FilterableField;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FieldFilterable;
use Laravel\Nova\Fields\Filters\BooleanGroupFilter;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class BooleanGroup extends Field implements FilterableField
{
    use FieldFilterable;
    use SupportsDependentFields;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-boolean-group-field';

    /**
     * The text to be used when there are no booleans to show.
     *
     * @var string
     */
    public $noValueText = 'No Data';

    /**
     * The options for the field.
     *
     * @var array
     */
    public $options;

    /**
     * Determine false values should be hidden.
     *
     * @var bool
     */
    public $hideFalseValues;

    /**
     * Determine true values should be hidden.
     *
     * @var bool
     */
    public $hideTrueValues;

    /**
     * Set the options for the field.
     *
     * @param array|\Closure():(array|\Illuminate\Support\Collection)|\Illuminate\Support\Collection $options
     *
     * @return $this
     */
    public function options(mixed $options): self
    {
        if (is_callable($options)) {
            $options = $options();
        }

        $this->options = with(
            value: collect($options),
            callback: fn ($options) => $options
                ->dot()
                ->map(
                    fn ($label, $name) => $options->isAssoc()
                        ? ['label' => $label, 'name' => $name]
                        : ['label' => $label, 'name' => $label],
                )
                ->values()
                ->undot()
                ->all(),
        );

        return $this;
    }

    /**
     * Whether false values should be hidden on the index.
     *
     * @return $this
     */
    public function hideFalseValues()
    {
        $this->hideTrueValues = false;
        $this->hideFalseValues = true;

        return $this;
    }

    /**
     * Whether true values should be hidden on the index.
     *
     * @return $this
     */
    public function hideTrueValues()
    {
        $this->hideTrueValues = true;
        $this->hideFalseValues = false;

        return $this;
    }

    /**
     * Set the text to be used when there are no booleans to show.
     *
     * @param string $text
     *
     * @return $this
     */
    public function noValueText($text)
    {
        $this->noValueText = $text;

        return $this;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter()
    {
        return transform($this->jsonSerialize(), function ($field) {
            $field['options'] = collect($field['options'])->transform(function ($option) {
                return [
                    'label' => $option['label'],
                    'value' => $option['name'],
                ];
            });

            return Arr::only($field, ['uniqueKey', 'options']);
        });
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'hideTrueValues' => $this->hideTrueValues,
            'hideFalseValues' => $this->hideFalseValues,
            'options' => $this->options,
            'noValueText' => Nova::__($this->noValueText),
        ]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute): void
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }
    }

    /**
     * Make the field filter.
     *
     * @return \Laravel\Nova\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new BooleanGroupFilter($this);
    }

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):void
     */
    protected function defaultFilterableCallback()
    {
        return function (NovaRequest $request, $query, $value, $attribute): void {
            $value = collect($value)->reject(fn ($value) => null === $value)->all();

            $query->when(!empty($value), fn ($query) => $query->whereJsonContains($attribute, $value));
        };
    }
}
