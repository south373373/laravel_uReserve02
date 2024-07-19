<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント新規登録
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="max-w-2xl py-4 mx-auto">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('conferences.store') }}">
                        @csrf

                        <!-- イベント名 -->
                        <div>
                            <x-input-label for="event_name" value="イベント名" />
                            <x-text-input id="event_name" class="block mt-1 w-full" type="text" name="event_name" :value="old('event_name')" required autofocus autocomplete="username" />
                        </div>

                        <!-- イベント名 -->
                        <div class="mt-4">
                            <x-input-label for="information" value="イベント詳細" />
                            <x-textarea row="3" id="information" class="block mt-1 w-full">{{ old('information')}}</x-textarea>
                        </div>

                        <div class="md:flex justify-between">
                        <!-- イベント日付 -->
                            <div class="mt-4">
                                <x-input-label for="event_date" value="イベント日付" />

                                <x-text-input id="event_date" class="block mt-1 w-full"
                                                type="text"
                                                name="event_date"
                                                required />
                            </div>

                            <!-- 開始時間 -->
                            <div class="mt-4">
                                <x-input-label for="start_time" value="開始時間" />

                                <x-text-input id="start_time" class="block mt-1 w-full"
                                                type="text"
                                                name="start_time"
                                                required />
                            </div>

                            <!-- 終了時間 -->
                            <div class="mt-4">
                                <x-input-label for="end_time" value="終了時間" />
                                <x-text-input id="end_time" class="block mt-1 w-full"
                                                type="text"
                                                name="end_time"
                                                required />
                            </div>
                        </div>
                        
                        <!-- 定員人数 -->
                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-input-label for="max_people" value="定員人数" />
                                <x-text-input id="max_people" class="block mt-1 w-full"
                                                type="number"
                                                name="max_people"
                                                required />
                            </div>
                            <div class="flex space-x-4 justify-around">
                                <input type="radio" name="is_visible" value="1" checked />表示
                                <input type="radio" name="is_visible" value="0" />非表示
                            </div>
                            <x-primary-button class="ms-3">
                                新規登録
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ mix('js/flatpickr.js')}}"></script>
</x-app-layout>