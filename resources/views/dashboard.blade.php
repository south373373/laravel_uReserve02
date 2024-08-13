<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- {{ __('Dashboard') }} --}}
            イベントカレンダー_[dashboard.blade.php]

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="event-calendar mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <!-- resources > calendar > indexの読み込み -->
                <!-- 変数を定義していない状態のviewを表示する場合 -->
                {{-- <x-calendar /> --}}

                <!-- flashメッセージの出力設定 -->
                @if(session('status'))
                  <div class="mb-4 font-medium text-sm text-green-600">
                      {{ session('status') }}
                  </div>
                @endif

                <!-- 変数を定義している状態のviewを表示する場合 -->
                <x-calendar :currentDate="$currentDate" :currentWeek="$currentWeek" :conferences="$conferences" :isFull="$isFull" />
                <!-- JavaScriptの読み込み -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
                <script src="{{ asset('js/flatpicker.js') }}"></script>
                
                <!-- @livewireScriptsは無しで設定 -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
