import sys, re

with open('resources/views/admin/event.blade.php', 'r', encoding='utf-8') as f:
    event_html = f.read()

with open('resources/views/admin/event-performance.blade.php', 'r', encoding='utf-8') as f:
    perf_html = f.read()

# 1. Add alpine wrapper to event_html
event_html = event_html.replace(
    "@section('content')",
    "@section('content')\n\n<div x-data=\"{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'setup' }\" @hashchange.window=\"activeTab = window.location.hash.substring(1) || 'setup'\">"
)

# 2. Add closing wrapper
event_html = event_html.replace(
    "@endsection",
    "</div>\n\n@endsection"
)

# 3. Replace 'View Performance Link' in top bar with Tab navigation
tab_html = """
                {{-- Tab Navigation --}}
                <nav class=\"flex space-x-1.5 bg-gray-100/80 dark:bg-gray-800/80 p-1.5 rounded-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm shadow-inner\">
                    <a href=\"#setup\" :class=\"activeTab === 'setup' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'\" class=\"px-5 py-2.5 text-sm rounded-lg transition-all duration-300\">Setup</a>
                    <a href=\"#analytics\" :class=\"activeTab === 'analytics' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'\" class=\"px-5 py-2.5 text-sm rounded-lg transition-all duration-300\">Analytics</a>
                    <a href=\"#orders\" :class=\"activeTab === 'orders' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-bold tracking-wide' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium'\" class=\"px-5 py-2.5 text-sm rounded-lg transition-all duration-300\">Orders <span class=\"ml-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300\">{{ $orders->total() }}</span></a>
                </nav>
"""
event_html = re.sub(r'\{\{-- View Performance Link --\}\}.*?</a>', tab_html, event_html, flags=re.DOTALL)

# 4. Wrap <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\"> in x-show='setup'
event_html = event_html.replace(
    '<div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">',
    "{{-- Setup Tab --}}\n        <div x-show=\"activeTab === 'setup'\" x-transition.opacity.duration.300ms class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">"
)
# Close it before </form>
event_html = event_html.replace(
    "</form>",
    "        </div>\n    </form>"
)

# 4.5. Hide save button unless activeTab is 'setup'
event_html = event_html.replace(
    '<button type=\"submit\"\n                    class=\"h-12 w-54',
    '<button type=\"submit\" x-show=\"activeTab === \'setup\'\"\n                    class=\"h-12 w-54'
)

# Remove recent orders list from setup
event_html = re.sub(r'\{\{-- Recent Orders --\}\}.*?<\/table>\s*<\/div>\s*<\/div>\s*<\/div>', '', event_html, flags=re.DOTALL)


# 5. Bring in Analytics and Orders right below </form>
# Extract analytics part from event-performance.blade.php
analytics_match = re.search(r'(<div class=\"grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6\">.*?<\/script>)', perf_html, re.DOTALL)
if analytics_match:
    analytics_content = analytics_match.group(1)
else:
    print('Failed to match analytics content')
    sys.exit(1)

# Replace $var with $performanceData['var'] in the analytics payload
vars_to_replace = [
    'totalRevenue', 'totalOrdersCompleted', 'pendingOrdersValue', 'sellThroughRate', 'totalTicketsSold', 'totalCapacity',
    'avgOrderValue', 'conversionRate', 'totalOrdersPending', 'totalOrdersCanceled', 'attendanceRate', 'totalTicketsScanned',
    'selectedEventId', 'tierBreakdown', 'chartLabels', 'chartVelocity'
]
for v in vars_to_replace:
    analytics_content = analytics_content.replace(f'${v}', f'$performanceData[\'{v}\']')

# Clean up timeframe pills formatting 
analytics_content = analytics_content.replace("document.getElementById('eventSwitcher').addEventListener('change', function () {\n                currentEventId = parseInt(this.value, 10);\n                updateDashboard(currentEventId, currentRange);\n            });", "") 

analytics_tab = f"""
    {{-- Analytics Tab --}}
    <div x-show=\"activeTab === 'analytics'\" x-transition.opacity.duration.300ms class=\"mt-6\">
        {analytics_content}
    </div>
"""

orders_tab = """
    {{-- Orders Tab --}}
    <div x-show=\"activeTab === 'orders'\" x-transition.opacity.duration.300ms class=\"mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden\">
        <div class=\"p-6 border-b border-gray-200 dark:border-gray-700 dark:bg-gray-900\">
            <h3 class=\"text-lg font-semibold text-gray-900 dark:text-white\">All Orders</h3>
            <p class=\"text-sm text-gray-500 mt-1\">Complete history of transactions for this event.</p>
        </div>
        <div class=\"overflow-x-auto\">
            <table class=\"w-full text-sm\">
                <thead class=\"text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50\">
                    <tr>
                        <th class=\"py-3.5 px-6\">Order ID</th>
                        <th class=\"py-3.5 px-6\">Customer</th>
                        <th class=\"py-3.5 px-6\">Date</th>
                        <th class=\"py-3.5 px-6 text-right\">Amount</th>
                        <th class=\"py-3.5 px-6 text-center\">Status</th>
                        <th class=\"py-3.5 px-6 text-center\">Action</th>
                    </tr>
                </thead>
                <tbody class=\"divide-y divide-gray-50 dark:divide-gray-700/50 text-gray-900 dark:text-white\">
                    @forelse ($orders as $order)
                        <tr class=\"hover:bg-gray-50 dark:hover:bg-gray-700/20 transition-colors duration-150\">
                            <td class=\"py-4 px-6 font-medium text-indigo-600 dark:text-indigo-400\">#{{ $order->id }}</td>
                            <td class=\"py-4 px-6\">{{ $order->user->name }}</td>
                            <td class=\"py-4 px-6 text-gray-500 dark:text-gray-400\">{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td class=\"py-4 px-6 text-right font-medium\">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                            <td class=\"py-4 px-6 text-center\">
                                <span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($order->status == 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @elseif($order->status == 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                    @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @endif\">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class=\"py-4 px-6 text-center\">
                                <a href=\"{{ route('manage.orders.show', $order->id) }}\" class=\"text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400\">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan=\"6\" class=\"py-8 text-center text-gray-400\">No orders found for this event yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class=\"p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900\">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
"""

event_html = event_html.replace(
    '    </form>\n\n    {{-- Template for new ticket rows --}}',
    f'    </form>\n{analytics_tab}\n{orders_tab}\n\n    {{-- Template for new ticket rows --}}'
)

# 6. Update Quick Stats on the Setup Tab using real data
event_html = event_html.replace(
    '<span class=\"font-semibold text-gray-900 dark:text-white\">123</span>',
    '<span class=\"font-semibold text-gray-900 dark:text-white\">{{ number_format($performanceData[\'totalTicketsSold\'] ?? 0) }}</span>'
)
event_html = event_html.replace(
    '<span class=\"font-semibold text-gray-900 dark:text-white\">$6,027.00</span>',
    '<span class=\"font-semibold text-gray-900 dark:text-white\">Rp {{ number_format($performanceData[\'totalRevenue\'] ?? 0, 0, \',\', \'.\') }}</span>'
)
event_html = event_html.replace(
    '<span class=\"font-semibold text-gray-900 dark:text-white\">15</span>',
    '<span class=\"font-semibold text-gray-900 dark:text-white\">{{ number_format($performanceData[\'totalOrdersPending\'] ?? 0) }}</span>'
)

with open('resources/views/admin/event.blade.php', 'w', encoding='utf-8') as f:
    f.write(event_html)
print('Done!')
