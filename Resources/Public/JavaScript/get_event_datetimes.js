window.onload = function() {
    const timezone = getClientTimezone();

    const eventIds = getEventIds();
    if (eventIds.length > 0) {
        fetchDates('events', eventIds, timezone);
    }

    const appointmentIds = getAppointmentIds();
    if (appointmentIds.length > 0) {
        fetchDates('appointments', appointmentIds, timezone);
    }
};

function getEventIds() {
    const events = document.querySelectorAll('.event');
    let eventIds = [];
    events.forEach(function (event) {
        if (event.dataset.id) {
            eventIds.push(event.dataset.id);
        }
    })
    return eventIds;
}

function getAppointmentIds() {
    const appointments = document.querySelectorAll('.appointment');
    let appointmentIds = [];
    appointments.forEach(function (appointment) {
        if (appointment.dataset.id) {
            appointmentIds.push(appointment.dataset.id);
        }
    })
    return appointmentIds;
}

function getClientTimezone() {
    return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

async function fetchDates(type, ids, timezone) {
    const url = '/api/surfcamp-events/get-time-for-' + type + '?ids=' + ids.join() + '&timezone=' + timezone;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        switch (type) {
            case 'events':
                updateDateTime('.event', json);
                break;
            case 'appointments':
                updateDateTime('.appointment', json);
                break;
        }
    } catch (error) {
        console.error(error.message);
    }
}

function updateDateTime(selector, data) {
    const elements = document.querySelectorAll(selector);
    elements.forEach(function (element) {
        if (!element.dataset.id) {
            return;
        }

        if (!data[element.dataset.id]) {
            return;
        }

        const elementData = data[element.dataset.id];

        if(elementData.startDate) {
            let dateTime = new Date(elementData.startDate);
            const startDateElement = element.querySelector('.event-start-date');
            startDateElement.innerHTML = formatDate(dateTime);
        }

        if(elementData.endDate) {
            let dateTime = new Date(elementData.endDate);
            const endDateElement = element.querySelector('.event-end-date');
            endDateElement.innerHTML = formatDate(dateTime);
        }
    });
}

async function fetchAppointmentDates(ids, timezone) {
    const url = '/api/surfcamp-events/get-time-for-appointments?ids=' + ids.join() + '&timezone=' + timezone;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();
        updateAppointmentsDateTime(json);
    } catch (error) {
        console.error(error.message);
    }
}

function formatDate(datetime) {
    const day = datetime.getDate();
    const month = datetime.getMonth() + 1; // Months are 0-based
    const year = datetime.getFullYear();

    let hours = datetime.getHours();
    if (hours < 10) {
        hours += '0';
    }

    let minutes = datetime.getMinutes();
    if (minutes < 10) {
        minutes += '0';
    }

    return `${day}.${month}.${year} ${hours}:${minutes}`;
}
