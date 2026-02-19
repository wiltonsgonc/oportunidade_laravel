<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->is_admin) {
                abort(403, 'Acesso não autorizado. Apenas administradores podem acessar esta área.');
            }
            return $next($request);
        });
    }

    /**
     * Lista todos os usuários
     */
    public function index(Request $request)
    {
        $query = Usuario::orderBy('is_admin_principal', 'desc')
                       ->orderBy('is_admin', 'desc')
                       ->orderBy('nome', 'asc');

        if ($request->has('busca') && $request->busca) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('usuario', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%");
            });
        }

        if ($request->has('tipo') && $request->tipo) {
            switch ($request->tipo) {
                case 'admin':
                    $query->where('is_admin', true);
                    break;
                case 'ativo':
                    $query->where('ativo', true);
                    break;
                case 'inativo':
                    $query->where('ativo', false);
                    break;
            }
        }

        $usuarios = $query->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostra o formulário de criação
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Salva um novo usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'usuario' => ['required', 'string', 'max:255', 'unique:usuarios,usuario'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email'],
            'senha' => ['required', 'string', 'min:6', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'usuario.required' => 'O campo usuário é obrigatório.',
            'usuario.unique' => 'Este nome de usuário já está em uso.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senba.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'senha.confirmed' => 'A confirmação de senha não confere.',
        ]);

        $usuarioLogado = Auth::user();
        
        $dados = [
            'nome' => $request->nome,
            'usuario' => $request->usuario,
            'email' => $request->email,
            'senha' => $request->senha,
            'ativo' => true,
        ];

        // Apenas admin principal pode criar outros admins
        if ($usuarioLogado->is_admin_principal) {
            $dados['is_admin'] = $request->has('is_admin');
            
            // Se for criar um admin principal (protegido)
            if ($request->has('is_admin_principal')) {
                $dados['is_admin_principal'] = true;
            }
        } else {
            // Admin comum não pode criar admins
            $dados['is_admin'] = false;
            $dados['is_admin_principal'] = false;
        }

        Usuario::create($dados);

        return redirect()->route('admin.usuarios.index')
                        ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Mostra o formulário de edição
     */
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuarioLogado = Auth::user();

        // Proteger admin principal
        if ($usuario->email === 'admin@cimatec.com.br' && !$usuarioLogado->is_admin_principal) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Atualiza um usuário
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuarioLogado = Auth::user();

        // Proteger admin principal
        if ($usuario->email === 'admin@cimatec.com.br' && !$usuarioLogado->is_admin_principal) {
            abort(403, 'Você não tem permissão para editar este usuário.');
        }

        $regras = [
            'nome' => ['required', 'string', 'max:255'],
            'usuario' => ['required', 'string', 'max:255', Rule::unique('usuarios')->ignore($usuario->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->id)],
            'is_admin' => ['nullable', 'boolean'],
            'ativo' => ['nullable', 'boolean'],
        ];

        // Apenas admin principal pode editar role de admin
        if (!$usuarioLogado->is_admin_principal) {
            unset($regras['is_admin']);
        }

        $request->validate($regras);

        $dados = [
            'nome' => $request->nome,
            'usuario' => $request->usuario,
            'email' => $request->email,
        ];

        // Atualizar senha se fornecida
        if ($request->senha) {
            $request->validate(['senha' => ['string', 'min:6', 'confirmed']]);
            $dados['senha'] = $request->senha;
        }

        // Atualizar status de admin (apenas admin principal)
        if ($usuarioLogado->is_admin_principal) {
            $dados['is_admin'] = $request->has('is_admin');
            
            // Proteger admin principal
            if ($usuario->email !== 'admin@cimatec.com.br') {
                $dados['is_admin_principal'] = $request->has('is_admin_principal');
            }
        }

        // Atualizar ativo
        $dados['ativo'] = $request->has('ativo');

        // Impedir que o usuário desative a si mesmo
        if ($usuario->id === $usuarioLogado->id && !$request->has('ativo')) {
            return back()->with('error', 'Você não pode desativar seu próprio usuário.');
        }

        $usuario->update($dados);

        return redirect()->route('admin.usuarios.index')
                        ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Exclui um usuário
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuarioLogado = Auth::user();

        // Impedir auto-exclusão
        if ($usuario->id === $usuarioLogado->id) {
            return back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        // Proteger admin principal
        if ($usuario->email === 'admin@cimatec.com.br') {
            return back()->with('error', 'O usuário administrador principal não pode ser excluído.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
                        ->with('success', 'Usuário excluído com sucesso!');
    }
}
