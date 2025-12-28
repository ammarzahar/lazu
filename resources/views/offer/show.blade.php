<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Offer Analyzer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('offer.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="current_offer_text" :value="__('Paste your current offer')" />
                            <textarea id="current_offer_text" name="current_offer_text" rows="5" class="mt-1 block w-full border-gray-300 rounded-md" required>{{ old('current_offer_text', $offer?->current_offer_text) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('current_offer_text')" />
                        </div>
                        <x-primary-button>{{ __('Analyze Offer') }}</x-primary-button>
                    </form>
                </div>
            </div>

            @if ($offer)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800">Offer Health</h3>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $offer->offer_score ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">Score out of 100</p>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800">Key Findings</h3>
                        <ul class="mt-3 list-disc list-inside text-sm text-gray-700 space-y-1">
                            @foreach (($offer->ai_findings ?? []) as $finding)
                                <li>{{ $finding }}</li>
                            @endforeach
                            @if (! count($offer->ai_findings ?? []))
                                <li>No findings yet. Run analysis to generate insights.</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Offer Upgrade Ideas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm text-gray-700">
                        <div class="md:col-span-2">
                            <p class="font-semibold">AI Suggestions</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach (data_get($offer->ai_suggestions, 'analysis', []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @php($upgrades = data_get($offer->ai_suggestions, 'upgrades', []))
                        <div>
                            <p class="font-semibold">Bundle</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach (($upgrades['bundle'] ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <p class="font-semibold">Bonus</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach (($upgrades['bonus'] ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <p class="font-semibold">Urgency</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach (($upgrades['urgency'] ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <p class="font-semibold">Risk Reversal</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                @foreach (($upgrades['risk_reversal'] ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
