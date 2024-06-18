<script setup>
import EventBookingLayout from '@/Layouts/EventBookingLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

    // Define props
    const propsData = defineProps({
      events: {
        type: Object,
      },
      eventId: {
        type: Number, // Adjust type based on your data structure
        default: null,
      },
    });

    //const id = propsData.events.id;
    const id = propsData.eventId;
    let maxTickets = propsData.events.data.max_tickets_per_customer
    const ticketsLeft = propsData.events.data.ticket_allocation


    // Create an array of numbers from 1 to maxTickets
    if(ticketsLeft <= maxTickets){
        maxTickets = ticketsLeft;
    }
    const maxTicketsOptions = [];
    for (let i = 1; i <= maxTickets; i++) {
        maxTicketsOptions.push(i);
    }

    // Use useForm to create reactive form data
    const form = useForm({
    event_id: id,
    first_name: '',
    last_name: '',
    email: '',
    number_of_tickets: 1,
    max_number_of_tickets: maxTickets
});

const submit = () => {
    form.post(route('event.book'));
};
//{{ events.data[0].id }}
//{{ eventId}}
</script>

<template>
    
        <div >
            <div class="mx-auto max-w-7xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">
                                Book Event Tickets
                            </h1>
                            <p class="mt-2 text-sm text-gray-700">
                            </p>
                        </div>

                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">

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
                                                <th
                                                    scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                                >
                                                    Tickets Left
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="event in events"
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
                                                <td
                                                    class="whitespace-nowrap px-3 py-4 text-sm text-gray-500"
                                                >
                                                    {{ event.ticket_allocation }}
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
        <EventBookingLayout>
        <div>
        <form @submit.prevent="submit">
            <div>
                
                <input
                    type="hidden"
                    v-model="form.event_id"
                    id="event_id"
                />
                <InputLabel for="first_name" value="First Name" />
                
                
                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="last_name" value="Last Name" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autocomplete="last_name"
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
                
            </div>

            <div class="mt-4">
                <InputLabel for="number_of_tickets" value="Number Of Tickets" />
                <select id="number-select" v-model="form.number_of_tickets" class="mt-1 block w-full">
                    <option v-for="number in maxTicketsOptions" :key="number" :value="number" class="mt-1 block w-full">
                        {{ number }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.number_of_tickets" />
            </div>

            <div class="flex items-center justify-end mt-4">
                

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Book Tickets
                </PrimaryButton>
            </div>
        </form>
    </div>
    </EventBookingLayout>
</template>
