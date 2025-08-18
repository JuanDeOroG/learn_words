{{-- filepath: resources/views/components/study/config-view.blade.php --}}
<!-- Botón para mostrar el modal -->
<div class="text-end mb-2">
    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#sessionConfigModal">
        Show Session Settings
    </button>
</div>

<!-- Modal de configuración de sesión -->
<div class="modal fade" id="sessionConfigModal" tabindex="-1" aria-labelledby="sessionConfigModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionConfigModalLabel">Session Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>Collections:</strong>
                        <div style="max-height:200px; overflow-y:auto;">
                            @foreach($collections as $collection)
                                <span class="badge bg-primary me-1 mb-1">{{ $collection->name }}</span>
                            @endforeach
                        </div>
                    </li>
                    <li class="list-group-item"><strong>Goal:</strong>
                        @if($goal['type'] === 'time')
                            Study for {{ $goal['value']->diffForHumans(now()) }} (until {{ $goal['value']->format('H:i') }})
                        @elseif($goal['type'] === 'quantity')
                            {{ $goal['value'] }} words
                        @else
                            No goal
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Order:</strong> {{ ucfirst($order) }}</li>
                    <li class="list-group-item"><strong>Presentation mode:</strong> {{ ucfirst($mode) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>