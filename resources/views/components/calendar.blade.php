<!-- Constantsファイルでの設定を参照 -->
@php
    use App\Constants\ConferenceConst;
@endphp


<div>
    <div class="text-center text-sm">
        テキストボックスをクリックし、日付を選択してください。。
    </div>
    <!-- 元は「x-text-input」 。また日付選択-->
    
    
    <!-- クエリ文字列を使って日付を更新 -->
    
    <!-- 日付選択の実行時にreloadを実施して、カレンダーの1週間分。但し、ローカル環境では日付選択の実行がうまく更新出来ず -->
    <input id="calendar" class="block mt-1 mb-2 mx-auto" type="text" name="calendar" value="{{ $currentDate->format('Y-m-d') }}" onchange="location.reload()" />
    
    <!-- カレンダーの体裁 -->
    <div class="flex mx-auto calendar-container">
        <x-calendar-time />
        @for($i = 0; $i < 7; $i++)
            <div class="w-32">
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['day'] }}</div>
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['dayOfWeek'] }}</div>
                @for($j =0; $j < 21; $j++)
                    <!-- イベントの有無の判定 -->
                    @if($conferences->isNotEmpty())
                    @php
                        $conference = $conferences->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . ConferenceConst::EVENT_TIME[$j]);
                    @endphp

                        @if(!is_null($conference))
                            @php
                                // 予約人数を取得して満員かどうかを確認
                                $reservedPeople = $conference->reservations()->whereNull('canceled_date')->sum('number_of_people');
                                $isFull = $conference->max_people <= $reservedPeople;

                                $conferenceId = $conference->id;
                                $conferenceName = $conference->name;
                                $conferencePeriod = \Carbon\Carbon::parse($conference->start_date)->diffInMinutes($conference->end_date) / 30 - 1;
                            @endphp

                            <a href="{{ route('conferences.detail', ['id' => $conferenceId ])}}">
                                <!-- イベントがあった場合は背景色を変更 <イベント名> -->
                                <!-- <div class="py-1 px-2 h-8 border border-gray-200 text-xs bg-blue-100">{{ $conferenceName }}</div> -->
                                <div class="py-1 px-2 h-8 border border-gray-200 text-xs {{ $isFull ? 'bg-red-100' : 'bg-blue-100' }}">{{ $conferenceName }}</div>
                            </a>

                            @if( $conferencePeriod > 0 )
                                <!-- イベントがあった場合は背景色を変更 <30分単位> -->
                                @for($k = 0; $k < $conferencePeriod; $k++)
                                    <!-- <div class="py-1 px-2 h-8 border border-gray-200 text-xs bg-blue-100"></div> -->
                                    <div class="py-1 px-2 h-8 border border-gray-200 text-xs {{ $isFull ? 'bg-red-100' : 'bg-blue-100' }}"></div>
                                @endfor
                                @php $j += $conferencePeriod @endphp
                            @endif
                        @else
                            <div class="py-1 px-2 h-8 border border-gray-200"></div>
                        @endif
                    @else
                        <div class="py-1 px-2 h-8 border border-gray-200"></div>
                    @endif
                @endfor
            </div>
        @endfor
    </div>
    <!-- 作成中は残しておく。最終的に完成した後に削除 -->
    <!-- @foreach($conferences as $conference) -->
    {{--  {{ $conference->start_date }} --}}
    <!-- @endforeach -->
</div>



