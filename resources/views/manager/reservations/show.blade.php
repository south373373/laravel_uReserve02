<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約詳細内容
        </h2>
    </x-slot>

    <div class="pt-4 pb-2">
        <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-20">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">                
                <div class="max-w-2xl py-4 mx-auto">
                    <!-- validationのエラー出力設定 -->
                    <div class="mb-5 text-red-500">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li type="dist">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                   <!-- 編集ページへ遷移 -->                   
                   <form method="GET" action="{{ route('reservations.edit', ['reservation' => $reservation->id]) }}">
                
                        <!-- ユーザー名（予約者名） -->
                        <div>
                            <x-input-label for="event_name" value="【予約者名】" />
                            {{-- {{ $reservation->user->name }} --}}
                            <!-- userで存在しないデータが混在しているため、以下の通りに回避対応 -->
                            {{ optional($reservation->user)->name ?? 'ユーザー不明' }}
                            
                        </div>

                        <div class="md:flex justify-between">
                            <!-- イベント名 -->
                            <div class="mt-4">
                                <x-input-label for="information" value="【イベント名】" />
                                {{-- {{ $reservation->conference->name }} --}}
                                <!-- conferenceで存在しないデータが混在しているため、以下の通りに回避対応 -->
                                {{ optional($reservation->conference)->name ?? 'イベント不明' }}

                            </div>
                        
                            <!-- 予約人数 -->
                            <div class="mt-4">
                                <x-input-label for="information" value="【現在の予約人数】" />
                                {{ $reservation->number_of_people . '人' }}
                            </div>
                        
                            <!-- 残りの予約人数 -->
                            <div class="mt-4">
                                <x-input-label for="information" value="【残りの予約人数】" />
                                {{ $reservation->conference->max_people - $reservation->conference->reservations->sum('number_of_people') . '人' }}
                            </div>
                        
                            <!-- 予約日時 -->
                            <div class="mt-4">
                                <x-input-label for="information" value="【予約日時】" />
                                {{ $reservation->created_at ? $reservation->created_at->format('Y-m-d H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="md:flex justify">
                            <!-- 過去のイベント一覧であれば、ボタンは非表示 -->
                            <div class="text-red-500 px-4 py-3">
                                <x-primary-button class="ms-3">
                                    編集する
                                </x-primary-button>
                            </div>
                            
                            <!-- 「戻る」ボタンの配置 -->
                            <div class="text-red-500 px-4 py-3">
                                <x-thirdary-button class="ms-3">
                                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">戻る</a>
                                </x-thirdary-button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="{{ mix('js/flatpickr.js')}}"></script> --}}
</x-app-layout>
