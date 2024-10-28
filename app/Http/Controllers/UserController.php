<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\User;
use App\Helpers\AuthHelper;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Models\UserPermission;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public $isAdmin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            $this->isAdmin = $user->hasRole('admin');
            return $next($request);
        });
    }
    public function index(UsersDataTable $dataTable, Request $request)
    {
        if ($this->isAdmin) {
            $query = User::query()->where('user_type', 'user');
            if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
                $query->where('status', $request->status);
            }

            $users = $query->get();
            return $dataTable->render('users.list', compact('users'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view this page!');
    }

    public function create()
    {
        if ($this->isAdmin) {
            $roles = Role::where('status', 1)->get()->pluck('title', 'id');
            $permissions = Permission::get();

            return view('users.form', compact('roles', 'permissions'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view this page!');
    }

    public function store(UserRequest $request)
    {
        if ($this->isAdmin) {
            $request['password'] = bcrypt($request->password);

            $request['username'] = $request->username ?? stristr($request->email, "@", true) . rand(100, 1000);

            $user = User::create($request->all());

            // Process permissions from the request
            $this->processPermissions($user, $request->permissions);

            $user->assignRole('user');

            return redirect()->route('users.index')->with('success', 'User added successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view this page!');
    }

    public function show($id)
    {
        if ($this->isAdmin) {
            $user = User::with('userProfile', 'roles')->findOrFail($id);
            $userPermissions = UserPermission::where('user_id', $id)->get();

            return view('users.profile', compact('user',));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to view this page!');
    }

    public function edit($id)
    {
        if ($this->isAdmin) {
            $data = User::with('userProfile', 'roles')->findOrFail($id);

            $data['user_type'] = $data->roles->pluck('id')[0] ?? null;

            $roles = Role::where('status', 1)->get()->pluck('title', 'id');

            $permissions = Permission::get();

            // Key userPermissions by permission_id for easier access
            $userPermissions = UserPermission::where('user_id', $id)
                ->get()
                ->keyBy('permission_id'); // This ensures $userPermissions[permission_id] is accessible directly


            return view('users.form', compact('data', 'id', 'roles', 'permissions', 'userPermissions'));
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit this page!');
    }

    public function update(UserRequest $request, $id)
    {
        if ($this->isAdmin) {
            // dd($request->all());
            $user = User::with('userProfile')->findOrFail($id);

            $request['password'] = $request->password != '' ? bcrypt($request->password) : $user->password;

            // User user data...
            $user->fill($request->all())->update();

            // Process permissions from the request
            $this->processPermissions($user, $request->permissions);

            if (auth()->check()) {
                return redirect()->route('users.index')->with('success', 'User updated successfully');
            }
            return redirect()->back()->with('success', 'User updated successfully');
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit this page!');
    }

    public function destroy($id)
    {
        if ($this->isAdmin) {
            $user = User::findOrFail($id);
            $status = 'errors';
            $message = "Error while deleting user account";

            if ($user != '') {
                $user->delete();
                $status = 'success';
                $message = "User account deleted successfully";
            }

            if (request()->ajax()) {
                return response()->json(['status' => true, 'message' => $message, 'datatable_reload' => 'dataTable_wrapper']);
            }

            return redirect()->back()->with($status, $message);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to delete this page!');
    }


    public function changeStatus(Request $request)
    {
        if ($this->isAdmin) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user = User::findOrFail($request->input('user_id'));

            $user->status = $user->status == 'active' ? 'inactive' : 'active';
            $user->save();

            // Return a response (can be JSON if used in an AJAX request)
            return response()->json([
                'success' => true,
                'message' => 'User status has been updated successfully!',
                'new_status' => $user->status
            ]);
        }
        return redirect()->route('dashboard')->with('error', 'You do not have permission to edit this page!');
    }

    protected function processPermissions(User $user, $permissions)
    {
        // Clear any existing permissions for the user
        UserPermission::where('user_id', $user->id)->delete();

        // Iterate through the submitted permissions and apply them to the user
        if ($permissions) {
            foreach ($permissions as $permissionId => $actions) {
                $permissionValue = 0;
                if (isset($actions['view'])) {
                    $permissionValue = $permissionValue | $actions['view'];
                }
                if (isset($actions['edit'])) {
                    $permissionValue = $permissionValue | $actions['edit'];
                }
                if (isset($actions['delete'])) {
                    $permissionValue = $permissionValue | $actions['delete'];
                    // $user->givePermissionTo('delete-' . $permissionId);
                }
                //store $permissionId and $permissionValue
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_id' => $permissionId,
                    'permission_value' => $permissionValue
                ]);
            }
        }
    }
}
