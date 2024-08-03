<div>
    カレンダー
    <div>
        <x-text-input id="calendar" class="block mt-1 w-full" type="text" name="calendar" />
    </div>
    {{ $currentDate }}
    <div class="flex">
        @for($day = 0; $day < 7; $day++)
            {{ $currentWeek[$day] }}
        @endfor
    </div>
</div>







<!-- <div class="container">
    <h1>カレンダー</h1>
    <x-text-input id="calendar" class="block mt-1 w-full" type="text" name="calendar" />
    <div class="current-date">
        <p>現在の日付: {{ $currentDate->format('Y-m-d') }}</p>
    </div>
    <div class="current-week">
        <h2>今週の日付</h2>
        <ul>
            @foreach($currentWeek as $day)
                <li>{{ $day }}</li>
            @endforeach
        </ul>
    </div>
</div> -->