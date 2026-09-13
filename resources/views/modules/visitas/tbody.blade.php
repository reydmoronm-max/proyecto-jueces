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
            <h6>
                <span class="badge bg-success me-1"><i class="fa-solid fa-phone"></i></span>
                {{ optional($item->persona)->telefono ?? '-' }}
            </h6>
        </td>
        <td>
            <h6 style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                title="{{ optional($item->persona)->direccion ?? '-' }}" data-bs-toggle="tooltip">
                <span class="badge bg-danger me-1"><i class="fa-solid fa-house"></i></span>
                {{ optional($item->persona)->direccion ?? '-' }}</h6>
        </td>
        <td>
            <h6>{{ $item->de_parte }}</h6>
        </td>
        <td>
            <h6>{{ $item->created_at->format('d/m/Y h:i A') }}</h6>
        </td>
        <td>
            <button class="btn btn-sm btn-light" title="Ver propósito de visita" data-bs-toggle="tooltip" onclick="mostrarProposito({{ $item->id }})">
                <i class="ri-eye-fill"></i>
            </button>
            <div id="proposito-text-{{ $item->id }}" class="d-none">{{ $item->proposito }}</div>
            
            <button class="btn btn-sm btn-warning" onclick="editarVisita({{ $item->id }})" title="Editar visita">
                <i class="ri-edit-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
