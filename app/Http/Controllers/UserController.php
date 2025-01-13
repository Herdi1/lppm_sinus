<?php

namespace App\Http\Controllers;

use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('get_users,api'), only: ['getAllUsers', 'getUserByRole']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create_user,api'), only: ['createUser']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('reset_password,api'), only: ['resetPassword']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('assign_role_to_user,api'), only: ['assignRoleToUser']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('remove_role_from_user,api'), only: ['RemoveRoleFromUser']),
        ];
    }

    public function getAllUsers()
    {
        $users = User::with('roles')->where('id', '!=', auth()->guard('api')->user()->id)->get();

        return response()->json($users);
    }

    public function getUserByRole($roleId)
    {
        $users = User::whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->get();

        return response()->json($users);
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $password = Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        $role = Role::findOrFail('Dosen');
        $user->assignRole($role);

        $mailData = [
            'username' => $user->name,
            'email' => $user->email,
            'password' => $password
        ];

        Mail::to($user->email)->send(new NewUserMail($mailData));

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna baru berhasil dibuat.'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat membuat pengguna baru'
        ]);
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $password = Str::random(8);

        $user->update([
            'password' => Hash::make($password)
        ]);

        $mailData = [
            'username' => $user->name,
            'email' => $user->email,
            'password' => $password
        ];

        Mail::to($user->email)->send(new NewUserMail($mailData));

        return response()->json([
            'message' => 'Password berhasil direset.'
        ], 200);
    }

    public function assignRoleToUser(Request $request)
    {
        $role = Role::findOrFail($request->roleId);
        $user = User::findOrFail($request->userId);

        $user->assignRole($role);
        return response()->json(['message' => 'Role berhasil disematkan']);
    }

    public function RemoveRoleFromUser(Request $request)
    {
        $role = Role::findOrFail($request->roleId);
        $user = User::findOrFail($request->userId);

        $user->removeRole($role);
        return response()->json(['message' => 'Role berhasil dihapus']);
    }

    public function assignPermissionToRole(Request $request)
    {
        $permission = Permission::findOrFail($request->permissionId);
        $role = Role::findOrFail($request->roleId);

        $role->givePermissionTo($permission);
        return response()->json(['message' => 'Ijin berhasil ditambahkan']);
    }
}
