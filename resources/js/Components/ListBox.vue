<template>
    <Listbox
        as="div"
        v-model="selected"
        @update:modelValue="value => emit('update:modelValue', value)"
    >
        <div class="relative mt-1">
            <ListboxButton class="relative w-full cursor-default rounded-md border border-gray-300 bg-white pl-3 pr-10 text-left shadow-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 sm:text-sm h-10">
                <span class="flex items-center">
                    <span class="block truncate">{{ selected.name }}</span>
                </span>
                <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </span>
            </ListboxButton>

            <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <ListboxOptions class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <ListboxOption
                        as="template"
                        v-for="item in listboxItems"
                        :key="item.id"
                        :value="item"
                        v-slot="{ active, selected }"
                    >
                        <li :class="[active ? 'text-white bg-blue-600' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-3 pr-9']">
                            <div class="flex items-center">
                                <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{ item.name }}</span>
                            </div>

                            <span v-if="selected" :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                            <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>

<script setup>
    import { ref, computed, watch } from 'vue'
    import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'
    import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

    const props = defineProps({
        items: {
            type: Array,
            required: true,
            default: [],
        },
        modelValue: null,
        resetIndex: {
            type: Boolean,
            default: false,
        },
    })

    const emit = defineEmits(['update:modelValue', 'update:resetIndex'])

    const initalItem = [
        {
            id: null,
            name: 'Select',
        }
    ]

    const listboxItems = computed(() => [...initalItem, ...props.items])

    let selected = ref(listboxItems.value[0])

    if (!!props.modelValue) {

        const selectedIndex = listboxItems.value.findIndex(item => item.id == parseInt(props.modelValue))

        selected.value = selectedIndex < 1 ? listboxItems.value[0] : listboxItems.value[selectedIndex]
    }

    // watch(() => _.cloneDeep(props.items), (newValue, oldValue) => {

    //     if (!!props.modelValue) {

    //     const selectedIndex = listboxItems.value.findIndex(item => item.id == parseInt(props.modelValue))

    //     selected.value = selectedIndex < 1 ? listboxItems.value[0] : listboxItems.value[selectedIndex]
    //     }
    // })

    // watch(() => _.cloneDeep(props.resetIndex), (newValue, oldValue) => {
    //     selected.value = listboxItems.value[0]
    //     emit('update:resetIndex', false)
    // })
</script>
