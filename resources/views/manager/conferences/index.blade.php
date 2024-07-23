<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            本日以降のイベント一覧
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
                <button onclick="location.href='{{ route('conferences.past')}}'" class="flex mb-4 ml-auto text-white bg-green-500 border-0 py-2 px-6 focus:outline-none hover:bg-green-600 rounded">過去のイベント一覧</button>
                <button onclick="location.href='{{ route('conferences.create')}}'" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規登録</button>
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
                        <!-- 削除用 -->
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"> - </th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Conferenceテーブルの情報を表示出力 -->
                      @foreach($conferences as $conference)
                      <tr>
                        <td class="text-blue-500 px-4 py-3"><a href="{{ route('conferences.show',['conference' => $conference->id ])}}">{{ $conference->name }}</a></td>
                        <td class="px-4 py-3">{{ $conference->start_date }}</td>
                        <td class="px-4 py-3">{{ $conference->end_date }}</td>
                        <!-- Reservationテーブルと外部結合のcolumnの情報を表示 -->
                        <td class="px-4 py-3">
                            @if(is_null($conference->number_of_people))
                               0 
                            @else
                               {{ $conference->number_of_people }}
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $conference->max_people }}</td>
                        <td class="px-4 py-3">{{ $conference->is_visible }}</td>
                        <!-- 削除用 -->
                        <form id="delete_{{$conference->id}}" method="post" action="{{ route('conferences.destroy', ['conference' => $conference->id] )}}">
                            @csrf
                            @method('delete')
                            <td class="text-red-500 px-4 py-3">
                                <x-danger-button class="ms-3">
                                    <a href="#" data-id="{{ $conference->id }}" onclick="deletePost(this)">削除</a>
                                </x-danger-button>
                            </td>
                        </form>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <!-- ページネーションの設定 -->
                  {{ $conferences->links() }}
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
