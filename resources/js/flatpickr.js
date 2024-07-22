import flatpickr from "flatpickr";
import { Japanese } from "flatpickr/dist/l10n/ja.js"

flatpickr("#event_date", {
    "locale": Japanese,
    // 本日以降
    minDate: "today",
    // 30日間迄
    maxDate: new Date().fp_incr(30)
});

flatpickr("#calendar", {
    "locale": Japanese,
    // 本日以降
    minDate: "today",
    // 30日間迄
    maxDate: new Date().fp_incr(30)
});

const setting = {
    "locale": Japanese,
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    // time_24hr: true,
    // 時間範囲指定
    minTime: "10:00",
    maxTime: "20:00",
    // 既定値(編集時に影響するため一旦コメント)
    // defaultDate: "10:00",
    // calendar用設定
    minuteIncrement: 30
}

// 上記のsettingを第2引数に記載
flatpickr("#start_time", setting);
flatpickr("#end_time", setting);