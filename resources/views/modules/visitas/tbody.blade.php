@foreach ($items as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <h6>{{ optional($item->persona)->nombres }} {{ optional($item->persona)->apellidos }}</h6>
            </div>
        </td>
        <td>
            <h6>
                <span class="badge {{ optional($item->persona)->cedula_tipo == 'V' ? 'bg-primary' : 'bg-warning text-dark' }} me-1">{{ optional($item->persona)->cedula_tipo }}</span>
                {{ optional($item->persona)->cedula }}
            </h6>
        </td>
        <td>
            <h6>{{ $item->de_parte }}</h6>
        </td>
        <td>
            <button class="btn btn-sm btn-info" style="background-color: #007bff; border-color: #007bff;" onclick="mostrarProposito({{ $item->id }})">
                <i class="ri-eye-fill"></i> Ver
            </button>
            <div id="proposito-text-{{ $item->id }}" class="d-none">{{ $item->proposito }}</div>
        </td>
        <td>
            <h6>{{ $item->created_at->format('d/m/Y h:i A') }}</h6>
        </td>
        <td>
            <button class="btn btn-sm btn-warning" onclick="editarVisita({{ $item->id }})" title="Editar visita">
                <i class="ri-edit-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
