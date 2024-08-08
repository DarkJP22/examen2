<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Sucursales;
use App\Models\Attraction;
use App\Models\Comment;
use App\Models\Species;

class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $attractions = Attraction::with('comments')->get()->map(function($attraction) {
            $attraction->average_rating = $attraction->averageRating();
            return $attraction;
        });

        return view('attractions.index', compact('attractions'));
    }

    public function commentsByRating(Request $request)
    {
        $minRating = $request->input('min_rating');
        $maxRating = $request->input('max_rating');

        $comments = Comment::whereBetween('rating', [$minRating, $maxRating])->get();

        return view('comments.index', compact('comments'));
    }

    public function commentCount($id)
    {
        $count = Comment::where('attraction_id', $id)->count();

        return view('comments.count', compact('count'));
    }

    public function attractionsBySpecies($speciesId)
    {
        $attractions = Attraction::whereHas('species', function($query) use ($speciesId) {
            $query->where('id', $speciesId);
        })->get();

        return view('attractions.species', compact('attractions'));
    }

    public function averageRatingBySpecies($speciesId)
    {
        $attractions = Attraction::whereHas('species', function($query) use ($speciesId) {
            $query->where('id', $speciesId);
        })->get();

        $averageRating = $attractions->pluck('averageRating')->avg();

        return view('attractions.species_average_rating', compact('averageRating'));
    }
    public function homepage(): View
    {
        $sucursales = Sucursales::all();
        return view('home', compact('sucursales'));
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function registration(): View
    {
        return view('auth.registration');
    }

    public function AdminHome(): View
    {
        return view('Admin.AdminHome');
    }

    public function pacientes(): View
    {
        return view('Admin.pacientes');
    }

    public function sucursales(): View
    {
        return view('Admin.sucursales');
    }
    public function alergias(): View
    {
        return view('Admin.alergias');
    }
    public function especialidades(): View
    {
        return view('Admin.especialidades');
    }
    public function medicos(): View
    {
        return view('Admin.medicos');
    }
    public function dashboard(): View
    {
        if (Auth::check()) {
            return view('Admin.AdminHome');
        }
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    public function postLogin(Request $request): RedirectResponse
    {
        // Validar la solicitud
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Obtener las credenciales
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Verifica si el checkbox "remember me" está marcado

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials, $remember)) {
            return redirect()->intended('attractions.index')
                ->with('success', 'You have successfully logged in');
        }

        // Si las credenciales no son correctas, lanzar una excepción
        throw ValidationException::withMessages([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function postRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $data = $request->all();
        $user = $this->create($data);

        Auth::login($user);

        return redirect("attractions.index")->withSuccess('Great! You have Successfully loggedin');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }

    /**
     * Función que devuelve la vista con el formulario de recuperar contraseña
     *
     * @return response()
     */
    public function formularioRecuperarContrasenia()
    {
        return view('auth.formulario-recuperar-contrasenia');
    }
    /**
     * Función que recibe el email del usuario y en caso de que exista le envía el email de recuperación de contraseña
     *
     * @return response()
     */
    public function enviarRecuperarContrasenia(Request $request)
    {
        // Validación del email
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Generamos un token único
        $token = Str::random(64);

        // Eliminamos la anterior reseteo de contraseña sin terminar
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        // Creamos la solicitud de reseteo de contraseña
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Enviamos el email de recuperación de contraseña
        Mail::send('email.recuperar-contrasenia', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Recuperar Contraseña');
        });

        return back()->with('message', 'Te hemos enviado un email con las instrucciones para que recuperes tu contraseña');
    }
    /**
     * Función que devuelve la vista con el formulario que actualiza la contraseña
     *
     * @return response()
     */
    public function formularioActualizacion($token)
    {
        return view('auth.formulario-actualizacion', ['token' => $token]);
    }
    /**
     * Función que actualiza la contraseña del usuario
     *
     * @return response()
     */
    public function actualizarContrasenia(Request $request)
    {
        // Validaciones
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        // Obtenemos el registro que contiene la solicitud de reseteo de contraseña
        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        // Si no existe la solicitud devolvemos un error
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Token inválido');
        }

        // Actualizamos la contraseña del usuario
        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);


        // Eliminamos la solicitud
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        // Devolvemos al formulario de login (devolvera un 404 puesto que no existe la ruta)
        return redirect('/login')->with('message', 'Tu contraseña se ha cambiado correctamente');
    }
}
