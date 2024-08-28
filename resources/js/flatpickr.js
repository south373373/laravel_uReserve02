// イベントの編集画面にて更新を実行すると、「終了時間には、開始時間より後の日付を指定してください。」
// とのエラーが出力。調べてみたところ、ブラウザ上のconsole.log上で以下のエラーが出力されていた。
// 「TypeError: Cannot read properties of null (reading 'value') at flatpickr.js:28:53 
//   (anonymous)	@	flatpickr.js:28」
// 
// どうやら、flatpickr.jsのコード内で、要素が存在しない場合にvalueプロパティにアクセスしようとしている。
// 具体的には、document.getElementById('calendar') が null を返している場合、その value を
// 取得しようするためにエラーが発生している様子。
// 
// 解決策として、以下の通りに編集。
// 01. flatpickr("#calendar", ... ) の初期化を行う前に、calendar 要素が存在するかどうかを確認処理を追記。
// 02. 他の要素（event_date, start_time, end_time）にも同様のチェックを追加して、要素が存在する場合にのみ
//     flatpickr を初期化するような処理を追記。

import flatpickr from "flatpickr";
import { Japanese } from "flatpickr/dist/l10n/ja.js"

// Event DateのFlatpickr設定
const eventDateElement = document.getElementById('event_date');
if(eventDateElement){
    flatpickr(eventDateElement, {
        "locale": Japanese,
        // 本日以降
        minDate: "today",
        // 30日間迄
        maxDate: new Date().fp_incr(30)
    });
}

// flatpickr("#event_date", {
//     "locale": Japanese,
//     // 本日以降
//     minDate: "today",
//     // 30日間迄
//     maxDate: new Date().fp_incr(30)
// });

// calendar処理の編集
const calendarElement = document.getElementById('calendar');
if(calendarElement){
    flatpickr(calendarElement, {
        "locale": Japanese,
        // 日付フォーマット
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr){
            fetchDate(dateStr);
            const url = new URL(window.location);
            url.searchParams.set('date', dateStr);
            // URLを更新
            window.history.pushState({}, '', url);
        },
        // 初期値設定
        defaultDate: calendarElement.value,
    });
}

// flatpickr("#calendar", {
//     "locale": Japanese,
//     dateFormat: "Y-m-d",  // 日付フォーマット
//     onChange: function(selectedDates, dateStr) {
//         // AJAXでデータを取得し、カレンダーを更新する
//         fetchDate(dateStr);
        
//         // URLのクエリパラメータを更新する
//         const url = new URL(window.location);
//         url.searchParams.set('date', dateStr);
//         window.history.pushState({}, '', url); // URLを更新
//     },
//     defaultDate: document.getElementById('calendar').value,  // 初期値設定
// });



// start_time処理の編集
const startTimeElement = document.getElementById('start_time');
if(startTimeElement){
    flatpickr(startTimeElement, {
        "locale": Japanese,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        // 時間範囲指定
        minTime: "10:00",
        maxTime: "20:00",
        // calendar用設定
        minuteIncrement: 30
    });
}

// end_time処理の編集
const endTimeElement = document.getElementById('end_time');
if(endTimeElement){
    flatpickr(endTimeElement, {
        "locale": Japanese,
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        // 時間範囲指定
        minTime: "10:00",
        maxTime: "20:00",
        // calendar用設定
        minuteIncrement: 30
    });
}

// const setting = {
//     "locale": Japanese,
//     enableTime: true,
//     noCalendar: true,
//     dateFormat: "H:i",
//     // time_24hr: true,
//     // 時間範囲指定
//     minTime: "10:00",
//     maxTime: "20:00",
//     // 既定値(編集時に影響するため一旦コメント)
//     // defaultDate: "10:00",
//     // calendar用設定
//     minuteIncrement: 30
// }

// // 上記のsettingを第2引数に記載
// flatpickr("#start_time", setting);
// flatpickr("#end_time", setting);




// 
// << dashboard画面上のカレンダー上の日付選択時の1週間分の更新処理 >>
// 
// 01. 日付変更時にデータをフェッチする関数の編集
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
        updateCalendar(data.currentWeek);
    })
    .catch(error => console.error('Error:', error));
}


// 01. 日付変更時にデータをフェッチする関数
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

//         // カレンダー表示部分の更新処理
//         updateCalendar(data.currentWeek);
//     })
//     .catch(error => console.error('Error:', error));
// }


// 02. カレンダーの1週間分の日付を更新する関数の編集
function updateCalendar(weekData) {
    const calendarContainer = document.querySelector('.calendar-container');
    if (calendarContainer) {
        calendarContainer.innerHTML = '';

        weekData.forEach(dayInfo => {
            const dayColumn = document.createElement('div');
            dayColumn.classList.add('w-32');
            dayColumn.innerHTML = `
                <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.day}</div>
                <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.dayOfWeek}</div>
            `;
            calendarContainer.appendChild(dayColumn);
        });
    }
}


// 02. カレンダーの1週間分の日付を更新する関数
// function updateCalendar(weekData) {
//     const calendarContainer = document.querySelector('.calendar-container'); // カレンダーのDOM要素

//     // カレンダーをクリアして、新しい1週間分の日付を挿入
//     calendarContainer.innerHTML = '';

//     weekData.forEach(dayInfo => {
//         const dayColumn = document.createElement('div');
//         dayColumn.classList.add('w-32');

//         // 日付と曜日の表示
//         dayColumn.innerHTML = `
//             <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.day}</div>
//             <div class="py-1 px-2 border border-gray-200 text-center">${dayInfo.dayOfWeek}</div>
//         `;
//         calendarContainer.appendChild(dayColumn);
//     });
// }