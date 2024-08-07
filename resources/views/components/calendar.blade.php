<!-- Constantsファイルでの設定を参照 -->
@php
    use App\Constants\ConferenceConst;
@endphp


<div>
    <div class="text-center text-sm">
        日付を選択してください。本日から最大30日先まで選択可能です。
    </div>
    <!-- x-text-input -->
        <input id="calendar" class="block mt-1 mb-2 mx-auto" type="text" name="calendar" value="{{ $currentDate }}" />
    <!-- カレンダーの体裁 -->
    <div class="flex border border-green-400 mx-auto">
        <x-calendar-time />
        @for($i = 0; $i < 7; $i++)
            <div class="w-32">
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['day'] }}</div>
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['dayOfWeek'] }}</div>
                @for($j =0; $j < 21; $j++)
                    <div class="py-1 px-2 h-8 border border-gray-200">{{ ConferenceConst::EVENT_TIME[$j] }}</div>
                @endfor
            </div>
        @endfor
    </div>
    @foreach($conferences as $conference)
        {{ $conference->start_date }}
    @endforeach
</div>



