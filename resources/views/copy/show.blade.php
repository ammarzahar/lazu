<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ads Copy Generator') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('copy.generate') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="offer_text" :value="__('Offer text (optional override)')" />
                            <textarea id="offer_text" name="offer_text" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('offer_text', $offer?->current_offer_text) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="style" :value="__('Style')" />
                                <select id="style" name="style" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach (['soft' => 'Soft Sell', 'hard' => 'Hard Sell', 'edu' => 'Educational'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('style') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="language" :value="__('Language')" />
                                <select id="language" name="language" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @foreach (['bm' => 'Bahasa Malaysia', 'en' => 'English'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('language') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <x-primary-button>{{ __('Generate Copy') }}</x-primary-button>
                    </form>
                </div>
            </div>

            @if (isset($copy))
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Generated Copy</h3>
                    <div class="mt-4 space-y-3 text-sm text-gray-700">
                        <div>
                            <p class="font-semibold">Headline</p>
                            <p>{{ $copy->headline }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Primary Text</p>
                            <p>{{ $copy->primaryText }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">CTA</p>
                            <p>{{ $copy->cta }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Variations</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                @foreach ($copy->variations as $variation)
                                    <li>{{ $variation }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
