<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント管理
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <section class="text-gray-600 body-font">
              <div class="container px-5 py-5 mx-auto">

              <!-- flashメッセージの出力設定 -->
              @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
              @endif

              <button onclick="location.href='{{ route('conferences.create')}}'" class="flex mb-4 ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">新規登録</button>
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
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Conferenceテーブルの情報を表示出力 -->
                      @foreach($conferences as $conference)
                      <tr>
                        <td class="text-blue-500 px-4 py-3"><a href="{{ route('conferences.show',['conference' => $conference->id ])}}">{{ $conference->name }}</a></td>
                        <td class="px-4 py-3">{{ $conference->start_date }}</td>
                        <td class="px-4 py-3">{{ $conference->end_date }}</td>
                        <td class="px-4 py-3">後ほど作る</td>
                        <td class="px-4 py-3">{{ $conference->max_people }}</td>
                        <td class="px-4 py-3">{{ $conference->is_visible }}</td>
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
</x-app-layout>
