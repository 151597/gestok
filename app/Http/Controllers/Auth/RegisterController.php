<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $id = $data['id'];
        if(!empty($id)){
            $usuario = User::findOrFail($id);
        }elseif(Auth::user()->funcao == 1){
            $usuario = new User;
        }else{
            abort(403, 'Unauthorized action.');
        }

        if(!empty($data['password'])){
            $usuario->password = Hash::make($data['password']); 
        }

        $usuario->name = $data['name'];
        $usuario->email = $data['email'];
        if(Auth::user()->funcao == 1){
            $usuario->funcao = $data['funcao'];
        }
        
        
        $usuario->save();

    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        ////$this->validator($request->all())->validate();
        $id = $request->input('id');
        $usuario = User::find($id);
        $user = Auth::user();

        if(is_null($id) || empty($id)){

            $request->validate([
                //'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed'],
            ]);
            
        }elseif($user->id == $id && $usuario->email == $request->input('email')){
            
            $request->validate([
                //'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['string', 'confirmed'],
            ]);

            
        }elseif($user->id != $id && $usuario->email != $request->input('email')){

            $request->validate([
                //'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        
        }elseif($user->id != $id && $usuario->email == $request->input('email')){
            $request->validate([
                //'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }


        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)/*?: redirect($this->redirectPath())*/;
    }

    public function index(){
        $usuarios = User::select('id', 'name', 'email', 'funcao')->get();

        return view('usuarios', ['usuarios' => $usuarios]);
    }

    public function loadRegister(){

        $loadRegister = DB::table('users')
        ->select('id', 'email', 'name', DB::raw(
            "(CASE
                WHEN funcao = 1 THEN 'Administrador'
                WHEN funcao = 2 THEN 'Suporte T.I.'
                WHEN funcao = 3 THEN 'Compras'
            END) as funcao"
        ), 'funcao as id_funcao')
        ->whereNull('deleted_at');

        if(Auth::user()->funcao != 1){
            $loadRegister = $loadRegister->where('id', Auth::user()->id);
        }

        $loadRegister = $loadRegister->get();

        return json_encode(['data' => $loadRegister]);
    }

    public function deleteRegister(Request $request){
        $idCheck = $request->input('data');
        $deleteRegister = User::whereIn('id', $idCheck);
        $deleteRegister->delete();
    }
}
