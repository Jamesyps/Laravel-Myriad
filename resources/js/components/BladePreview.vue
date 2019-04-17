<template>
    <div class="text-xs font-mono overflow-x-auto whitespace-nowrap">
        @component('{{ path }}')<br>

        {{ defaultSlotValue }}<br>

        <template v-for="value, slot in namedSlots">
            @slot('{{ slot }}') {{ value }} @endslot<br>
        </template>

        @endcomponent

        <div v-for="value, variable in variables" class="pt-4">
            @component('{{ path }}', ['{{ variable }}' => '{{ value }}'])<br>

            {{ defaultSlotValue }}<br>

            <template v-for="value, slot in namedSlots">
                @slot('{{ slot }}') {{ value }} @endslot<br>
            </template>

            @endcomponent
        </div>

    </div>
</template>

<script>
export default {
    props: {
        path: {
            type: String,
            required: true,
        },
        slots: {
            type: [Object, Array],
            default: {}
        },
        variables: {
            type: [Object, Array],
            default: {},
        },
    },

    computed: {
        defaultSlotValue() {
            return this.slots['default'];
        },

        namedSlots() {
            delete this.slots['default'];

            return this.slots;
        },

    },
}
</script>
