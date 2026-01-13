<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('LAZU Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500">AI CMO guidance for your next marketing moves.</p>
            </div>
            <a href="{{ route('copy.show') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-500">
                Generate Ads Copy
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="text-sm text-green-600">{{ session('status') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div
                    class="lg:col-span-2 bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/50 overflow-hidden p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Today's CMO Brief</h3>
                            <p class="text-sm text-gray-500 mt-1">Up to 3 actions with context.</p>
                        </div>
                        <div class="p-2 bg-brand-50 rounded-lg text-brand-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach (($brief?->decisions ?? []) as $decision)
                            <div
                                class="border border-gray-100 bg-white/50 rounded-xl p-5 hover:border-brand-200 transition-colors">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-800">{{ $decision['action'] ?? 'Action' }}</p>
                                    <span
                                        class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ ($decision['priority'] ?? '') === 'high' ? 'bg-red-100 text-red-700' : 'bg-brand-100 text-brand-700' }}">
                                        {{ strtoupper($decision['priority'] ?? 'medium') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $decision['reason'] ?? '' }}</p>
                                <div class="mt-4 flex gap-3">
                                    <a href="{{ route('copy.show') }}"
                                        class="text-xs text-white bg-brand-600 hover:bg-brand-700 px-3 py-1.5 rounded-lg font-medium transition-colors">Generate
                                        Copy</a>
                                    <a href="{{ route('offer.show') }}"
                                        class="text-xs text-brand-700 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg font-medium transition-colors">View
                                        Offer</a>
                                </div>
                            </div>
                        @endforeach
                        @if (!count($brief?->decisions ?? []))
                            <div class="text-center py-8 text-gray-500">
                                <p>No brief yet. It will generate after your first profile + offer.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/50 overflow-hidden p-8">
                    <h3 class="text-xl font-bold text-gray-800">Offer Health</h3>
                    <div class="flex items-baseline mt-4">
                        <p
                            class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-blue-500">
                            {{ $offer?->offer_score ?? 'N/A' }}
                        </p>
                        <span class="ml-2 text-sm text-gray-500">/ 100</span>
                    </div>

                    <div class="mt-6 space-y-3 text-sm text-gray-600">
                        @foreach (($offer?->ai_findings ?? []) as $finding)
                            <div class="flex gap-2">
                                <span class="text-brand-500 mt-0.5">•</span>
                                <p>{{ $finding }}</p>
                            </div>
                        @endforeach
                        @if (!count($offer?->ai_findings ?? []))
                            <p class="text-gray-400 italic">Run the offer analyzer to see insights.</p>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="font-semibold text-gray-800 mb-3">Suggested Upgrades</p>
                        <ul class="space-y-2 text-sm text-gray-600">
                            @foreach (data_get($offer?->ai_suggestions, 'analysis', []) as $suggestion)
                                <li class="flex gap-2">
                                    <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $suggestion }}</span>
                                </li>
                            @endforeach
                            @if (!count(data_get($offer?->ai_suggestions, 'analysis', [])))
                                <li class="text-gray-400 italic">Generate offer ideas to unlock upgrade suggestions.</li>
                            @endif
                        </ul>
                    </div>
                    <a href="{{ route('offer.show') }}"
                        class="mt-6 block w-full text-center text-sm text-brand-600 font-semibold hover:text-brand-800 transition-colors">View
                        Offer Detail →</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div
                    class="bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/50 overflow-hidden p-8 lg:col-span-2">
                    <h3 class="text-xl font-bold text-gray-800">Upcoming Opportunities</h3>
                    <p class="text-sm text-gray-500 mt-1">Next 60 days from the marketing calendar.</p>
                    <div class="mt-6 space-y-3">
                        @foreach ($upcomingEvents as $event)
                            <div
                                class="flex items-center justify-between border border-gray-100 bg-white/50 rounded-xl p-4 hover:border-brand-200 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                                        {{ $event->event_date->format('d M') }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 group-hover:text-brand-600 transition-colors">
                                            {{ $event->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ strtoupper($event->region) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('campaigns.index') }}"
                                    class="text-sm text-brand-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity">Plan
                                    Campaign →</a>
                            </div>
                        @endforeach
                        @if (!count($upcomingEvents))
                            <div class="text-center py-8 text-gray-500">
                                <p>No events found in the next 60 days.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div
                    class="bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/50 overflow-hidden p-8">
                    <h3 class="text-xl font-bold text-gray-800">This Week</h3>
                    <p class="text-sm text-gray-500 mt-1">Performance snapshot.</p>

                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between items-center p-3 bg-brand-50 rounded-lg">
                            <span class="text-sm text-brand-900 font-medium">Spend</span>
                            <span class="font-bold text-brand-700">RM
                                {{ number_format($latestSnapshot?->metrics['spend'] ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                            <span class="text-sm text-purple-900 font-medium">CTR</span>
                            <span class="font-bold text-purple-700">{{ $latestSnapshot?->metrics['ctr'] ?? 0 }}%</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-pink-50 rounded-lg">
                            <span class="text-sm text-pink-900 font-medium">CPA</span>
                            <span class="font-bold text-pink-700">RM
                                {{ number_format($latestSnapshot?->metrics['cpa'] ?? 0, 2) }}</span>
                        </div>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('reports.weekly.send') }}"
                            class="mt-6 border-t border-gray-100 pt-4">
                            @csrf
                            <x-primary-button class="w-full justify-center">Send Weekly Report Now</x-primary-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>