
$(document).ready(function () {
    var calendarEl = $('#calendar')[0]; // jQuery 選擇器獲取 DOM 元素

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'UTC',
        editable: true,
        initialView: 'multiMonthTwoMonth',
        views: {
            multiMonthTwoMonth: {
                type: 'multiMonth',
                duration: {months: 4}
            }
        },
        multiMonthMinWidth: 300,
        multiMonthMaxColumns: 1,
        events: 'https://fullcalendar.io/api/demo-feeds/events.json'
    });

    calendar.render();
});
