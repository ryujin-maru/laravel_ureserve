<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント詳細
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
                        <div>
                            <x-label for="event_name" value="イベント名" />
                            {{$event->name}}
                        </div>

                        <div class="mt-4">
                            <x-label for="information" value="イベント詳細" />
                            {!! nl2br(e($event->information)) !!}
                        </div>
            
                        <div class="md:flex justify-between">
                            <div class="mt-4">
                                <x-label for="event_date" value="イベント日付" />
                                {{ $event->eventDate }}
                            </div>
    
                            <div class="mt-4">
                                <x-label for="start_time" value="開始時間" />
                                {{ $event->startTime }}
                            </div>
    
                            <div class="mt-4">
                                <x-label for="end_time" value="終了時間" />
                                {{ $event->endTime }}
                            </div>
                        </div>

                    <form id="cancel_{{$event->id}}" method="POST" action="{{ route('mypage.cancel',['id'=>$event->id]) }}">
                        @csrf
                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-label value="予約人数" />
                                {{$reservation->number_of_people}}
                            </div>
                            {{-- @php
                                dd($event->eventDate);
                            @endphp --}}
                            @if($event->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日'))
                            <a href="#" onclick="cancelPost(this)" data-id="{{$event->id}}" class="bg-black text-white py-2 px-4">
                                キャンセルする
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function cancelPost(e) {
        'use strict';
        console.log('cancel_' + e.dataset.id);
        if(confirm('本当にキャンセルしてよろしいですか？')){
            document.getElementById('cancel_' + e.dataset.id).submit();
        }
    }
</script>
</x-app-layout>
