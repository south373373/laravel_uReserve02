<div>
    <div class="text-center text-sm">
        日付を選択してください。本日から最大30日先まで選択可能です。
    </div>
    <!-- x-text-input -->
        <input id="calendar" class="block mt-1 mb-2 mx-auto" type="text" name="calendar" />
        {{ $currentDate }}
    <!-- カレンダーの体裁 -->
    <div class="flex border border-green-400 mx-auto">
        <x-calendar-time />
        <x-day />
        <x-day />
        <x-day />
        <x-day />
        <x-day />
        <x-day />
        <x-day />
    </div>

    <div id="week-display" class="flex">
        @foreach($currentWeek as $day)
            {{ $day }}
        @endforeach
    </div>
</div>