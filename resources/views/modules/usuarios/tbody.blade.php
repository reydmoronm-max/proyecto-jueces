@foreach ($items as $item)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <img class="rounded img-fluid avatar-40 me-3"
                    src="{{ asset('images/avatars/usuario.png') }}" alt="profile">
                <h6>{{ $item->nombre }} {{ $item->apellido }}</h6>
            </div>
        </td>
        <td>
            <h6>{{ $item->user }}</h6>
        </td>
        <td>
            <a href="#" class="btn btn-sm btn-primary"><i class="ri-lock-password-fill"></i></a>
        </td>
        <td>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input" type="checkbox" id="{{ $item->id }}" 
                {{ $item->activo ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <h6>{{ $item->rol }}</h6>
        </td>
        <td>
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario">
                <i class="ri-edit-fill"></i>
            </button>
        </td>
    </tr>
@endforeach