<?php ob_start(); 

$user = Auth::user();
$isAdmin = $user['role'] === 'admin';
?>

<style>
#calendar {
    background: white;
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.fc-event {
    cursor: pointer;
    border-radius: 4px;
    font-size: 0.875rem;
}

.fc-event.completed {
    opacity: 0.6;
    text-decoration: line-through;
}

.calendar-controls {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
}

.color-option {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s;
    flex-shrink: 0;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.selected {
    border-color: #212529;
    box-shadow: 0 0 0 2px white, 0 0 0 4px #212529;
}

.event-type-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
    flex-shrink: 0;
}

.page-header {
    margin-bottom: 1rem;
}

.page-header h3 {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
}

.btn-new-event {
    width: 100%;
    padding: 0.75rem;
    font-size: 1rem;
    font-weight: 500;
}

.filter-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.filter-buttons .btn {
    padding: 0.5rem;
    font-size: 0.875rem;
}

.calendar-controls .row {
    gap: 1rem;
}

.form-control,
.form-select {
    font-size: 16px; 
    padding: 0.75rem;
}

.form-label {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.color-picker-wrapper {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: flex-start;
}

.modal-body {
    padding: 1rem;
}

.modal-header {
    padding: 1rem;
}

.modal-footer {
    padding: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.modal-footer .btn {
    flex: 1;
    min-width: 120px;
}

.alert {
    border-radius: 8px;
    font-size: 0.875rem;
    padding: 0.75rem 1rem;
}

.fc-toolbar {
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.5rem 0;
}

.fc-toolbar-chunk {
    display: flex;
    justify-content: center;
}

.fc-toolbar-title {
    font-size: 1.1rem;
    text-align: center;
}

.fc-button {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.fc-header-toolbar {
    margin-bottom: 1rem;
}

.fc-list-event {
    padding: 0.75rem;
}

.fc-list-event-title {
    font-size: 0.875rem;
}

.event-detail-item {
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 0.75rem;
}

.event-detail-item h6 {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.event-detail-item p {
    font-size: 0.875rem;
    margin: 0;
}

.btn {
    min-height: 44px;
    touch-action: manipulation;
}

.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
}

@media (min-width: 768px) {
    #calendar {
        padding: 1.5rem;
        border-radius: 12px;
    }

    .calendar-controls {
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .page-header h3 {
        font-size: 1.75rem;
    }

    .btn-new-event {
        width: auto;
        padding: 0.5rem 1.5rem;
    }

    .filter-buttons {
        display: flex;
        grid-template-columns: none;
        flex-wrap: wrap;
    }

    .filter-buttons .btn {
        padding: 0.375rem 1rem;
    }

    .fc-toolbar {
        flex-direction: row;
        gap: 0;
    }

    .fc-toolbar-title {
        font-size: 1.5rem;
    }

    .fc-button {
        padding: 0.4rem 0.65rem;
        font-size: 1rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer .btn {
        flex: initial;
    }

    .color-option {
        width: 30px;
        height: 30px;
    }
}

@media (min-width: 992px) {
    .page-header h3 {
        font-size: 2rem;
    }
}

@media (max-width: 575px) {
    .fc-daygrid-day-number {
        font-size: 0.875rem;
        padding: 0.5rem;
    }

    .fc-col-header-cell {
        font-size: 0.75rem;
        padding: 0.5rem 0.25rem;
    }
}
</style>

<div class="container-fluid px-3 px-md-4 py-3 py-md-4">
    <div class="page-header d-flex flex-column flex-md-row 
                justify-content-between align-items-start align-items-md-center 
                gap-2 gap-md-3 mb-3 mb-md-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-calendar-event me-2"></i>Agenda da Equipe TI
            </h3>
            <p class="text-muted mb-0 small">Gerencie suas tarefas e compromissos</p>
        </div>
        <button class="btn btn-primary btn-new-event" data-bs-toggle="modal" data-bs-target="#eventModal">
            <i class="bi bi-plus-circle me-2"></i>Novo Evento
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="calendar-controls">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <h6 class="mb-2 fw-semibold">Filtros</h6>
                <div class="filter-buttons">
                    <button class="btn btn-sm btn-outline-primary active" data-filter="all">
                        <i class="bi bi-grid me-1 d-none d-md-inline"></i>Todos
                    </button>
                    <button class="btn btn-sm btn-outline-info" data-filter="task">
                        <i class="bi bi-check-square me-1"></i>Tarefas
                    </button>
                    <button class="btn btn-sm btn-outline-warning" data-filter="meeting">
                        <i class="bi bi-people me-1"></i>Reuniões
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" data-filter="reminder">
                        <i class="bi bi-bell me-1"></i>Lembretes
                    </button>
                </div>
            </div>
            
            <?php if ($isAdmin): ?>
            <div class="col-12 col-md-6">
                <h6 class="mb-2 fw-semibold">Visualizar Agenda de:</h6>
                <select class="form-select form-select-sm" id="userFilter">
                    <option value="">Todos da Equipe</option>
                    <?php foreach ($teamMembers as $member): ?>
                    <option value="<?= $member['id'] ?>" <?= $member['id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($member['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="calendar"></div>
</div>

<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down modal-lg">
        <div class="modal-content">
            <form id="eventForm" method="post" action="<?= BASE_URL ?>/?url=calendar/store">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Novo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="eventId">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Título <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="title" id="eventTitle" 
                               placeholder="Ex: Reunião de projeto" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">
                                Data/Hora Início <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" 
                                   name="start_date" id="eventStart" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">
                                Data/Hora Fim <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" class="form-control" 
                                   name="end_date" id="eventEnd" required>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="all_day" id="eventAllDay">
                            <label class="form-check-label" for="eventAllDay">
                                Dia inteiro
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipo de Evento</label>
                        <select class="form-select" name="event_type" id="eventType">
                            <option value="task">Tarefa</option>
                            <option value="meeting">Reunião</option>
                            <option value="reminder">Lembrete</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descrição</label>
                        <textarea class="form-control" name="description" id="eventDescription" 
                                  rows="3" placeholder="Descreva os detalhes do evento..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Local</label>
                        <input type="text" class="form-control" name="location" id="eventLocation" 
                               placeholder="Ex: Sala de Reuniões, Online, etc.">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Cor do Evento</label>
                        <div class="color-picker-wrapper">
                            <div class="color-option selected" data-color="#0d6efd" style="background: #0d6efd;" title="Azul"></div>
                            <div class="color-option" data-color="#198754" style="background: #198754;" title="Verde"></div>
                            <div class="color-option" data-color="#ffc107" style="background: #ffc107;" title="Amarelo"></div>
                            <div class="color-option" data-color="#dc3545" style="background: #dc3545;" title="Vermelho"></div>
                            <div class="color-option" data-color="#6610f2" style="background: #6610f2;" title="Roxo"></div>
                            <div class="color-option" data-color="#fd7e14" style="background: #fd7e14;" title="Laranja"></div>
                            <div class="color-option" data-color="#20c997" style="background: #20c997;" title="Turquesa"></div>
                            <div class="color-option" data-color="#6c757d" style="background: #6c757d;" title="Cinza"></div>
                        </div>
                        <input type="hidden" name="color" id="eventColor" value="#0d6efd">
                    </div>

                    <div class="mb-3" id="statusGroup" style="display: none;">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="eventStatus">
                            <option value="pending">Pendente</option>
                            <option value="completed">Concluído</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteEventBtn" style="display: none;">
                        <i class="bi bi-trash me-1"></i>Excluir
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEventTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewEventContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="completeEventBtn">
                    <i class="bi bi-check-circle me-1"></i>Concluir
                </button>
                <button type="button" class="btn btn-primary" id="editEventBtn">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/pt-br.global.min.js"></script>

<script>
let calendar;
let currentEvent = null;
let currentFilter = 'all';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const isMobile = window.innerWidth < 768;
    const initialView = isMobile ? 'listWeek' : 'dayGridMonth';
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: initialView,
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: isMobile 
                ? 'dayGridMonth,listWeek' 
                : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista'
        },
        height: 'auto',
        events: function(info, successCallback, failureCallback) {
            const userId = document.getElementById('userFilter')?.value || '';
            fetch('<?= BASE_URL ?>/?url=calendar/getEvents&user_id=' + userId)
                .then(response => response.json())
                .then(data => {
                    let filteredData = data;
                    if (currentFilter !== 'all') {
                        filteredData = data.filter(event => 
                            event.extendedProps.event_type === currentFilter
                        );
                    }
                    successCallback(filteredData);
                })
                .catch(error => failureCallback(error));
        },
        editable: true,
        droppable: true,
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            showEventDetails(info.event);
        },
        eventDrop: function(info) {
            updateEventDates(info.event);
        },
        eventResize: function(info) {
            updateEventDates(info.event);
        },
        dateClick: function(info) {
            openEventModal(info.date);
        },
        eventClassNames: function(arg) {
            if (arg.event.extendedProps.status === 'completed') {
                return ['completed'];
            }
            return [];
        },
        windowResize: function(view) {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
            }
        }
    });
    
    calendar.render();

    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            calendar.refetchEvents();
        });
    });

    document.querySelectorAll('.color-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('eventColor').value = this.dataset.color;
        });
    });

    <?php if ($isAdmin): ?>
    document.getElementById('userFilter')?.addEventListener('change', function() {
        calendar.refetchEvents();
    });
    <?php endif; ?>

    document.getElementById('deleteEventBtn').addEventListener('click', function() {
        if (confirm('Tem certeza que deseja excluir este evento?')) {
            deleteEvent(currentEvent.id);
        }
    });

    document.getElementById('completeEventBtn').addEventListener('click', function() {
        if (currentEvent) {
            completeEvent(currentEvent.id);
        }
    });

    document.getElementById('editEventBtn').addEventListener('click', function() {
        if (currentEvent) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewEventModal'));
            modal.hide();
            setTimeout(() => editEvent(currentEvent), 200);
        }
    });
});

function openEventModal(date = null) {
    document.getElementById('modalTitle').textContent = 'Novo Evento';
    document.getElementById('eventForm').action = '<?= BASE_URL ?>/?url=calendar/store';
    document.getElementById('eventForm').reset();
    document.getElementById('eventId').value = '';
    document.getElementById('deleteEventBtn').style.display = 'none';
    document.getElementById('statusGroup').style.display = 'none';
    
    document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
    document.querySelector('.color-option[data-color="#0d6efd"]').classList.add('selected');
    document.getElementById('eventColor').value = '#0d6efd';
    
    if (date) {
        const dateStr = date.toISOString().slice(0, 16);
        document.getElementById('eventStart').value = dateStr;
        const endDate = new Date(date);
        endDate.setHours(endDate.getHours() + 1);
        document.getElementById('eventEnd').value = endDate.toISOString().slice(0, 16);
    }
    
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
}
function editEvent(event) {
    document.getElementById('modalTitle').textContent = 'Editar Evento';
    document.getElementById('eventForm').action = '<?= BASE_URL ?>/?url=calendar/update';
    document.getElementById('eventId').value = event.id;
    document.getElementById('eventTitle').value = event.title;
    
    const startStr = event.extendedProps.start_date || event.start;
    const endStr = event.extendedProps.end_date || event.end;
    
    document.getElementById('eventStart').value = startStr.replace(' ', 'T').substring(0, 16);
    document.getElementById('eventEnd').value = endStr.replace(' ', 'T').substring(0, 16);
    
    document.getElementById('eventAllDay').checked = event.allDay;
    document.getElementById('eventDescription').value = event.extendedProps.description || '';
    document.getElementById('eventLocation').value = event.extendedProps.location || '';
    document.getElementById('eventType').value = event.extendedProps.event_type || 'task';
    document.getElementById('eventStatus').value = event.extendedProps.status || 'pending';
    document.getElementById('eventColor').value = event.backgroundColor;
    
    document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
    const colorOption = document.querySelector(`.color-option[data-color="${event.backgroundColor}"]`);
    if (colorOption) colorOption.classList.add('selected');
    
    document.getElementById('deleteEventBtn').style.display = 'inline-block';
    document.getElementById('statusGroup').style.display = 'block';
    
    const modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
}

function showEventDetails(event) {
    currentEvent = event;
    const props = event.extendedProps;
    
    const typeIcons = {
        'task': 'check-square',
        'meeting': 'people',
        'reminder': 'bell',
        'other': 'calendar-event'
    };
    
    const typeLabels = {
        'task': 'Tarefa',
        'meeting': 'Reunião',
        'reminder': 'Lembrete',
        'other': 'Outro'
    };
    
    const statusLabels = {
        'pending': '<span class="badge bg-warning">Pendente</span>',
        'completed': '<span class="badge bg-success">Concluído</span>',
        'cancelled': '<span class="badge bg-danger">Cancelado</span>'
    };
    
    const startStr = props.start_date || event.start;
    const endStr = props.end_date || event.end;
    
    let content = `
        <div class="event-detail-item">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="event-type-icon">
                    <i class="bi bi-${typeIcons[props.event_type] || 'calendar-event'}"></i>
                </div>
                <span class="text-muted">${typeLabels[props.event_type] || 'Evento'}</span>
                ${statusLabels[props.status] || ''}
            </div>
        </div>
        
        <div class="event-detail-item">
            <h6>Período</h6>
            <p>
                <i class="bi bi-calendar me-2"></i>
                ${formatDateTime(startStr)} 
                ${endStr ? ' até ' + formatDateTime(endStr) : ''}
            </p>
        </div>
    `;
    
    if (props.description) {
        content += `
            <div class="event-detail-item">
                <h6>Descrição</h6>
                <p>${escapeHtml(props.description)}</p>
            </div>
        `;
    }
    
    if (props.location) {
        content += `
            <div class="event-detail-item">
                <h6>Local</h6>
                <p>
                    <i class="bi bi-geo-alt me-2"></i>${escapeHtml(props.location)}
                </p>
            </div>
        `;
    }
    
    <?php if ($isAdmin): ?>
    if (props.user_name) {
        content += `
            <div class="event-detail-item">
                <h6>Responsável</h6>
                <p>
                    <i class="bi bi-person me-2"></i>${escapeHtml(props.user_name)}
                </p>
            </div>
        `;
    }
    <?php endif; ?>
    
    document.getElementById('viewEventTitle').textContent = event.title;
    document.getElementById('viewEventContent').innerHTML = content;
    
    const canEdit = <?= $user['role'] === 'admin' ? 'true' : 'false' ?> || props.user_id == <?= $user['id'] ?>;
    document.getElementById('completeEventBtn').style.display = 
        (props.status !== 'completed' && canEdit) ? 'inline-block' : 'none';
    document.getElementById('editEventBtn').style.display = canEdit ? 'inline-block' : 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('viewEventModal'));
    modal.show();
}

function updateEventDates(event) {
    const startDate = event.start.toISOString();
    const endDate = event.end ? event.end.toISOString() : startDate;
    
    fetch('<?= BASE_URL ?>/?url=calendar/updateDates', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${event.id}&start_date=${startDate}&end_date=${endDate}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Erro ao atualizar evento');
            event.revert();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        event.revert();
    });
}

function completeEvent(eventId) {
    fetch('<?= BASE_URL ?>/?url=calendar/complete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${eventId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            calendar.refetchEvents();
            const modal = bootstrap.Modal.getInstance(document.getElementById('viewEventModal'));
            modal.hide();
        } else {
            alert('Erro ao completar evento');
        }
    });
}

function deleteEvent(eventId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= BASE_URL ?>/?url=calendar/delete';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'id';
    input.value = eventId;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function formatDateTime(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleString('pt-BR', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

function formatDateTimeLocal(date) {
    if (!date) return '';
    
    const d = new Date(date);
    
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/../layouts/main.php'; 
?>