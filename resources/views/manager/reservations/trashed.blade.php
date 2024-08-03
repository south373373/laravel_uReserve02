reservations.trashed

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            無効予約一覧
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

              <div class="flex justify-between">
                {{-- <button onclick="location.href='{{ route('reservations.past')}}'" class="flex mb-4 ml-auto text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded">過去のイベント一覧</button> --}}
                <button onclick="location.href='{{ route('reservations.create')}}'" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規登録</button>
              </div>
                <div class="w-full mx-auto overflow-auto">
                  <table class="table-auto w-full text-left whitespace-no-wrap">
                    <thead>
                      <tr>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">イベント名</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">開始日時</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">終了日時</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">予約人数</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">定員人数</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">表示・非表示</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">削除日</th>
                        <!-- 復旧用 -->
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"> - </th>
                        <!-- 削除用 -->
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"> - </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Conferenceテーブルの情報を表示出力 -->
                      @foreach($trashedConferences as $conference)
                      <tr>
                        <td class="px-4 py-3">{{ $reservation->name }}</a></td>
                        <td class="px-4 py-3">{{ $reservation->start_date }}</td>
                        <td class="px-4 py-3">{{ $reservation->end_date }}</td>
                        <!-- Reservationテーブルと外部結合のcolumnの情報を表示 -->
                        <td class="px-4 py-3">
                            @if(is_null($reservation->number_of_people))
                               0 
                            @else
                               {{ $reservation->number_of_people }}
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $reservation->max_people }}</td>
                        <td class="px-4 py-3">{{ $reservation->is_visible }}</td>
                        <td class="px-4 py-3">{{ $reservation->deleted_at }}</td>
                        <!-- 復旧用 -->
                        <form id="restore_{{$reservation->id}}" method="post" action="{{ route('reservations.restore', ['reservation' => $reservation->id] )}}">
                            @csrf
                            @method('PATCH')
                            <td class="text-blue-500 px-4 py-3">
                                <x-thirdary-button class="ms-3">
                                    <a href="#" data-id="{{ $reservation->id }}" onclick="restorePost(this)">戻す</a>
                                </x-thirdary-button>
                            </td>
                        </form>
                        <!-- 削除用 -->
                        <form id="delete_{{$reservation->id}}" method="post" action="{{ route('reservations.forceDestroy', ['reservation' => $reservation->id] )}}">
                            @csrf
                            @method('DELETE')
                                <td class="text-red-500 px-4 py-3">
                                <x-danger-button class="ms-3">
                                    <a href="#" data-id="{{ $reservation->id }}" onclick="deleteForce(this)">完全削除</a>
                                </x-danger-button>
                                </td>
                        </form>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <!-- ページネーションの設定 -->
                  {{ $trashedConferences->links() }}
                </div>
                <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">

                </div>
              </div>
            </section>
            </div>
        </div>
    </div>
    <script>
        // 復旧用の確認メッセージ
        function restorePost(e){
            'use strict';
            if(confirm('本当にイベント管理へ戻してもよろしいでしょうか？')){
                document.getElementById('restore_' + e.dataset.id).submit();
            }
        }

        // 完全削除用の確認メッセージ
        function deleteForce(e){
            'use strict';
            if(confirm('完全に削除してもよろしいでしょうか？')){
                document.getElementById('delete_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
