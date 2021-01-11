<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->where('role', 2)->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:256',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ];
        $messages = [

            'name.required' => 'Es indispensable ingresar el nombre completo.',
            'name.max' => 'El nombre del vendedor es demasiado extenso.',
            'email.required' => 'Es indispensable ingresar el e-mail del vendedor.',
            'email.email' => 'El e-mail ingresado no es válido.',
            'email.max' => 'El e-mail es demasiado extenso.',
            'email.unique' => 'El e-mail ya se encuentra en uso.',
            'password.required' => 'Es necesario ingresar una contraseña',
            'password.min' => 'La contraseña debe presentar al menos 6 caracteres.',
        ];
        $this->validate($request, $rules, $messages);

        $evaluator = new User();
        $evaluator->name = $request->input('name');
        $evaluator->email = $request->input('email');
        $evaluator->password = bcrypt($request->input('password'));
        $evaluator->save();

        return redirect('/users')->with('notification', 'El vendedor se ha registrado exitosamente.');
    }

    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required|max:256',
            'email' => "required|email|max:255|unique:users,email,$id",
            'password' => 'nullable|min:6'
        ];
        $messages = [
            'name.required' => 'Es indispensable ingresar el nombre completo.',
            'name.max' => 'El nombre del vendedor es demasiado extenso.',
            'email.required' => 'Es indispensable ingresar el e-mail del vendedor.',
            'email.email' => 'El e-mail ingresado no es válido.',
            'email.max' => 'El e-mail es demasiado extenso.',
            'email.unique' => 'El e-mail ya se encuentra en uso.',
            'password.min' => 'La contraseña debe presentar al menos 6 caracteres.',
        ];
        $this->validate($request, $rules, $messages);

        $user = User::withTrashed()->findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $password = $request->input('password');
        if ($password)
            $user->password = bcrypt($password);
        $user->save();

        return redirect('/users')->with('notification', 'Usuario modificado exitosamente.');
    }

    public function deactivate($id)
    {
        $evaluator = User::findOrFail($id);
        $evaluator->delete();

        return back()->with('notification', 'El usuario se ha eliminado correctamente.');
    }

    public function activate($id)
    {
        $userDeleted = User::withTrashed()->findOrFail($id);
        $userDeleted->restore();

        return back()->with('notification', 'El usuario con sus respectivos procesos se ha restaurado correctamente.');
    }
}
