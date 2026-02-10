<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('personal')->get();

        return response()->json(UserResource::collection($users));
    }

    public function store(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $user = $id ? User::find($id) : new User();

        if ($id && ! $user) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        $personalRules = [
            'personal' => [$id ? 'sometimes' : 'required', 'array'],
            'personal.NombrePersonal' => [$id ? 'sometimes' : 'required', 'string', 'max:255'],
            'personal.ApellidoPaterno' => [$id ? 'sometimes' : 'required', 'string', 'max:255'],
            'personal.ApellidoMaterno' => ['nullable', 'string', 'max:255'],
            'personal.SexoID' => ['nullable', 'string', 'max:50'],
            'personal.Telefono' => ['nullable', 'string', 'max:50'],
            'personal.Celular' => ['nullable', 'string', 'max:50'],
            'personal.Correo' => ['nullable', 'email', 'max:255'],
            'personal.DNI' => ['nullable', 'string', 'max:50'],
            'personal.CarnetExtranjeria' => ['nullable', 'string', 'max:50'],
            'personal.Nacionalidad' => ['nullable', 'string', 'max:100'],
            'personal.EmpresaID' => ['nullable', 'string', 'max:50'],
            'personal.Estado' => ['nullable', 'string', 'max:50'],
            'personal.CorreoEmpresa' => ['nullable', 'email', 'max:255'],
            'personal.photo' => ['nullable', 'string', 'max:255'],
            'personal.created_at' => ['nullable', 'date'],
            'personal.created_by' => ['nullable', 'string', 'max:50'],
            'personal.updated_at' => ['nullable', 'date'],
            'personal.updated_by' => ['nullable', 'string', 'max:50'],
            'personal.tipoUsuario' => ['nullable', 'string', 'max:50'],
            'personal.centroMedicoID' => ['nullable', 'string', 'max:50'],
            'personal.rne' => ['nullable', 'string', 'max:50'],
            'personal.cmp' => ['nullable', 'string', 'max:50'],
        ];

        $rules = [
            'name' => [$id ? 'sometimes' : 'required', 'string', 'max:255'],
            'email' => [$id ? 'sometimes' : 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$id ? 'sometimes' : 'required', 'nullable', 'string', 'min:6'],
        ];

        $data = $request->validate(array_merge($rules, $personalRules));

        $personalPayload = [];
        $personalInput = $data['personal'] ?? $request->input('personal');
        if (is_array($personalInput)) {
            $personalPayload = Arr::only($personalInput, [
                'NombrePersonal',
                'ApellidoPaterno',
                'ApellidoMaterno',
                'SexoID',
                'Telefono',
                'Celular',
                'Correo',
                'DNI',
                'CarnetExtranjeria',
                'Nacionalidad',
                'EmpresaID',
                'Estado',
                'CorreoEmpresa',
                'photo',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'tipoUsuario',
                'centroMedicoID',
                'rne',
                'cmp',
            ]);
        }
        $data = Arr::except($data, ['personal']);

        $payload = array_merge($data, $request->only([
            'current_team_id',
            'profile_photo_path',
            'created_by',
            'updated_by',
            'personal_id',
            'state',
            'photo',
            'last_signIn_at',
            'users_grupo_id',
            'users_roles_id',
            'tokendigital',
            'tokendigitaldt',
            'passsecure',
            'roles_id',
            'id_samk',
            'skip_two_factor',
        ]));

        if (array_key_exists('password', $payload) && ($payload['password'] === null || $payload['password'] === '')) {
            unset($payload['password']);
        }

        DB::transaction(function () use ($id, $user, $payload, $personalPayload) {
            if (! $id) {
                $personal = new Personal();
                $personal->forceFill($personalPayload);
                $personal->save();
                $payload['personal_id'] = $personal->PersonalID;
            } elseif ($personalPayload) {
                $existingPersonal = $user->personal;
                if ($existingPersonal) {
                    $existingPersonal->forceFill($personalPayload);
                    $existingPersonal->save();
                } else {
                    $personal = new Personal();
                    $personal->forceFill($personalPayload);
                    $personal->save();
                    $payload['personal_id'] = $personal->PersonalID;
                }
            }

            $user->forceFill($payload);
            $user->save();
        });

        $user->refresh()->load('personal');

        return response()->json(new UserResource($user), $id ? 200 : 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::with('personal')->find($id);

        if (! $user) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return response()->json(new UserResource($user));
    }
}
