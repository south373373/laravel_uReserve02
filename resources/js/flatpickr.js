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
    dateFormat: "Y-m-d",  // 日付フォーマット
    onChange: function(selectedDates, dateStr) {
        // AJAXでデータを取得し、カレンダーを更新する
        fetchDate(dateStr);
        
        // URLのクエリパラメータを更新する
        const url = new URL(window.location);
        url.searchParams.set('date', dateStr);
        window.history.pushState({}, '', url); // URLを更新
    },
    defaultDate: document.getElementById('calendar').value,  // 初期値設定
});

// 日付変更時にデータをフェッチする関数
function fetchDate(date) {
    console.log('Fetching data for date:', date);

    fetch('/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ date: date })
    })
    .then(response => response.json())
    .then(data => {
        // const weekDisplay = document.getElementById('week-display');
        // weekDisplay.innerHTML = ''; // 既存の表示をクリア
        // data.currentWeek.forEach(week => {
        //     weekDisplay.innerHTML += `
        //         <div class="w-32">
        //             <div class="py-1 px-2 border border-gray-200 text-center">${week.day}</div>
        //             <div class="py-1 px-2 border border-gray-200 text-center">${week.dayOfWeek}</div>
        //         </div>`;
        // });

        // カレンダー表示部分の更新処理
        updateCalendar(data.currentWeek);
    })
    .catch(error => console.error('Error:', error));
}


// カレンダーの1週間分の日付を更新する関数
function updateCalendar(weekData) {
    const calendarContainer = document.querySelector('.calendar-container'); // カレンダーのDOM要素

    // カレンダーをクリアして、新しい1週間分の日付を挿入
    calendarContainer.innerHTML = '';

    weekData.forEach(dayInfo => {
        const dayColumn = document.createElement('div');
        dayColumn.classList.add('w-32');

        // 日付と曜日の表示
        dayColumn.innerHTML = `
            <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.day}</div>
            <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.dayOfWeek}</div>
        `;
        calendarContainer.appendChild(dayColumn);
    });
}


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


// 今回用の追記分
// - 日付変更時にデータをフェッチする関数
// function fetchDate(date) {
//     console.log('Fetching data for date:', date);

//     fetch('/', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify({ date: date })
//     })
//     .then(response => response.json())
//     .then(data => {
//         const weekDisplay = document.getElementById('week-display');
//         weekDisplay.innerHTML = ''; // 既存の表示をクリア
//         data.currentWeek.forEach(week => {
//             weekDisplay.innerHTML += `
//                 <div class="w-32">
//                     <div class="py-1 px-2 border border-gray-200 text-center">${week.day}</div>
//                     <div class="py-1 px-2 border border-gray-200 text-center">${week.dayOfWeek}</div>
//                 </div>`;
//         });
//     })
//     .catch(error => console.error('Error:', error));
// }