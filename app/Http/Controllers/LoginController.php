<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'name' => 'required',
                'second_name' => 'required',
                'lastname' => 'required',
                'mother_lastname' => 'required',
                'RFC' => 'required',
                'genre' => 'required',
                'INE_Front' => 'required',
                'INE_Back' => 'required',
                'email' => 'required|unique:users,email',
                'phone' => 'required',
                'direction' => 'required',
                'password' => 'required',
                'rol' => 'required',
            ]
        );
        if($validate->fails()){
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => $validate->errors()
            ],400);
        }


        $user = User::create([
            'name' => $request->name,
            'second_name' => $request->second_name,
            'lastname' => $request->lastname,
            'mother_lastname' => $request->mother_lastname,
            'RFC' => $request->RFC,
            'genre' => $request->genre,
            'INE_Front' => $request->INE_Front,
            'INE_Back' => $request->INE_Back,
            'email' => $request->email,
            'phone' => $request->phone,
            'direction' => $request->direction,
            'status' => 1,
            'rol' => $request->rol,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario Registrado correctamente'
        ], 200);

    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
        [
            'email' => 'required|max:250|email',
            'password' => 'required|max:250',
        ]);
        if ($validator->fails())
        {
            return response()->json([
                "status"    => 400,
                "message"   => "Error en las validaciones",
                "error"     => [$validator->errors()],
                "data"      => []
            ],400);
        }
        $user = User::where('email', $request['email'])->firstOrfail();
        if(!is_null($user) && Hash::check($request->password,$user->password))
        {
            $user->save();
            if($user->status == 1)
            {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'Token' => $token,
                    'name'=> $user->name,
                ],200);
            }
            else{
                return response()->json([
                    'message' => 'Usuario bloqueado'
                ],400);
            }
        }
        else{
            return response()->json([
                'message' => 'Credenciales no correctas'
            ],400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->Tokens()->delete();
        return response()->json([
            "status" => 200,
            "msg" => "Sesion Cerrada"
        ]);
    }
}
