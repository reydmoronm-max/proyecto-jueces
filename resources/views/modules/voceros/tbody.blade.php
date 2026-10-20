@foreach ($items as $item)
    <tr data-search="{{ implode(' ', array_filter([
        $item->id,
        $item->persona?->id,
        $item->persona?->cedula_tipo,
        $item->persona?->cedula,
        $item->persona?->nombres,
        $item->persona?->apellidos,
        $item->categoria_vocero,
        \Carbon\Carbon::parse($item->fecha_eleccion)->format('d-m-Y'),
        \Carbon\Carbon::parse($item->fecha_eleccion)->format('Y-m-d'),
    ])) }}" data-status="{{ $item->activo ? 'activo' : 'inactivo' }}">
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
