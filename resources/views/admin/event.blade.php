@extends('layouts.admin.default')

@section('content')

<div x-data="eventManager({{ $item->id }})">
    <div class="mb-6 flex flex-row justify-between items-center">
        <div class="flex flex-row items-center gap-5">
            <a href="{{ $backUrl }}" class="p-2 text-[#e9d5ff] hover:text-white rounded-lg bg-[#4a00e0] transition-colors duration-300 hover:shadow-[0_0_15px_rgba(168,85,247,0.4)] border border-transparent hover:border-white/20" title="Back">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white" x-text="formData.title">{{ $title }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Overview as of {{ now()->format('l, d F Y') }}
                </p>
            </div>
        </div>
        <div class="flex flex-col items-end gap-1">
            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Assigned Organizer</label>
            <select x-model="formData.user_id" @change="saveOrganizer()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300 min-w-[180px]">
                <option value="">Unassigned</option>
                @foreach($organizers as $orgId => $orgName)
                    <option value="{{ $orgId }}">{{ $orgName }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        {{-- Event Details --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-row items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Details</h3>
                <button type="button" @click="if(editing) { save() } else { editing = true }" 
                    class="transition-all duration-300 disabled:opacity-50"
                    :disabled="saving"
                    :class="editing ? 'inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] shadow-md hover:shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300'"
                >
                    <span x-show="!editing">Edit</span>
                    <span x-show="editing" class="flex items-center gap-2" style="display: none;">
                        <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="saving ? 'Saving...' : 'Save'"></span>
                    </span>
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event Name</label>
                        <p x-show="!editing" class="text-gray-900 dark:text-white font-medium" x-text="formData.title">{{ $item->title }}</p>
                        <input x-show="editing" type="text" x-model="formData.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <p x-show="!editing" class="text-gray-900 dark:text-white" x-text="categoryName">{{ $item?->category?->name ?? 'None' }}</p>
                        <select x-show="editing" x-model="formData.category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">
                            <option value="">Select Category</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" @if($item->category_id == $id) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                            <p x-show="!editing" class="text-gray-900 dark:text-white" x-text="formatDate(formData.start_time)"></p>
                            <input x-show="editing" type="datetime-local" x-model="formData.start_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                            <p x-show="!editing" class="text-gray-900 dark:text-white" x-text="formatDate(formData.end_time)"></p>
                            <input x-show="editing" type="datetime-local" x-model="formData.end_time" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                        <p x-show="!editing" class="text-gray-900 dark:text-white" x-text="formData.location">{{ $item->location }}</p>
                        <input x-show="editing" type="text" x-model="formData.location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <p x-show="!editing" class="text-gray-900 dark:text-white whitespace-pre-wrap" x-text="formData.description">{{ $item->description }}</p>
                        <textarea x-show="editing" rows="4" x-model="formData.description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" style="display: none;">{{ $item->description }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Event Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Event Settings</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" x-model="formData.status" @change="save()" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-600 focus:border-purple-600 dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quota</label>
                        <input type="number" name="quota" x-model.number="formData.quota" @change.debounce.500ms="save()" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-600 focus:border-purple-600 dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300">
                    </div>
                </div>
            </div>
        </div>

        {{-- Ticket Types --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket Types</h3>
                    <div class="text-xs px-2.5 py-1 rounded-md transition-colors duration-300" :class="totalTicketCapacity > formData.quota ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800'">
                        <span class="font-bold" x-text="totalTicketCapacity"></span> / <span x-text="formData.quota"></span> Quota
                    </div>
                </div>
                <button type="button" @click="addTicketType()" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 disabled:opacity-50 disabled:cursor-not-allowed" :disabled="totalTicketCapacity >= formData.quota">
                    + Add Ticket Type
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <template x-for="(ticket, index) in formData.ticket_types" :key="index">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            {{-- View mode --}}
                            <div x-show="!ticket.editMode">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white" x-text="getTicketTypeName(ticket.ticket_type_id) || 'Unknown Type'"></h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Ticket configuration</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-white" x-text="'Rp ' + parseFloat(ticket.price).toLocaleString('id-ID')"></p>
                                        <span class="text-xs text-green-600 dark:text-green-400">Available</span>
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div><span class="font-medium">Capacity:</span> <span x-text="ticket.capacity"></span></div>
                                </div>
                                <div class="mt-3 flex gap-2 justify-end">
                                    <button type="button" @click="ticket.editMode = true" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700">Edit</button>
                                    <button type="button" @click="removeTicketType(index)" class="text-xs font-medium text-red-500 dark:text-red-400 hover:text-red-700">Remove</button>
                                </div>
                            </div>
                            {{-- Edit mode --}}
                            <div x-show="ticket.editMode" style="display:none;">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Ticket Type</label>
                                        <select x-model="ticket.ticket_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                            <option value="">Select Type</option>
                                            @foreach($ticketTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Price (Rp)</label>
                                        <input type="number" step="0.01" x-model="ticket.price" placeholder="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Capacity</label>
                                        <input type="number" x-model.number="ticket.capacity" placeholder="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-end items-center">
                                    <span x-show="totalTicketCapacity > formData.quota" class="text-xs text-red-500 dark:text-red-400 mr-2">Exceeds total event quota!</span>
                                    <button type="button" :disabled="totalTicketCapacity > formData.quota" @click="if(totalTicketCapacity <= formData.quota) { ticket.editMode = false; saveTicketTypes(); }" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white rounded-lg bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Save
                                    </button>
                                    <button type="button" @click="cancelEdit(ticket, index)" class="text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 px-3 py-1.5">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="formData.ticket_types.length === 0" class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        No ticket types configured for this event.
                    </div>
                </div>
            </div>
        </div>

        
    </div>

    <div class="space-y-6">
        {{-- Quick Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Stats</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Tickets Sold</span>
                        <span class="font-semibold text-gray-900 dark:text-white">123</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Revenue</span>
                        <span class="font-semibold text-gray-900 dark:text-white">$6,027.00</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Active Orders</span>
                        <span class="font-semibold text-gray-900 dark:text-white">15</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="py-3 px-4 font-medium">Order ID</th>
                                <th class="py-3 px-4 font-medium">Customer</th>
                                <th class="py-3 px-4 font-medium">Ticket Type</th>
                                <th class="py-3 px-4 font-medium">Amount</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-900 dark:text-white">
                            <!-- Example Order -->
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-4 px-4">#ORD-001</td>
                                <td class="py-4 px-4">John Doe</td>
                                <td class="py-4 px-4">General Admission</td>
                                <td class="py-4 px-4">$49.00</td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Completed
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('eventManager', (eventId) => ({
            editing: false,
            saving: false,
            formData: {
                title: @json($item->title),
                start_time: '{{ $item?->start_time?->format("Y-m-d\TH:i") }}',
                end_time: '{{ $item?->end_time?->format("Y-m-d\TH:i") }}',
                category_id: {{ $item->category_id ?? 'null' }},
                user_id: '{{ $item->user_id ?? '' }}',
                location: @json($item->location),
                description: @json($item->description),
                status: @json($item->status),
                quota: {{ $item->quota }},
                ticket_types: @json($eventTicketTypesData),
            },
            categories: @json($categories),
            organizers: @json($organizers),
            allTicketTypes: @json($ticketTypesData),
            get totalTicketCapacity() {
                return this.formData.ticket_types.reduce((sum, ticket) => sum + (parseInt(ticket.capacity) || 0), 0);
            },
            get categoryName() {
                return this.categories[this.formData.category_id] || 'None';
            },
            getTicketTypeName(id) {
                const tt = this.allTicketTypes.find(t => t.id == id);
                return tt ? tt.name : null;
            },
            addTicketType() {
                this.formData.ticket_types.push({
                    id: null,
                    ticket_type_id: '',
                    price: 0,
                    capacity: 0,
                    editMode: true,
                    isNew: true
                });
            },
            cancelEdit(ticket, index) {
                if (ticket.isNew) {
                    this.formData.ticket_types.splice(index, 1);
                } else {
                    ticket.editMode = false;
                }
            },
            removeTicketType(index) {
                this.formData.ticket_types.splice(index, 1);
                this.saveTicketTypes();
            },
            async saveOrganizer() {
                await fetch(`/manage/events/${eventId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_id: this.formData.user_id || null })
                });
            },
            async saveTicketTypes() {
                const payload = this.formData.ticket_types.map(({ ticket_type_id, price, capacity }) => ({ ticket_type_id, price, capacity }));
                await fetch(`/manage/events/${eventId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ticket_types: payload })
                });
            },
            init() {
                // Initial update for text displays if needed
            },
            formatDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                // "d M Y, H:i A"
                const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
                // Using toLocaleDateString and replacing formatting issues
                // Note: manual formatting will perfectly match the blade standard, but here is a simple approach
                const dt = date.toLocaleString('en-GB', options).replace(',', '');
                return dt.toUpperCase(); // Ensure AM/PM matches Blade default
            },
            async save() {
                if (this.saving) return;
                
                if (this.totalTicketCapacity > this.formData.quota) {
                    alert('Cannot save event. Ticket capacities exceed total event quota.');
                    return;
                }

                this.saving = true;
                
                try {
                    const response = await fetch(`/manage/events/${eventId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });
                    
                    if (response.ok) {
                        this.editing = false;
                        // Data is now officially saved.
                    } else {
                        console.error('Save failed');
                    }
                } catch (error) {
                    console.error('Error saving event:', error);
                } finally {
                    this.saving = false;
                }
            }
        }));
    });
</script>
@endsection