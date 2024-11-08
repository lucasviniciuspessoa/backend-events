<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'=> 'string|required|min:3|max:100',
            'email'=>'string|required|email|unique:users',
            'password'=> 'string|required|min:3'

        ]);

        if ($validator->fails()) {
            return response()->json([

                'message'=> 'Erro de validação',
                'errors'=> $validator->errors()
            ],400);
        }

        try {
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> $request->password
            ]);

            $token = $user->createToken('app_token')->plainTextToken;
        }catch(\Exception $e) {
            return response()->json([
                'message' => 'Erro ao  registrar um usuário',
                'error'=> $e->getMessage()
            ]);
        }


        return response()->json([
            'message' => 'usuário registrado com sucesso!',
            'user'=>$user,
            'token' => $token
        ]);


    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:7',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'token' => $token,
        ]);
    }
    //
}
