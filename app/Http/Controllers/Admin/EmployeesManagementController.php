<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeesManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $position = $request->input('position', '');
        $city = $request->input('city', '');

        $employees = Employee::with('responsavel')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%')
                      ->orWhere('document', 'like', '%' . $search . '%')
                      ->orWhere('position', 'like', '%' . $search . '%')
                      ->orWhere('city', 'like', '%' . $search . '%');
                });
            })
            ->when($position, fn($q) => $q->where('position', $position))
            ->when($city, fn($q) => $q->where('city', $city))
            ->orderBy('email')
            ->paginate(15)
            ->withQueryString();

        $positions = Employee::distinct()
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->pluck('position')
            ->sort();

        $cities = Employee::distinct()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->pluck('city')
            ->sort();

        return view('admin.employees-management.index', compact('employees', 'positions', 'cities', 'search', 'position', 'city'));
    }

    public function create()
    {
        return view('admin.employees-management.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, null, false);

        $data['document'] = preg_replace('/[^0-9]/', '', $data['document']);
        $data['zipcode'] = preg_replace('/[^0-9]/', '', $data['zipcode']);
        $data['state'] = strtoupper($data['state']);
        $data['admin_id'] = Auth::id();

        // Criptografar a senha antes de salvar
        $data['password'] = bcrypt($data['password']);

        Employee::create($data);

        return redirect()->route('admin.employees.index')->with('message', 'Funcionário cadastrado com sucesso!');
    }



    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.employees-management.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $data = $this->validateData($request, $employee->id, true);

        $data['document'] = preg_replace('/[^0-9]/', '', $data['document']);
        $data['zipcode'] = preg_replace('/[^0-9]/', '', $data['zipcode']);
        $data['state'] = strtoupper($data['state']);

        // Se senha foi preenchida, criptografa, senão remove do array para não atualizar
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['admin_id']); // não altera admin_id na edição

        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('message', 'Funcionário atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('message', 'Funcionário excluído com sucesso!');
    }

    private function validateData(Request $request, $employeeId = null, $isUpdate = false)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'document' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidDocument($value)) {
                        $fail('O document informado não é válido.');
                    }
                },
                Rule::unique('employees', 'document')->ignore($employeeId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'birthdate' => 'required|date|before:today',
            'position' => 'required|string|max:255',
            'zipcode' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'neighbourhood' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
        ];

        // Regra para senha: obrigatória no create, opcional no update
        if ($isUpdate) {
            $rules['password'] = 'nullable|string|min:8'; // senha opcional no update
        } else {
            $rules['password'] = 'required|string|min:8'; // obrigatória no create
        }

        $messages = [
            'document.required' => 'O CPF é obrigatório.',
            'document.size' => 'O CPF deve ter exatamente 11 dígitos.',
            'document.regex' => 'O CPF deve conter apenas números.',
            'document.unique' => 'Este document já está cadastrado.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'birthdate.required' => 'A data de nascimento é obrigatória.',
            'birthdate.before' => 'A data de nascimento deve ser anterior à data atual.',
            'position.required' => 'O cargo é obrigatório.',
            'zipcode.required' => 'O CEP é obrigatório.',
            'zipcode.size' => 'O CEP deve ter exatamente 8 dígitos.',
            'zipcode.regex' => 'O CEP deve conter apenas números.',
            'address.required' => 'O endereço é obrigatório.',
            'number.required' => 'O número é obrigatório.',
            'city.required' => 'A cidade é obrigatória.',
            'state.required' => 'O estado é obrigatório.',
            'state.size' => 'O estado deve ter exatamente 2 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
        ];

        return $request->validate($rules, $messages);
    }



    private function isValidDocument($document)
    {
        $document = preg_replace('/[^0-9]/', '', $document);

        if (strlen($document) != 11) return false;
        if (preg_match('/(\d)\1{10}/', $document)) return false;

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $document[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;
        if ($document[9] != $digit1) return false;

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $document[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return $document[10] == $digit2;
    }

    public function fetchCep(Request $request)
    {
        $cep = preg_replace('/\D/', '', $request->cep);

        if (strlen($cep) != 8) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        try {
            $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");

            $data = json_decode($response, true);

            if (isset($data['erro'])) {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao consultar o CEP'], 500);
        }
    }
}
