<?php ob_start(); 

$user = Auth::user();
$isAdmin = $user['role'] === 'admin';
?>

<style>
#calendar {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.fc-event {
    cursor: pointer;
    border-radius: 4px;
}

.fc-event.completed {
    opacity: 0.6;
    text-decoration: line-through;
}

.calendar-controls {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1.5rem;
}

.color-option {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    transition: all 0.2s;
}

.color-option:hover {
    transform: scale(1.1);
}

.color-option.selected {
    border-color: #212529;
}

.event-type-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f8f9fa;
}
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-calendar-event me-2"></i>Agenda da Equipe TI
            </h3>
            <p class="text-muted mb-0 small">Gerencie suas tarefas e compromissos</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
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
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-2">Filtros</h6>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-sm btn-outline-primary active" data-filter="all">
                        Todos
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
            <div class="col-md-6 text-end">
                <h6 class="mb-2">Visualizar Agenda de:</h6>
                <select class="form-select form-select-sm d-inline-block w-auto" id="userFilter">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="eventForm" method="post" action="<?= BASE_URL ?>/?url=calendar/store">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Novo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="eventId">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" id="eventTitle" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data/Hora Início <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="start_date" id="eventStart" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Data/Hora Fim <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="end_date" id="eventEnd" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="all_day" id="eventAllDay">
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
                        <textarea class="form-control" name="description" id="eventDescription" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Local</label>
                        <input type="text" class="form-control" name="location" id="eventLocation" placeholder="Ex: Sala de Reuniões, Online, etc.">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Cor</label>
                        <div class="d-flex gap-2">
                            <div class="color-option selected" data-color="#0d6efd" style="background: #0d6efd;"></div>
                            <div class="color-option" data-color="#198754" style="background: #198754;"></div>
                            <div class="color-option" data-color="#ffc107" style="background: #ffc107;"></div>
                            <div class="color-option" data-color="#dc3545" style="background: #dc3545;"></div>
                            <div class="color-option" data-color="#6610f2" style="background: #6610f2;"></div>
                            <div class="color-option" data-color="#fd7e14" style="background: #fd7e14;"></div>
                            <div class="color-option" data-color="#20c997" style="background: #20c997;"></div>
                            <div class="color-option" data-color="#6c757d" style="background: #6c757d;"></div>
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
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEventTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewEventContent">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="completeEventBtn">
                    <i class="bi bi-check-circle me-1"></i>Marcar como Concluído
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="editEventBtn">
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

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
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
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        editable: true,
        droppable: true,
        eventClick: function(info) {
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
        }
    });
    
    calendar.render();

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
            editEvent(currentEvent);
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
    document.getElementById('eventStart').value = formatDateTimeLocal(event.start);
    document.getElementById('eventEnd').value = formatDateTimeLocal(event.end || event.start);
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
    
    let content = `
        <div class="mb-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="event-type-icon">
                    <i class="bi bi-${typeIcons[props.event_type] || 'calendar-event'}"></i>
                </div>
                <span class="text-muted">${typeLabels[props.event_type] || 'Evento'}</span>
                ${statusLabels[props.status] || ''}
            </div>
        </div>
        
        <div class="mb-3">
            <h6 class="text-muted small mb-1">Período</h6>
            <p class="mb-0">
                <i class="bi bi-calendar me-2"></i>
                ${formatDateTime(event.start)} 
                ${event.end ? ' até ' + formatDateTime(event.end) : ''}
            </p>
        </div>
    `;
    
    if (props.description) {
        content += `
            <div class="mb-3">
                <h6 class="text-muted small mb-1">Descrição</h6>
                <p class="mb-0">${escapeHtml(props.description)}</p>
            </div>
        `;
    }
    
    if (props.location) {
        content += `
            <div class="mb-3">
                <h6 class="text-muted small mb-1">Local</h6>
                <p class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>${escapeHtml(props.location)}
                </p>
            </div>
        `;
    }
    
    <?php if ($isAdmin): ?>
    if (props.user_name) {
        content += `
            <div class="mb-3">
                <h6 class="text-muted small mb-1">Responsável</h6>
                <p class="mb-0">
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
    return d.toISOString().slice(0, 16);
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