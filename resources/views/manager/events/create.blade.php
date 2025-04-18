<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl mx-auto py-4">
                    <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
            
                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf
            
                        <div>
                            <x-label for="event_name" value="イベント名" />
                            <x-input id="event_name" class="block mt-1 w-full" type="text" name="event_name" :value="old('event_name')" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-label for="information" value="イベント詳細" />
                            <x-textarea row="3" name="information" id="information" class="block mt-1 w-full">{{old('information')}}</x-textarea>
                        </div>
            
                        <div class="md:flex justify-between">
                            <div class="mt-4">
                                <x-label for="event_date" value="イベント日付" />
                                <x-input id="event_date" class="block mt-1 w-full" type="text" name="event_date" required />
                            </div>
    
                            <div class="mt-4">
                                <x-label for="start_time" value="開始時間" />
                                <x-input id="start_time" class="block mt-1 w-full" type="text" name="start_time" required />
                            </div>
    
                            <div class="mt-4">
                                <x-label for="end_time" value="終了時間" />
                                <x-input id="end_time" class="block mt-1 w-full" type="text" name="end_time" required />
                            </div>
                        </div>

                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-label for="max-people" value="定員" />
                                <x-input id="max_people" class="block mt-1 w-full" type="number" name="max_people" required />
                            </div>
                            <div class="flex space-x-4 justify-around">
                                <input type="radio" name="is_visible" value="1" checked>表示
                                <input type="radio" name="is_visible" value="0" checked>非表示
                            </div>
                            <x-button class="ml-4">
                                新規登録
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
