<x-app-layout :assets="$assets ?? []">
    <div>
        <?php
        $id = $id ?? null;
        ?>
        @if(isset($id))
        {!! Form::model($data, ['route' => ['users.update', $id], 'method' => 'patch' , 'enctype' => 'multipart/form-data']) !!}
        @else
        {!! Form::open(['route' => ['users.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        @endif
        <div class="row">
            <div class="col-12 ">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{$id !== null ? 'Update' : 'Add' }} User </h4>
                        </div>
                        <div class="card-action">
                            <a href="{{route('users.index')}}" class="btn btn-sm btn-primary" role="button">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="fname">First Name: <span class="text-danger">*</span></label>
                                    {{ Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => 'First Name', 'required']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="lname">Last Name: <span class="text-danger">*</span></label>
                                    {{ Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => 'Last Name' ,'required']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="email">Email: <span class="text-danger">*</span></label>
                                    {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Enter e-mail', 'required']) }}
                                </div>
                            </div>
                            <hr>
                            <h5 class="mb-3">Security</h5>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-label" for="uname">User Name: <span class="text-danger">*</span></label>
                                    {{ Form::text('username', old('username'), ['class' => 'form-control', 'required', 'placeholder' => 'Enter Username']) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="pass">Password: @if(!isset($id)) <span class="text-danger">*</span> @endif</label>
                                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off', 'required' => !isset($id)]) }}
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="rpass">Confirm Password: @if(!isset($id)) <span class="text-danger">*</span> @endif</label>
                                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'autocomplete' => 'off', 'required' => !isset($id)]) }}
                                </div>
                            </div>
                            <hr>
                            <h5 class="mb-3">Permissions</h5>
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-center">View</th>
                                            <th class="text-center">Edit</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                        <tr>
                                            <td>{{ $permission->title }}
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    name="permissions[{{ $permission->id }}][view]"
                                                    value="2"
                                                    id="{{ $permission->id }}_view"
                                                    {{ isset($userPermissions[$permission->id]) && ($userPermissions[$permission->id]->permission_value & 2) ? 'checked' : '' }}>
                                            </td>

                                            <td class="text-center">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    name="permissions[{{ $permission->id }}][edit]"
                                                    value="4"
                                                    id="{{ $permission->id }}_edit"
                                                    {{ isset($userPermissions[$permission->id]) && ($userPermissions[$permission->id]->permission_value & 4) ? 'checked' : '' }}>
                                            </td>

                                            <td class="text-center">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    name="permissions[{{ $permission->id }}][delete]"
                                                    value="8"
                                                    id="{{ $permission->id }}_delete"
                                                    {{ isset($userPermissions[$permission->id]) && ($userPermissions[$permission->id]->permission_value & 8) ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary">{{$id !== null ? 'Update' : 'Add' }} User</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</x-app-layout>
