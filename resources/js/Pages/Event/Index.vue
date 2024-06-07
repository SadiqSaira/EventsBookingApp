<script setup>
import MagnifyingGlass from "@/Components/Icons/MagnifyingGlass.vue";
//import Pagination from "@/Components/Pagination.vue";
//import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

defineProps({
    events: {
        type: Object,
    },
});

let searchByCountry = ref("");
let searchByDate = ref("");


let EventsUrl = computed(() => {
    const url = new URL(route("events.index"));

    if (searchByCountry.value) {
        console.log(searchByCountry.value);
        url.searchParams.set("searchByCountry", searchByCountry.value);
    }
    if (searchByDate.value) {
        console.log(searchByDate.value);
        url.searchParams.set("searchByDate", searchByDate.value);
    }

    return url;
});

watch(
    () => EventsUrl.value,
    (newValue) => {
        router.visit(newValue, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }
);

const search = () => {
    if (searchByDate.value && searchByDate.value.length === 2) {
        const startDate = searchByDate.value[0].toISOString().split('T')[0];
        const endDate = searchByDate.value[1].toISOString().split('T')[0];
        const searchParams = {
            searchByDate: `${startDate} to ${endDate}`,
        };
    } 
}
</script>

<template>
    <Head title="Events" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Events
            </h2>
        </template>
        <div class="bg-gray-100 py-10">
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">
                                Events
                            </h1>
                            <p class="mt-2 text-sm text-gray-700">
                                A list of all the Events.
                            </p>
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">

                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row mt-6">
                        <div class="relative text-sm text-gray-800 sm:w-1/3 mr-4">
                            <div class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500">
                                <MagnifyingGlass />
                            </div>
                            <input
                                type="text"
                                v-model="searchByCountry"
                                placeholder="Events By Country..."
                                id="searchByCountry"
                                class="block w-3/4 rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                        </div>
                        <div class="relative text-sm text-gray-800 sm:w-1/3">
                            <div class="absolute pl-2 left-0 top-0 bottom-0 flex items-center pointer-events-none text-gray-500">
                                <MagnifyingGlass />
                            </div>
                            <flat-pickr 
                                v-model="searchByDate" 
                                :config="{
                                    mode: 'range',
                                    altInput: true,
                                    altFormat: 'F j, Y',
                                    dateFormat: 'Y-m-d'
                                }" 
                                placeholder="Events By Date"
                                class="block w-3/4 rounded-lg border-0 py-2 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            ></flat-pickr>
                        </div>    
                    </div>

                    <div class="mt-8 flex flex-col">
                        <div
                            class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8"
                        >
                            <div
                                class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
                            >
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    Event Name
                                            
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    
                                                    Start Date
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                >
                                                    End Date
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    City
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Country
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="event in events.data"
                                                :key="event.id"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{ event.event_name }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                >
                                                    {{ event.start_datetime }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ event.end_datetime }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ event.city }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ event.country }}
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>