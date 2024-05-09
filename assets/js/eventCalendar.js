document.addEventListener('DOMContentLoaded', function() {
    var calendarEL = document.getElementById('calendar');
    
    let events = [];
    calendarEL.innerHTML = '<div class="calendar-loader"><p><i class="fa fa-fw fa-spinner fa-spin"></i></div>';
    $.getJSON("Rest/getEvents")
        .done(function(data){
            data.forEach((item) => {
                let event = {
                    title: item.title,
                    start: item.eventStart.replace(' ','T'),
                    end: (item.eventEnd === null) ? null : item.eventEnd.replace(' ','T'),
                    url: 'internal/event/' + item.seoLink,
                    allDay: (item.eventEnd === null) ? true : false
                };
                events.push(event);
            });
            var calendar = new FullCalendar.Calendar(calendarEL, {
                plugins: ['dayGrid', 'interaction'],
                initialView: 'dayGridMonth',
                editable: false,
                events: events,
                locale: 'hu',
                allDay: false,
                selectable: true,
                unselectAuto: false,
                dayMaxEvents: true
            });
            calendar.on('select', function(info){
                console.log(info);
            })
            calendar.render();
            $(".calendar-loader").hide();
        })
        .fail((jqxhr, textStatus, error) => {
            console.error("Hiba történt!");
        })
});