@extends('layouts.default')

@section('content')
    {{-- $event is now passed from CheckoutController --}}

    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="/events"
                        class="text-indigo-600 dark:text-indigo-400 flex items-center gap-2 font-medium hover:underline mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Events
                    </a>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">Complete Your Booking</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Follow the steps to secure your tickets.</p>
                </div>

                <!-- Unified Stepper UI -->
                <div class="flex items-center gap-2 md:gap-4 font-semibold text-sm">
                    <div id="nav-step-1"
                        class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 transition-colors">
                        <div
                            class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            1</div>
                        <span class="hidden sm:block">Tickets</span>
                    </div>
                    <div class="w-8 md:w-12 h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                    <div id="nav-step-2" class="flex items-center gap-2 text-gray-400 dark:text-gray-600 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center"
                            id="nav-badge-2">2</div>
                        <span class="hidden sm:block">Details</span>
                    </div>
                    <div class="w-8 md:w-12 h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="flex items-center gap-2 text-gray-400 dark:text-gray-600">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center">
                            3</div>
                        <span class="hidden sm:block">Payment</span>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-12">
                    <!-- Left: Event Summary Mini -->
                    <div
                        class="col-span-1 md:col-span-4 bg-indigo-50 dark:bg-gray-800/50 p-6 md:p-8 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700 flex flex-col">
                        <span
                            class="px-3 py-1 bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wider rounded-lg shadow-sm">
                            {{ $event->category->name ?? 'Event' }}
                        </span>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-4 mb-2">{{ $event->title }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ Str::limit($event->description, 100) }}
                        </p>

                        <div class="space-y-4">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3 shadow-sm shadow-black/5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span>{{ \Carbon\Carbon::parse($event->start_time)->format('D, M d Y • h:i A') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                <div
                                    class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mr-3 shadow-sm shadow-black/5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span>{{ $event->location }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Form Wizard -->
                    <div class="col-span-1 md:col-span-8 p-6 md:p-8">
                        <form id="checkout-form" action="{{ route('checkout.store', $event->id) }}" method="POST"
                            class="relative">
                            @csrf
                            <!-- We POST to CheckoutController to create the order, which then redirects to payment -->

                            <!-- STEP 1: TICKETS -->
                            <div id="step-1-content" class="transition-opacity duration-300 w-full opacity-100">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Step 1: Select Tickets</h3>

                                <div class="space-y-6">
                                    <!-- Dynamic Ticket Types Wrapper -->
                                    <div id="ticket-selection-wrapper" class="space-y-4">
                                        <!-- Initial Row -->
                                        <div class="flex flex-col sm:flex-row gap-4 ticket-row items-end">
                                            <div class="flex-grow w-full">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ticket
                                                    Type</label>
                                                <select name="tickets[0][event_ticket_type_id]"
                                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-base ticket-type-select">
                                                    @foreach($event->eventTicketTypes as $tt)
                                                        @if($tt->ticketType)
                                                            <option value="{{ $tt->id }}">{{ $tt->ticketType->name }} - Rp
                                                                {{ number_format($tt->price, 0, ',', '.') }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-full sm:w-32 shrink-0">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount</label>
                                                <input type="number" name="tickets[0][qty]" value="1" min="1" max="10"
                                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-base ticket-qty-input"
                                                    required onchange="checkMaxTickets(this)"
                                                    oninput="checkMaxTickets(this)">
                                            </div>
                                            <div class="shrink-0">
                                                <button type="button" onclick="removeTicketRow(this)"
                                                    class="p-4 text-red-500 hover:text-red-700 transition self-end bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40"
                                                    style="display:none;" title="Remove this ticket type">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="mt-4 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                        <button type="button" onclick="addTicketRow()"
                                            class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 flex items-center gap-1 transition-colors">
                                            <div
                                                class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center mr-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            Add Another Type
                                        </button>
                                        <div
                                            class="text-sm text-gray-600 dark:text-gray-400 font-semibold px-4 py-2 bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                            Total: <span id="total-tickets-count"
                                                class="text-indigo-600 dark:text-indigo-400 font-bold">1</span> / 10 limit
                                        </div>
                                    </div>



                                    <div class="pt-8 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button" onclick="goToStep(2)"
                                            class="w-full py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transform hover:-translate-y-1 transition-all duration-300 flex justify-between items-center px-6">
                                            <span>Continue to Details</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 2: DETAILS -->
                            <div id="step-2-content" class="transition-opacity duration-300 w-full hidden opacity-0">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Step 2: Your Details</h3>

                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full
                                            Name</label>
                                        <input type="text" name="name" id="name-input" required placeholder="John Doe"
                                            value="{{ auth()->user()->name ?? '' }}"
                                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email
                                            Address</label>
                                        <input type="email" name="email" id="email-input" required
                                            placeholder="john@example.com" value="{{ auth()->user()->email ?? '' }}"
                                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <p class="text-xs text-gray-500 mt-2">We'll send your tickets to this email.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone
                                            Number</label>
                                        <input type="tel" name="phone" id="phone-input" required
                                            placeholder="+1 (555) 000-0000" value="{{ auth()->user()->phone ?? '' }}"
                                            class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    </div>

                                    <div
                                        class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col-reverse sm:flex-row gap-3">
                                        <button type="button" onclick="goToStep(1)"
                                            class="w-full sm:w-1/3 py-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                                            Back
                                        </button>
                                        <button type="button" onclick="validateAndSubmit()"
                                            class="w-full sm:w-2/3 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex justify-between items-center px-6">
                                            <span>Review Order</span>
                                            <span class="text-indigo-200 text-sm font-normal items-center flex gap-1">Step 3
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function goToStep(step) {
            const step1 = document.getElementById('step-1-content');
            const step2 = document.getElementById('step-2-content');
            const nav1 = document.getElementById('nav-step-1');
            const nav2 = document.getElementById('nav-step-2');
            const navBadge2 = document.getElementById('nav-badge-2');

            if (step === 1) {
                // Hide 2, Show 1
                step2.classList.add('opacity-0');
                setTimeout(() => {
                    step2.classList.add('hidden');
                    step1.classList.remove('hidden');
                    setTimeout(() => step1.classList.remove('opacity-0'), 50);
                }, 300);

                nav1.className = "flex items-center gap-2 text-indigo-600 dark:text-indigo-400 transition-colors";
                nav1.firstElementChild.className = "w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30";

                nav2.className = "flex items-center gap-2 text-gray-400 dark:text-gray-600 transition-colors";
                navBadge2.className = "w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center";
            } else if (step === 2) {
                // Hide 1, Show 2
                step1.classList.add('opacity-0');
                setTimeout(() => {
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    setTimeout(() => step2.classList.remove('opacity-0'), 50);
                }, 300);

                nav1.className = "flex items-center gap-2 text-gray-400 dark:text-gray-600 transition-colors";
                nav1.firstElementChild.className = "w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 flex items-center justify-center";

                nav2.className = "flex items-center gap-2 text-indigo-600 dark:text-indigo-400 transition-colors";
                navBadge2.className = "w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30";
            }
        }

        let ticketRowIndex = 1;

        function checkMaxTickets(input) {
            let total = 0;
            document.querySelectorAll('.ticket-qty-input').forEach(el => {
                let val = parseInt(el.value);
                if (val < 1 || isNaN(val)) {
                    val = 1; // force minimum 1 if edited to something else
                    el.value = 1;
                }
                total += val;
            });
            document.getElementById('total-tickets-count').innerText = total;

            if (total > 10) {
                alert('You can only purchase a maximum of 10 tickets total.');
                input.value = Math.max(1, parseInt(input.value) - (total - 10));
                checkMaxTickets(input); // Recalculate correctly
            }
        }

        function addTicketRow() {
            let total = 0;
            document.querySelectorAll('.ticket-qty-input').forEach(el => {
                total += parseInt(el.value) || 0;
            });

            if (total >= 10) {
                alert('Cannot add more rows. You have reached the maximum of 10 tickets.');
                return;
            }

            const wrapper = document.getElementById('ticket-selection-wrapper');
            const firstRow = wrapper.querySelector('.ticket-row');
            const newRow = firstRow.cloneNode(true);

            // Update names to use new index
            const select = newRow.querySelector('.ticket-type-select');
            select.name = `tickets[${ticketRowIndex}][event_ticket_type_id]`;

            const input = newRow.querySelector('.ticket-qty-input');
            input.name = `tickets[${ticketRowIndex}][qty]`;
            input.value = "1";

            // Show remove button
            const removeBtn = newRow.querySelector('button');
            removeBtn.style.display = 'block';

            wrapper.appendChild(newRow);
            ticketRowIndex++;
            checkMaxTickets(input);
        }

        function removeTicketRow(btn) {
            btn.closest('.ticket-row').remove();
            checkMaxTickets(document.querySelector('.ticket-qty-input'));
        }

        function validateAndSubmit() {
            const form = document.getElementById('checkout-form');
            const name = document.getElementById('name-input').value;
            const email = document.getElementById('email-input').value;
            const phone = document.getElementById('phone-input').value;

            if (!name || !email || !phone) {
                alert("Please fill in all required fields!");
                return;
            }

            let total = 0;
            document.querySelectorAll('.ticket-qty-input').forEach(el => {
                total += parseInt(el.value) || 0;
            });

            if (total === 0) {
                alert("You must purchase at least 1 ticket!");
                return;
            }
            if (total > 10) {
                alert("Maximum 10 tickets per order.");
                return;
            }

            form.submit();
        }
    </script>
@endsection