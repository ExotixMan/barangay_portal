document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('bhdDetailsModal');

        if (!modalElement) {
            return;
        }

        modalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const titleElement = document.getElementById('bhdDetailsModalTitle');
            const labelElement = document.querySelector('#bhdDetailsModalLabel span');
            const descriptionElement = document.getElementById('bhdDetailsModalDescription');
            const metaElement = document.getElementById('bhdDetailsModalMeta');

            const details = {
                label: button.dataset.detailLabel || 'Details',
                title: button.dataset.detailTitle || 'Details',
                description: button.dataset.detailDescription || 'No description was provided.',
                date: button.dataset.detailDate || '',
                time: button.dataset.detailTime || '',
                location: button.dataset.detailLocation || '',
                attendees: button.dataset.detailAttendees || '',
                start: button.dataset.detailStart || '',
                target: button.dataset.detailTarget || '',
                completed: button.dataset.detailCompleted || ''
            };

            titleElement.textContent = details.title;
            labelElement.textContent = details.label;
            descriptionElement.textContent = details.description;
            metaElement.replaceChildren();

            const metaItems = [
                { icon: 'fa-calendar-days', value: details.date },
                { icon: 'fa-clock', value: details.time },
                { icon: 'fa-location-dot', value: details.location },
                { icon: 'fa-users', value: details.attendees },
                { icon: 'fa-calendar-plus', value: details.start },
                { icon: 'fa-flag-checkered', value: details.target },
                { icon: 'fa-circle-check', value: details.completed }
            ];

            metaItems.forEach(function (item) {
                if (!item.value) {
                    return;
                }

                const chip = document.createElement('span');
                const icon = document.createElement('i');
                const text = document.createElement('span');

                icon.className = 'fas ' + item.icon;
                text.textContent = item.value;
                chip.append(icon, text);
                metaElement.appendChild(chip);
            });
        });
    });
