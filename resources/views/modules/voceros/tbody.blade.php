@foreach ($items as $item)
    <tr>
        <td>
            <span class="badge bg-primary me-1">{{ $item->persona->cedula_tipo ?? 'V' }}</span>
            {{ $item->persona->cedula }}
        </td>
        <td>{{ $item->persona->nombres }} {{ $item->persona->apellidos }}</td>
        <td>{{ $item->categoria_vocero }}</td>
        <td>{{ \Carbon\Carbon::parse($item->fecha_eleccion)->format('d-m-Y') }}</td>
        <td>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input estado-vocero" type="checkbox"
                    id="vocero-{{ $item->id }}" data-id="{{ $item->id }}"
                    {{ $item->activo ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-warning" title="Editar"
                onclick="editarVocero({{ $item->id }})">
                <i class="ri-pencil-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
