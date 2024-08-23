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
                        
                    <!-- cancelボタンのform設定 -->
                    <form id="cancel_{{ $conference->id }}" method="post" action="{{ route('mypage.cancel', ['id' => $conference->id]) }}">
                        @csrf
                        <!-- 定員人数 -->
                        <div class="md:flex justify-between items-end">
                            <div class="mt-4">
                                <x-input-label value="【予約人数】" />
                                {{ $reservation->number_of_people }}
                            </div>

                            <!-- 過去のイベント一覧
                                 「キャンセル」ボタン(予約取消)の表示条件として「<」今日以前であると設定 -->
                            @if($conference->eventDate >= \Carbon\Carbon::today()->format('Y年m月d日') )
                                <x-primary-button class="ms-3">
                                    <a href="#" data-id="{{ $conference->id }}" onclick="cancelPost(this)" class="btn btn-secondary">
                                        予約取消
                                    </a>
                                </x-primary-button>
                            @endif

                            <!-- 「戻る」ボタンの配置 -->
                            <div class="text-red-500 px-4">
                                <x-primary-button class="ms-3">
                                    <a href="{{ route('mypage.index') }}" class="btn btn-secondary">
                                        戻る
                                    </a>
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- cancelボタン（予約取消）の処理 -->
    <script>
        function cancelPost(e){
            'use strict';
            if(confirm('本当に予約を取り消してもよろしいでしょうか？'))
            {
                document.getElementById('cancel_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
