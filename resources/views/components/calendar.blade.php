<div>
    カレンダー
    <!-- x-text-input -->
        <input id="calendar" class="block mt-1 w-full" type="text" name="calendar" />
        {{ $currentDate }}
    <div id="week-display" class="flex">
        @foreach($currentWeek as $day)
            {{ $day }}
        @endforeach
    </div>
</div>