/**
 * FF Langensendelbach – Kalender (FullCalendar)
 */
document.addEventListener('DOMContentLoaded', function () {
    const calEl = document.getElementById('fw-calendar');
    if (!calEl || typeof FullCalendar === 'undefined') return;

    const calendar = new FullCalendar.Calendar(calEl, {
        locale: 'de',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth',
        },
        buttonText: {
            today: 'Heute',
            month: 'Monat',
            week: 'Woche',
            list: 'Liste',
        },
        events: (typeof FW_EVENTS !== 'undefined') ? FW_EVENTS : [],
        eventClick: function (info) {
            const ev = info.event;
            const props = ev.extendedProps;
            const start = ev.start ? ev.start.toLocaleString('de-DE', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            }) : '';
            const end = ev.end ? ' – ' + ev.end.toLocaleTimeString('de-DE', {hour:'2-digit',minute:'2-digit'}) + ' Uhr' : '';

            let html = `<p class="mb-2"><i class="bi bi-calendar3 me-2 text-danger"></i><strong>${start}${end}</strong></p>`;
            if (props.location) html += `<p class="mb-2"><i class="bi bi-geo-alt me-2 text-danger"></i>${props.location}</p>`;
            if (props.description) html += `<p class="mb-0 text-muted">${props.description}</p>`;

            document.getElementById('terminModalLabel').textContent = ev.title;
            document.getElementById('terminModalBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('terminModal'));
            modal.show();
        },
        height: 'auto',
        dayMaxEvents: 3,
    });

    calendar.render();
});
