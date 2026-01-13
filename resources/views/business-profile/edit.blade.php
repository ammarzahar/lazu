<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Business Profile') }}
            </h2>
            <p class="text-gray-500 text-sm mt-1">Tell us about your business so we can tailor the AI for you.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-xl shadow-xl rounded-2xl border border-white/50 overflow-hidden">
                <div class="p-8 text-gray-900">
                    @if (session('status'))
                        <div
                            class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('business-profile.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="business_name" :value="__('Business Name')" />
                                <x-text-input id="business_name" name="business_name" type="text"
                                    class="mt-1 block w-full" :value="old('business_name', $profile?->business_name)"
                                    required placeholder="e.g. Acme Corp" />
                                <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
                            </div>

                            <div>
                                <x-input-label for="business_type" :value="__('Business Type')" />
                                <select id="business_type" name="business_type"
                                    class="mt-1 block w-full border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2.5 transition-all">
                                    @foreach (['service' => 'Service', 'ecom' => 'E-commerce', 'local' => 'Local'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('business_type', $profile?->business_type) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('business_type')" />
                            </div>

                            <div>
                                <x-input-label for="main_channel" :value="__('Main Channel')" />
                                <select id="main_channel" name="main_channel"
                                    class="mt-1 block w-full border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2.5 transition-all">
                                    @foreach (['meta_ads' => 'Meta Ads', 'whatsapp' => 'WhatsApp', 'landing' => 'Landing Page'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('main_channel', $profile?->main_channel) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('main_channel')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="product_or_service" :value="__('Product or Service')" />
                            <textarea id="product_or_service" name="product_or_service"
                                class="mt-1 block w-full border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2.5 transition-all"
                                rows="3" required
                                placeholder="Describe what you sell...">{{ old('product_or_service', $profile?->product_or_service) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('product_or_service')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="price_min" :value="__('Price Min (RM)')" />
                                <x-text-input id="price_min" name="price_min" type="number" step="0.01"
                                    class="mt-1 block w-full" :value="old('price_min', $profile?->price_min)"
                                    required />
                                <x-input-error class="mt-2" :messages="$errors->get('price_min')" />
                            </div>
                            <div>
                                <x-input-label for="price_max" :value="__('Price Max (RM)')" />
                                <x-text-input id="price_max" name="price_max" type="number" step="0.01"
                                    class="mt-1 block w-full" :value="old('price_max', $profile?->price_max)"
                                    required />
                                <x-input-error class="mt-2" :messages="$errors->get('price_max')" />
                            </div>
                            <div>
                                <x-input-label for="gross_margin_pct" :value="__('Gross Margin %')" />
                                <x-text-input id="gross_margin_pct" name="gross_margin_pct" type="number"
                                    class="mt-1 block w-full" :value="old('gross_margin_pct', $profile?->gross_margin_pct)" placeholder="Optional" />
                                <x-input-error class="mt-2" :messages="$errors->get('gross_margin_pct')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="target_audience" :value="__('Target Audience')" />
                            <textarea id="target_audience" name="target_audience"
                                class="mt-1 block w-full border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2.5 transition-all"
                                rows="2" required
                                placeholder="Who are your ideal customers?">{{ old('target_audience', $profile?->target_audience) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('target_audience')" />
                        </div>

                        <div>
                            <x-input-label for="monthly_objective" :value="__('Monthly Objective')" />
                            <select id="monthly_objective" name="monthly_objective"
                                class="mt-1 block w-full border-gray-200 bg-white/50 backdrop-blur-sm rounded-xl shadow-sm focus:border-brand-500 focus:ring-brand-500 py-2.5 transition-all">
                                @foreach (['leads' => 'Leads', 'sales' => 'Sales', 'awareness' => 'Awareness'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('monthly_objective', $profile?->monthly_objective) === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('monthly_objective')" />
                        </div>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-100">
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