@foreach ($items as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <h6>{{ $item->nombre }} {{ $item->apellido }}</h6>
            </div>
        </td>
        <td>
            <h6>{{ $item->cedula }}</h6>
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
            <form id="delete-form-{{ $item->id }}" action="{{ route('visitas.destroy', $item->id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
            <button class="btn btn-sm btn-danger" onclick="confirmDeleteVisita({{ $item->id }})">
                <i class="ri-delete-bin-6-fill"></i>
            </button>
        </td>
    </tr>
@endforeach
