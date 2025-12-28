<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Campaign Planner') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="text-sm text-green-600">{{ session('status') }}</div>
            @endif
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Plan a Campaign</h3>
                <form method="POST" action="{{ route('campaigns.store') }}" class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div class="md:col-span-2">
                        <x-input-label for="marketing_event_id" :value="__('Marketing Event')" />
                        <select id="marketing_event_id" name="marketing_event_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }} · {{ $event->event_date->format('M d, Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="objective" :value="__('Objective')" />
                        <select id="objective" name="objective" class="mt-1 block w-full border-gray-300 rounded-md">
                            @foreach (['leads' => 'Leads', 'sales' => 'Sales', 'awareness' => 'Awareness'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="duration_days" :value="__('Duration (days)')" />
                        <x-text-input id="duration_days" name="duration_days" type="number" class="mt-1 block w-full" value="7" />
                    </div>
                    <div class="md:col-span-4">
                        <x-primary-button>{{ __('Generate Plan') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Your Recent Plans</h3>
                <div class="mt-4 space-y-3">
                    @foreach ($plans as $plan)
                        <div class="flex items-center justify-between border border-gray-100 rounded-lg p-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $plan->marketingEvent?->name }}</p>
                                <p class="text-sm text-gray-500">Objective: {{ ucfirst($plan->objective) }} · Status: {{ ucfirst($plan->status) }}</p>
                            </div>
                            <a href="{{ route('campaigns.show', $plan) }}" class="text-sm text-indigo-600 font-semibold">View Plan</a>
                        </div>
                    @endforeach
                    @if (! count($plans))
                        <p class="text-sm text-gray-500">No plans yet. Generate your first campaign plan above.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
