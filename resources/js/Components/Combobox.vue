<template>
    <Combobox
        v-model="selected"
        nullable
        @update:modelValue="value => emit('update:modelValue', value)"
    >
        <div class="relative mt-1">
            <div
                class="relative w-full"
            >
                <ComboboxInput
                    class="w-full border-gray-300 focus:border-blue-600 focus:ring-blue-600 rounded-md shadow-sm text-sm placeholder:text-gray-400 h-10"
                    :displayValue="(person) => person?.name"
                    @change="updateQuery($event.target.value)"
                    :placeholder="placeholder"
                    autocomplete="off"
                />
                <button
                    v-if="query != '' || selected != null"
                    @click="selected = null"
                    type="button"
                    class="absolute inset-y-0 right-7 flex items-center px-1"
                >
                    <XMarkIcon
                        class="h-5 w-5 text-gray-400"
                    />
                </button>
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center px-2"
                >
                    <ChevronUpDownIcon
                    class="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                    />
                </ComboboxButton>
            </div>
            <TransitionRoot
                leave="transition ease-in duration-100"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                @after-leave="query = ''"
            >
            <ComboboxOptions
                class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            >
                <div
                    v-if="filteredSupplier.length === 0 && query !== ''"
                    class="relative cursor-default select-none py-2 px-4 text-gray-700"
                >
                    No options match your search.
                </div>

                <ComboboxOption
                    v-for="(person, index) in filteredSupplier"
                    as="template"
                    :key="index"
                    :value="person"
                    v-slot="{ selected, active }"
                >
                    <li
                        class="relative cursor-pointer select-none py-2 pl-3 pr-4"
                        :class="{
                            'bg-blue-600 text-white': active,
                            'bg-blue-100 text-blue-700': selected,
                            'text-gray-900': !selected
                        }"
                    >
                        <span
                            class="block truncate"
                            :class="{ 'font-medium': selected, 'font-normal': !selected }"
                        >
                            {{ person.name }}
                        </span>
                        <span
                            v-if="selected"
                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                            :class="{ 'text-white': active, 'text-blue-600': !active }"
                        >
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                        </span>
                    </li>
                </ComboboxOption>
            </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import {
    CheckIcon,
    ChevronUpDownIcon,
    XMarkIcon,
} from '@heroicons/vue/20/solid'
import axios from 'axios';

const props = defineProps({
    items: {
        type: Array,
        default: [],
    },
    ajaxUrl: {
        type: String,
    },
    placeholder: {
        type: String,
        default: 'Type to search...',
    }
});

const items = ref(props.items)
let selected = ref(null)
let query = ref('')
let debounce = ref(null)

const emit = defineEmits(['update:modelValue'])

let filteredSupplier = computed(() =>
    query.value === ''
        ? items.value
        : items.value.filter((person) =>
            person.name
            .toLowerCase()
            .replace(/\s+/g, '')
            .includes(query.value.toLowerCase().replace(/\s+/g, ''))
        )
)

let searchSuppliers = () => {
    axios.get(props.ajaxUrl + query.value, {
        //
    })
    .then(response => {
        if (response.data.length) {
            items.value = response.data
        }
    })
}

let updateQuery = (newQuery) => {
    if (newQuery && newQuery !== '') {
        clearInterval(debounce.value)
        debounce.value = setTimeout(() => {
            query.value = newQuery
            searchSuppliers()
        }, 600)
    }
}

// Emit if current value is cleared
watch(selected, (newValue, oldValue) => {
    if (newValue == null) {
        emit('update:modelValue', null)
    }
})
</script>
