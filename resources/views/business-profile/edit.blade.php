<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Business Profile') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('business-profile.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="business_name" :value="__('Business Name')" />
                            <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full" :value="old('business_name', $profile?->business_name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                        </div>

                        <div>
                            <x-input-label for="business_type" :value="__('Business Type')" />
                            <select id="business_type" name="business_type" class="mt-1 block w-full border-gray-300 rounded-md">
                                @foreach (['service' => 'Service', 'ecom' => 'E-commerce', 'local' => 'Local'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('business_type', $profile?->business_type) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('business_type')" />
                        </div>

                        <div>
                            <x-input-label for="product_or_service" :value="__('Product or Service')" />
                            <textarea id="product_or_service" name="product_or_service" class="mt-1 block w-full border-gray-300 rounded-md" rows="3" required>{{ old('product_or_service', $profile?->product_or_service) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('product_or_service')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="price_min" :value="__('Price Min (RM)')" />
                                <x-text-input id="price_min" name="price_min" type="number" step="0.01" class="mt-1 block w-full" :value="old('price_min', $profile?->price_min)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price_min')" />
                            </div>
                            <div>
                                <x-input-label for="price_max" :value="__('Price Max (RM)')" />
                                <x-text-input id="price_max" name="price_max" type="number" step="0.01" class="mt-1 block w-full" :value="old('price_max', $profile?->price_max)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price_max')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="gross_margin_pct" :value="__('Gross Margin % (optional)')" />
                            <x-text-input id="gross_margin_pct" name="gross_margin_pct" type="number" class="mt-1 block w-full" :value="old('gross_margin_pct', $profile?->gross_margin_pct)" />
                            <x-input-error class="mt-2" :messages="$errors->get('gross_margin_pct')" />
                        </div>

                        <div>
                            <x-input-label for="target_audience" :value="__('Target Audience')" />
                            <textarea id="target_audience" name="target_audience" class="mt-1 block w-full border-gray-300 rounded-md" rows="2" required>{{ old('target_audience', $profile?->target_audience) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('target_audience')" />
                        </div>

                        <div>
                            <x-input-label for="main_channel" :value="__('Main Channel')" />
                            <select id="main_channel" name="main_channel" class="mt-1 block w-full border-gray-300 rounded-md">
                                @foreach (['meta_ads' => 'Meta Ads', 'whatsapp' => 'WhatsApp', 'landing' => 'Landing Page'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('main_channel', $profile?->main_channel) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('main_channel')" />
                        </div>

                        <div>
                            <x-input-label for="monthly_objective" :value="__('Monthly Objective')" />
                            <select id="monthly_objective" name="monthly_objective" class="mt-1 block w-full border-gray-300 rounded-md">
                                @foreach (['leads' => 'Leads', 'sales' => 'Sales', 'awareness' => 'Awareness'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('monthly_objective', $profile?->monthly_objective) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_objective')" />
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Save Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
