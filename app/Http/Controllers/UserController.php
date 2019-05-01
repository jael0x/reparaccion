<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;

class UserController extends Controller
{

        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authAPI:api',['only' => ['update', 'show','destroy','logout']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return null;   
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');                
        $credentials["password"]=$credentials["password"];  
        // dd($credentials);
        // dd(Auth::attempt($credentials));
        if (auth()->attempt($credentials)) {
            // Authentication passed...
            $token = Str::random(60);
            auth()->user()->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();
            return auth()->user();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),            
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60),
        ]);        
        $role=Role::where('name',"user")->get();
        
        $user->roles()->attach($role);

        return $user;
    }

    /**
     * Display the specified resource.
     *     
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
        return auth()->user();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user=auth()->user();                
        $user->name=$request->input('name');
        $user->email=$request->input('email');
        $user->username=$request->input('username');
        if($request->hasFile('avatar'))
        {
            $user->avatar=$request->file('avatar')->store('users');
        }                
        $user->roles()->attach($request->input('idRole'));
        $user->telefono=$request->input('telefono');
        $user->direccion=$request->input('direccion');
        $user->ubicacion=$request->input('ubicacion');        
        return $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function logout()
    {
        return Auth::logout();
    }

    public function errlogin()
    {
        return [
            'failed' => 'No user login'];
    }
    
}
