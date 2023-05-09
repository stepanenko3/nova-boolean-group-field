<template>
    <DefaultField
        :field="currentField"
        :errors="errors"
        :full-width-content="true"
        :show-help-text="showHelpText"
    >
        <template #field>
            <DefaultButton @click="toggleAll" class="mb-5">
                {{ __('Toggle All') }}
            </DefaultButton>

            <div class="grid md:grid-cols-12 gap-4">
                <div
                    class="md:col-span-4 space-y-2"
                    v-for="(group, key) in groups"
                >
                    <h3 class="uppercase tracking-wide cursor-pointer font-bold text-xs" @click="() => toggleGroup(group)">
                        {{ key || "Main" }}
                    </h3>

                    <CheckboxWithLabel
                        v-for="option in group"
                        :key="option.name"
                        :name="option.name"
                        :checked="option.checked"
                        @input="toggle(option, $event.target.checked)"
                        :disabled="currentlyIsReadonly"
                    >
                        <span>{{ option.label }}</span>
                    </CheckboxWithLabel>
                </div>
            </div>
        </template>
    </DefaultField>
</template>

<script>
import fromPairs from "lodash/fromPairs";
import map from "lodash/map";
import merge from "lodash/merge";
import groupBy from "lodash/groupBy";
import find from "lodash/find";

import { DependentFormField, HandlesValidationErrors } from "laravel-nova";

export default {
    mixins: [HandlesValidationErrors, DependentFormField],

    data: () => ({
        value: {},
    }),

    methods: {
        setInitialValue() {
            let values = merge(
                this.currentField.value || {},
                this.finalPayload
            );

            this.value = map(this.currentField.options, (o) => {
                return {
                    name: o.name,
                    label: o.label,
                    checked: values[o.name] || false,
                };
            });
        },

        fill(formData) {
            this.fillIfVisible(
                formData,
                this.field.attribute,
                JSON.stringify(this.finalPayload)
            );
        },

        toggle(option, checked = true) {
            const firstOption = find(this.value, (o) => o.name === option.name);
            firstOption.checked = checked;

            if (this.field) {
                this.emitFieldValueChange(
                    this.field.attribute,
                    JSON.stringify(this.finalPayload)
                );
            }
        },

        toggleAll(e) {
            e.preventDefault();
            e.stopPropagation();

            this.toggleGroup(this.value);
        },

        toggleGroup(group) {
            const values = Object.values(group);
            const checkedCount = values.filter(o => o.checked).length;
            const totalCount = values.length;

            if (checkedCount < totalCount) {
                values.map(o => this.toggle(o, true));
            } else if (checkedCount > 0 && checkedCount === totalCount) {
                values.map(o => this.toggle(o, false))
            }
        },

        onSyncedField() {
            this.setInitialValue();
        },
    },

    computed: {
        finalPayload() {
            return fromPairs(map(this.value, (o) => [o.name, o.checked]));
        },

        groups() {
            return groupBy(this.value, function (item) {
                return item.name.split(".").slice(0, -1).join(".");
            });
        },
    },
};
</script>
