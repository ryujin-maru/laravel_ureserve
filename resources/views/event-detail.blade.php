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
            
                    <form method="POST" action="{{ route('events.reserve',['id'=>$event->id]) }}">
                        @csrf
            
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

                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-label for="max-people" value="定員" />
                                {{$event->max_people}}
                            </div>

                            <div class="mt-4">
                                <x-label for="reserved-people" value="予約人数" />
                                <select name="reserved_people">
                                    @for($i = 1; $i <= $reservablePeople; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <input type="hidden" name="id" value="{{$event->id}}">
                            <x-button class="ml-4">
                                予約する
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="pt-4 pb-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl mx-auto py-4">
                    @if(!$users->isEmpty())
                    <div class="text-center p-2">
                        予約状況
                    </div>
                        <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                                <tr>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">予約者名</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                @if (is_null($reservation['canceled_date']))
                                <tr>
                                    <td class="px-4 py-3">{{$reservation['name']}}</td>
                                    <td class="px-4 py-3">{{$reservation['number_of_people']}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}

</x-app-layout>
