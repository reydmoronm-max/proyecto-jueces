@foreach ($items as $item)
    <tr>
        <td class="fw-bold">{{ $item->nombre }}</td>
        <td>{{ $item->descripcion ?? 'Sin descripción' }}</td>
        <td>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input estado-categoria" type="checkbox"
                    id="cat-{{ $item->id }}" data-id="{{ $item->id }}"
                    {{ $item->activo ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-warning" title="Editar"
                onclick="editarCategoria({{ $item->id }})">
                <i class="ri-pencil-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
