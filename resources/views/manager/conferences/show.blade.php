<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント詳細内容
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
                    <form method="GET" action="{{ route('conferences.edit', ['conference' => $conference->id]) }}">

                        <!-- イベント名 -->
                        <div>
                            <x-input-label for="event_name" value="【イベント名】" />
                            {{ $conference->name }}
                            
                        </div>

                        <!-- イベント名 -->
                        <div class="mt-4">
                            <x-input-label for="information" value="【イベント詳細】" />
                            <!-- 2行以上の改行用設定 -->
                            {!! nl2br(e($conference->information)) !!}
                            
                        </div>

                        <div class="md:flex justify-between">
                        <!-- イベント日付 -->
                            <div class="mt-4">
                                <x-input-label for="event_date" value="【イベント日付】" />
                                <!-- Accessors と Mutatorsの設定した変数を記載 -->
                                {{ $conference->eventDate }}
                            </div>

                            <!-- 開始時間 -->
                            <div class="mt-4">
                                <x-input-label for="start_time" value="【開始時間】" />

                                <!-- Accessors と Mutatorsの設定した変数を記載 -->
                                {{ $conference->startTime }}

                            </div>

                            <!-- 終了時間 -->
                            <div class="mt-4">
                                <x-input-label for="end_time" value="【終了時間】" />
                                <!-- Accessors と Mutatorsの設定した変数を記載 -->
                                {{ $conference->endTime }}
                            </div>
                        </div>
                        
                        <!-- 定員人数 -->
                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-input-label for="max_people" value="【定員人数】" />
                                {{ $conference->max_people }}
                            </div>
                            <div class="flex space-x-4 justify-around">
                                @if($conference->is_visible)
                                    表示中
                                @else
                                    非表示
                                @endif
                            </div>
                            <!-- 過去のイベント一覧であれば、ボタンは非表示 -->
                            @if($conference->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日') )
                                <x-primary-button class="ms-3">
                                    編集する
                                </x-primary-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="py-4">
        <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-20">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">                
                <div class="max-w-2xl py-4 mx-auto">
                    @if(!$users->isEmpty())
                        <div class="text-left py-2">予約状況</div>
                        <table class="table-auto w-full text-left whitespace-no-wrap">
                            <thead>
                              <tr>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約者名</th>
                                <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                              </tr>
                            </thead>
                            <thbody>
                                <!-- 予約情報の設定を追記 -->
                                @foreach($reservations as $reservation)
                                    @if(is_null($reservation['canceled_date']) )
                                        <tr>
                                            <td class="px-4 py-3">{{ $reservation['name'] }}</td>
                                            <td class="px-4 py-3">{{ $reservation['number_of_people'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </thbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="{{ mix('js/flatpickr.js')}}"></script> --}}
</x-app-layout>
