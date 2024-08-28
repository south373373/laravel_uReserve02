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
                    <form method="POST" action="{{ route('conferences.reserve', ['id' => $conference->id]) }}">
                        @csrf
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
                                <!-- 満員時の表示変更 -->
                                @if($isFull)
                                    <span class="text-red-500 text-xs">このイベントは満員です。</span>
                                @else
                                    <x-input-label for="reserved_people" value="【予約人数】" />
                                    <select name="reserved_people">
                                        <!-- 予約可能な最大人数を表示 -->
                                        @for($i = 1; $i <= $reservablePeople; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                @endif
                            </div>

                            <!-- 会議の予約済みか否かの判定 -->
                            @if($isReserved === null)
                                <!-- 会議の空席・満席状況の判定 -->
                                @if(!$isFull)
                                    <!-- ログイン済みかどうかの判定 -->
                                    @auth
                                        <!-- ログイン済みなら「予約する」ボタンを表示 -->
                                        <input type="hidden" name="id" value="{{ $conference->id }}">
                                        <x-primary-button class="ms-3">
                                            予約する
                                        </x-primary-button>
                                    @else
                                        <!-- 未ログインならログインページへのリンクを表示 -->
                                        <a href="{{ route('login') }}" class="text-white bg-black px-2 py-1 rounded">
                                            <!-- 予約するにはログインが必要 -->
                                            予約する
                                        </a>
                                    @endauth
                                @endif
                            @else
                                <span class="text-xs">このイベントは既に予約済みです。</span>
                            @endif

                            <!-- 「戻る」ボタンの配置 -->
                            <x-thirdary-button class="ms-3">
                                <a href="{{ Auth::check() ? route('dashboard') : route('guest.calendar') }}" class="btn btn-secondary">戻る</a>
                            </x-thirdary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
