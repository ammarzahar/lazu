<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('LAZU Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500">AI CMO guidance for your next marketing moves.</p>
            </div>
            <a href="{{ route('copy.show') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-500">
                Generate Ads Copy
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="text-sm text-green-600">{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Today's CMO Brief</h3>
                    <p class="text-sm text-gray-500 mt-1">Up to 3 actions with context.</p>
                    <div class="mt-4 space-y-4">
                        @foreach (($brief?->decisions ?? []) as $decision)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-800">{{ $decision['action'] ?? 'Action' }}</p>
                                    <span class="text-xs text-gray-500 uppercase">{{ $decision['priority'] ?? 'medium' }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">{{ $decision['reason'] ?? '' }}</p>
                                <div class="mt-3 flex gap-2">
                                    <a href="{{ route('copy.show') }}" class="text-xs text-indigo-600 font-semibold">Generate Copy</a>
                                    <a href="{{ route('offer.show') }}" class="text-xs text-indigo-600 font-semibold">View Offer</a>
                                    <button class="text-xs text-gray-500 font-semibold" type="button">Mark Done</button>
                                </div>
                            </div>
                        @endforeach
                        @if (! count($brief?->decisions ?? []))
                            <p class="text-sm text-gray-500">No brief yet. It will generate after your first profile + offer.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Offer Health</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-3">{{ $offer?->offer_score ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Latest offer score</p>
                    <div class="mt-4 space-y-2 text-sm text-gray-600">
                        @foreach (($offer?->ai_findings ?? []) as $finding)
                            <p>• {{ $finding }}</p>
                        @endforeach
                        @if (! count($offer?->ai_findings ?? []))
                            <p>Run the offer analyzer to see insights.</p>
                        @endif
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        <p class="font-semibold text-gray-700">Suggested Upgrades</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach (data_get($offer?->ai_suggestions, 'analysis', []) as $suggestion)
                                <li>{{ $suggestion }}</li>
                            @endforeach
                            @if (! count(data_get($offer?->ai_suggestions, 'analysis', [])))
                                <li>Generate offer ideas to unlock upgrade suggestions.</li>
                            @endif
                        </ul>
                    </div>
                    <a href="{{ route('offer.show') }}" class="mt-4 inline-flex text-sm text-indigo-600 font-semibold">View Offer Health</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800">Upcoming Opportunities</h3>
                    <p class="text-sm text-gray-500 mt-1">Next 60 days from the marketing calendar.</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($upcomingEvents as $event)
                            <div class="flex items-center justify-between border border-gray-100 rounded-lg p-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $event->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }} · {{ strtoupper($event->region) }}</p>
                                </div>
                                <a href="{{ route('campaigns.index') }}" class="text-sm text-indigo-600 font-semibold">Plan Campaign</a>
                            </div>
                        @endforeach
                        @if (! count($upcomingEvents))
                            <p class="text-sm text-gray-500">No events found in the next 60 days.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">This Week</h3>
                    <p class="text-sm text-gray-500 mt-1">Performance snapshot and next move.</p>
                    <div class="mt-4 text-sm text-gray-700 space-y-2">
                        <p>Spend: RM {{ number_format($latestSnapshot?->metrics['spend'] ?? 0, 2) }}</p>
                        <p>CTR: {{ $latestSnapshot?->metrics['ctr'] ?? 0 }}%</p>
                        <p>CPA: RM {{ number_format($latestSnapshot?->metrics['cpa'] ?? 0, 2) }}</p>
                    </div>
                    @if (auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('reports.weekly.send') }}" class="mt-4">
                            @csrf
                            <x-primary-button>Send Weekly Report Now</x-primary-button>
                        </form>
                        <p class="text-xs text-gray-400 mt-2">Admin/dev only.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
