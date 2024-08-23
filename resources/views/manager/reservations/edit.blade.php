<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約編集
        </h2>
    </x-slot>

    <div class="py-12">
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

                    <!-- ユーザー名 -->
                    <div>
                        <x-input-label for="event_name" value="【ユーザー名】" />
                        {{-- {{ $reservation->user->name }} --}}
                        <!-- userで存在しないデータが混在しているため、以下の通りに回避対応 -->
                        {{ optional($reservation->user)->name ?? 'ユーザー不明' }}
                        
                    </div>

                    <!-- イベント名 -->
                    <div class="mt-4">
                        <x-input-label for="information" value="【イベント名】" />
                        {{-- {{ $reservation->conference->name }} --}}
                        <!-- conferenceで存在しないデータが混在しているため、以下の通りに回避対応 -->
                        {{ optional($reservation->conference)->name ?? 'イベント不明' }}
                        
                    </div>


                    <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
                        @csrf
                        <!-- put・patchのどちらかをmethodに指定 -->
                        @method('put')



                        <!-- 予約人数 -->
                        <div class="mt-4">
                            <x-input-label for="number_of_people" value="予約人数" />
                            <input id="number_of_people" class="form-control" type="number" name="number_of_people" value="{{ old('number_of_people', $reservation->number_of_people) }}" required>
                        </div>

                        <div class="md:flex justify pt-2">
                            <div class="text-red-500 px-4 py-3">
                                <x-primary-button class="ms-3">
                                    更新する
                                </x-primary-button>
                            </div>

                            <!-- 「キャンセル」ボタン ->「戻る」ボタンを流用 -->
                            <div class="text-red-500 px-4 py-3">
                                <x-primary-button class="ms-3">
                                    <a href="{{ route('reservations.index') }}" class="btn btn-secondary">キャンセルする</a>
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="{{ mix('js/flatpickr.js')}}"></script> --}}
</x-app-layout>
