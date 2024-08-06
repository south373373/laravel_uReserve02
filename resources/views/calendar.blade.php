<x-calendar-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベントカレンダー
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
        <div class="event-calendar border border-red-400 mx-auto sm:px-6 lg:px-20">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">                
                <!-- resources > calendar > indexの読み込み -->
                <!-- 変数を定義していない状態のviewを表示する場合 -->
                {{-- <x-calendar /> --}}

                <!-- 変数を定義している状態のviewを表示する場合 -->
                <x-calendar :currentDate="$currentDate" :currentWeek="$currentWeek" />        
                <!-- @livewireScriptsは無しで設定 -->
            </div>
        </div>
    </div>
</x-calendar-layout>