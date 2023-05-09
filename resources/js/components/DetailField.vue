<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div v-if="value.length > 0" class="grid md:grid-cols-12 gap-4">
                <div
                    class="md:col-span-4 space-y-2"
                    v-for="(group, key) in groups"
                >
                    <h3 class="uppercase tracking-wide cursor-pointer font-bold text-xs" @click="() => toggleGroup(group)">
                        {{ key || "Main" }}
                    </h3>

                    <ul class="space-y-2">
                        <li
                            v-for="option in group"
                            :class="classes[option.checked]"
                            class="flex items-center rounded-full font-bold text-sm leading-tight space-x-2"
                        >
                            <IconBoolean class="flex-none" :value="option.checked" />
                            <span>{{ option.label }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <span v-else>{{ this.field.noValueText }}</span>
        </template>
    </PanelItem>
</template>

<script>
import filter from "lodash/filter";
import map from "lodash/map";
import groupBy from "lodash/groupBy";

export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"],

    data: () => ({
        value: [],
        groups: {},
        classes: {
            true: "text-green-500",
            false: "text-red-500",
        },
    }),

    created() {
        this.field.value = this.field.value || {};

        this.value = filter(
            map(this.field.options, (o) => {
                return {
                    name: o.name,
                    label: o.label,
                    checked: this.field.value[o.name] || false,
                };
            }),
            (o) => {
                if (
                    this.field.hideFalseValues === true &&
                    o.checked === false
                ) {
                    return false;
                } else if (
                    this.field.hideTrueValues === true &&
                    o.checked === true
                ) {
                    return false;
                }

                return true;
            }
        );

        this.groups = groupBy(this.value, function (item) {
            return item.name.split(".").slice(0, -1).join(".");
        });
    },
};
</script>
