@foreach ($items as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <h6>{{ $item->nombre }}</h6>
            </div>
        </td>
        <td>
            <h6>
                <span class="badge bg-secondary text-white me-1">RIF</span>
                {{ $item->rif }}
            </h6>
        </td>
        <td>
            @if ($item->jefe)
                <h6>
                    <span class="badge bg-primary me-1">{{ $item->jefe->cedula_tipo }}</span>
                    {{ $item->jefe->cedula }} - 
                    {{ $item->jefe->nombres }} {{ $item->jefe->apellidos }}
                </h6>
            @else
                <span class="text-danger">No asignado</span>
            @endif
        </td>
        <td>
            <h6 style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->direccion }}" data-bs-toggle="tooltip">
                {{ $item->direccion }}
            </h6>
        </td>
        <td>
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-warning me-1" onclick="editarConsejo({{ $item->id }})" title="Editar consejo">
                    <i class="ri-edit-fill"></i>
                </button>
                <form action="{{ route('consejos-comunales.destroy', $item->id) }}" method="POST" class="d-inline form-eliminar">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-consejo" title="Eliminar consejo">
                        <i class="ri-delete-bin-fill"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
