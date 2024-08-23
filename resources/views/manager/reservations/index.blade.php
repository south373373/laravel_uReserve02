<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約状況一覧
        </h2>
    </x-slot>

    <div class="py-2">
        <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-20">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <section class="text-gray-600 body-font">
              <div class="container px-5 py-5 mx-auto">

              <!-- flashメッセージの出力設定 -->
              @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
              @endif

                <div class="w-full mx-auto overflow-auto">
                  <table class="table-auto w-full text-left whitespace-no-wrap">
                    <thead>
                      <tr>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">イベント名</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約者名</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">受付日時</th>
                        <!-- 削除用 -->
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">-</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Reservationテーブルの情報を表示出力 -->
                      @foreach($reservations as $reservation)
                      <tr>
                        <!-- 予約者名 -->
                        {{-- <td class="px-4 py-3">{{ $reservation->conference->name }}</td> --}}
                        <!-- conferenceで存在しないデータが混在しているため、以下の通りに回避対応 -->
                        <td class="text-blue-500 px-4 py-3"><a href="{{ route('reservations.show', $reservation->id) }}">{{ optional($reservation->conference)->name ?? 'イベント不明' }}</a></td>
                        
                        <!-- イベント名 -->
                        {{-- <td class="px-4 py-3">{{ $reservation->user->name }}</td> --}}
                        <!-- userで存在しないデータが混在しているため、以下の通りに回避対応 -->
                        <td class="px-4 py-3">{{ optional($reservation->user)->name ?? 'ユーザー不明' }}</td>
                        
                        <!-- 受付日時 -->
                        <td class="px-4 py-3">{{ $reservation->number_of_people }}</td>
                        <td class="px-4 py-3">
                            {{ $reservation->created_at ? $reservation->created_at->format('Y-m-d H:i') : 'N/A' }}
                        </td>

                        <!-- 詳細リンクの処理を記載 -->
                        <!-- <td class="text-blue-500 px-4 py-3"> -->
                            {{-- <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info">詳細</a> --}}
                        <!-- </td> -->
                        
                        <!-- 削除用 -->
                        <form id="delete_{{$reservation->id}}" method="post" action="{{ route('reservations.forceDestroy', ['reservation' => $reservation->id]) }}">
                            @csrf
                            @method('delete')
                            <td class="text-red-500 px-4 py-3">
                                <x-danger-button class="ms-3">
                                    <a href="#" data-id="{{ $reservation->id }}" onclick="deletePost(this)">完全削除</a>
                                </x-danger-button>
                            </td>
                        </form>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <!-- ページネーションの設定 -->
                  {{ $reservations->links() }}
                </div>
                <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
                </div>
              </div>
            </section>
            </div>
        </div>
    </div>
    <script>
        // 削除用の確認メッセージ
        function deletePost(e){
            'use strict';
            if(confirm('本当に削除してもよろしいでしょうか？')){
                document.getElementById('delete_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
