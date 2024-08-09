<!-- Constantsファイルでの設定を参照 -->
@php
    use App\Constants\ConferenceConst;
@endphp


<div>
    <div class="text-center text-sm">
        日付を選択してください。本日から最大30日先まで選択可能です。
    </div>
    <!-- 元は「x-text-input」 。また日付選択-->
    <input id="calendar" class="block mt-1 mb-2 mx-auto" type="text" name="calendar" value="{{ $currentDate }}" />
    <!-- カレンダーの体裁 -->
    <div class="flex border border-green-400 mx-auto">
        <x-calendar-time />
        @for($i = 0; $i < 7; $i++)
            <div class="w-32">
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['day'] }}</div>
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['dayOfWeek'] }}</div>
                @for($j =0; $j < 21; $j++)
                    <!-- イベントの有無の判定 -->
                    @if($conferences->isNotEmpty())
                        @if(!is_null($conferences->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . ConferenceConst::EVENT_TIME[$j] )))
                            @php
                                // 最後をidにて指定。
                                $conferenceId = $conferences->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . ConferenceConst::EVENT_TIME[$j] )->id;
                                
                                $conferenceName = $conferences->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . ConferenceConst::EVENT_TIME[$j] )->name;
                                $conferenceInfo = $conferences->firstWhere('start_date', $currentWeek[$i]['checkDay'] . " " . ConferenceConst::EVENT_TIME[$j] );
                                
                                // 開始 - 終了の差分を計算
                                $conferencePeriod = \Carbon\Carbon::parse($conferenceInfo->start_date)->diffInMinutes($conferenceInfo->end_date) / 30 - 1;
                            @endphp
                            <a href="{{ route('conferences.detail', ['id' => $conferenceId ])}}">
                                <!-- イベントがあった場合は背景色を変更 -->
                                <div class="py-1 px-2 h-8 border border-gray-200 text-xs bg-blue-100">{{ $conferenceName }}</div>
                            </a>
                            @if( $conferencePeriod > 0 )
                                @for($k = 0; $k < $conferencePeriod; $k++)
                                    <div class="py-1 px-2 h-8 border border-gray-200 text-xs bg-blue-100"></div>
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
    @foreach($conferences as $conference)
        {{ $conference->start_date }}
    @endforeach
</div>



