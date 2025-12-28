<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meta Ads Connection') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">{{ session('status') }}</div>
                    @endif

                    <p class="text-sm text-gray-500">Provider: {{ strtoupper($provider) }}</p>

                    <form method="POST" action="{{ route('ads.store') }}" class="space-y-4 mt-4">
                        @csrf
                        <div>
                            <x-input-label for="account_id" :value="__('Ad Account ID')" />
                            <x-text-input id="account_id" name="account_id" type="text" class="mt-1 block w-full" :value="old('account_id', $account?->account_id)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('account_id')" />
                        </div>
                        <div>
                            <x-input-label for="access_token" :value="__('Access Token')" />
                            <textarea id="access_token" name="access_token" rows="3" class="mt-1 block w-full border-gray-300 rounded-md" required>{{ old('access_token', $account?->access_token) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('access_token')" />
                        </div>
                        <div>
                            <x-input-label for="token_expires_at" :value="__('Token Expires At (optional)')" />
                            <x-text-input id="token_expires_at" name="token_expires_at" type="datetime-local" class="mt-1 block w-full" :value="old('token_expires_at', optional($account?->token_expires_at)->format('Y-m-d\\TH:i'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('token_expires_at')" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-gray-300" @checked(old('is_active', $account?->is_active ?? true)) />
                            <label for="is_active" class="text-sm text-gray-700">Active</label>
                        </div>
                        <div class="flex gap-3">
                            <x-primary-button>{{ __('Save Connection') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Test Connection</h3>
                <p class="text-sm text-gray-600 mt-1">Run a lightweight check to confirm the API is reachable.</p>
                <form method="POST" action="{{ route('ads.test') }}" class="mt-4">
                    @csrf
                    <x-primary-button>{{ __('Test Connection') }}</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Latest Performance Snapshot</h3>
                @if ($latestSnapshot)
                    <div class="mt-4 text-sm text-gray-700 space-y-2">
                        <p>Date: {{ $latestSnapshot->date->format('M d, Y') }}</p>
                        <p>Spend: RM {{ number_format($latestSnapshot->metrics['spend'] ?? 0, 2) }}</p>
                        <p>CTR: {{ $latestSnapshot->metrics['ctr'] ?? 0 }}%</p>
                        <p>CPC: RM {{ number_format($latestSnapshot->metrics['cpc'] ?? 0, 2) }}</p>
                        <p>CPA: RM {{ number_format($latestSnapshot->metrics['cpa'] ?? 0, 2) }}</p>
                    </div>
                @else
                    <p class="text-sm text-gray-500 mt-2">No performance data yet. Run the daily sync job.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
