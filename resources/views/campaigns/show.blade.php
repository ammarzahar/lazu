<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Campaign Plan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">{{ $plan->marketingEvent?->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Objective: {{ ucfirst($plan->objective) }} · Duration: {{ $plan->duration_days }} days</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800">Offer Plan</h4>
                <ul class="mt-3 list-disc list-inside text-sm text-gray-700 space-y-1">
                    @foreach (($plan->offer_plan ?? []) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800">Copy Pack</h4>
                <ul class="mt-3 list-disc list-inside text-sm text-gray-700 space-y-1">
                    @foreach (($plan->copy_pack ?? []) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
