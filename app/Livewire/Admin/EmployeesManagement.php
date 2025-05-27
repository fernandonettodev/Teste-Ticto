<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmployeesManagement extends Component
{
    use WithPagination;

    // Propriedades de controle
    public $showModal = false;
    public $editingEmployee = null;
    
    // Filtros
    public $search = '';
    public $position = '';
    public $city = '';
    
    // Campos do formulário - dados básicos
    public $document = ''; // CPF
    public $email = '';
    public $birthdate = '';
    public $position_input = '';
    
    // Campos do formulário - endereço
    public $zipcode = '';
    public $street = '';
    public $number = '';
    public $neighbourhood = '';
    public $city_input = '';
    public $state = '';

    protected function rules()
    {
        return [
            'document' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/',
                function ($attribute, $value, $fail) {
                    if (!$this->isValiddocument($value)) {
                        $fail('O document informado não é válido.');
                    }
                },
                Rule::unique('employees', 'document')->ignore($this->editingEmployee?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($this->editingEmployee?->id),
            ],
            'birthdate' => 'required|date|before:today',
            'position_input' => 'required|string|max:255',
            'zipcode' => 'required|string|size:8|regex:/^[0-9]{8}$/',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'neighbourhood' => 'required|string|max:255',
            'city_input' => 'required|string|max:255',
            'state' => 'required|string|size:2',
        ];
    }

    protected $messages = [
        'document.required' => 'O document é obrigatório.',
        'document.size' => 'O document deve ter exatamente 11 dígitos.',
        'document.regex' => 'O document deve conter apenas números.',
        'document.unique' => 'Este document já está cadastrado.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já está cadastrado.',
        'birthdate.required' => 'A data de nascimento é obrigatória.',
        'birthdate.before' => 'A data de nascimento deve ser anterior à data atual.',
        'position_input.required' => 'O position é obrigatório.',
        'zipcode.required' => 'O zipcode é obrigatório.',
        'zipcode.size' => 'O zipcode deve ter exatamente 8 dígitos.',
        'zipcode.regex' => 'O zipcode deve conter apenas números.',
        'street.required' => 'A street é obrigatória.',
        'number.required' => 'O número é obrigatório.',
        'neighbourhood.required' => 'O neighbourhood é obrigatório.',
        'city_input.required' => 'A city é obrigatória.',
        'state.required' => 'O state é obrigatório.',
        'state.size' => 'O state deve ter exatamente 2 caracteres.',
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'position', 'city'])) {
            $this->resetPage();
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $this->editingEmployee = $employee;
        
        $this->document = $employee->document;
        $this->email = $employee->email;
        $this->birthdate = $employee->birthdate->format('Y-m-d');
        $this->position_input = $employee->position;
        $this->zipcode = $employee->zipcode;
        $this->street = $employee->street;
        $this->number = $employee->number;
        $this->neighbourhood = $employee->neighbourhood;
        $this->city_input = $employee->city;
        $this->state = $employee->state;
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        // Remove formatação do document e zipcode antes de salvar
        $cleanDocument = preg_replace('/[^0-9]/', '', $this->document);
        $cleanzipcode = preg_replace('/[^0-9]/', '', $this->zipcode);

        $data = [
            'document' => $cleanDocument,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'position' => $this->position_input,
            'zipcode' => $cleanzipcode,
            'street' => $this->street,
            'number' => $this->number,
            'neighbourhood' => $this->neighbourhood,
            'city' => $this->city_input,
            'state' => strtoupper($this->state),
            'supervisor_id' => Auth::id(), // Admin que cadastrou
        ];

        if ($this->editingEmployee) {
            // Na edição, não altera o responsável original
            unset($data['supervisor_id']);
            $this->editingEmployee->update($data);
            session()->flash('message', 'Funcionário atualizado com sucesso!');
        } else {
            Employee::create($data);
            session()->flash('message', 'Funcionário cadastrado com sucesso!');
        }

        $this->closeModal();
    }

    public function delete($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employee->delete();
        session()->flash('message', 'Funcionário excluído com sucesso!');
    }

    public function buscarzipcode()
    {
        if (strlen($this->zipcode) == 8) {
            try {
                $response = file_get_contents("https://viazipcode.com.br/ws/{$this->zipcode}/json/");
                $data = json_decode($response, true);
                
                if (!isset($data['erro'])) {
                    $this->street = $data['logradouro'] ?? '';
                    $this->neighbourhood = $data['neighbourhood'] ?? '';
                    $this->city_input = $data['localidade'] ?? '';
                    $this->state = $data['uf'] ?? '';
                }
            } catch (\Exception $e) {
                // Se falhar, não faz nada - usuário pode preencher manualmente
            }
        }
    }

    /**
     * Valida se o document é válido usando o algoritmo oficial
     */
    private function isValiddocument($document)
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

    public function formatDocument($document)
    {
        return substr($document, 0, 3) . '.' . substr($document, 3, 3) . '.' . substr($document, 6, 3) . '-' . substr($document, 9, 2);
    }

    public function formatzipcode($zipcode)
    {
        return substr($zipcode, 0, 5) . '-' . substr($zipcode, 5, 3);
    }

    public function updatedDocument($value)
    {
        $this->document = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Remove formatação do zipcode e busca endereço
     */
    public function updatedzipcode($value)
    {
        $this->zipcode = preg_replace('/[^0-9]/', '', $value);
        if (strlen($this->zipcode) == 8) {
            $this->buscarzipcode();
        }
    }

    /**
     * Converte state para maiúscula
     */
    public function updatedstate($value)
    {
        $this->state = strtoupper($value);
    }

    private function resetForm()
    {
        $this->editingEmployee = null;
        $this->document = '';
        $this->email = '';
        $this->birthdate = '';
        $this->position_input = '';
        $this->zipcode = '';
        $this->street = '';
        $this->number = '';
        $this->neighbourhood = '';
        $this->city_input = '';
        $this->state = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $employees = Employee::with('responsavel')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('email', 'like', '%' . $this->search . '%')
                      ->orWhere('document', 'like', '%' . $this->search . '%')
                      ->orWhere('position', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->position, function($query) {
                $query->where('position', $this->position);
            })
            ->when($this->city, function($query) {
                $query->where('city', $this->city);
            })
            ->orderBy('email')
            ->paginate(15);

        $positions = Employee::distinct()
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->pluck('position')
            ->sort();

        $citys = Employee::distinct()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->pluck('city')
            ->sort();

        return view('livewire.admin.employees-management', [
            'employees' => $employees,
            'positions' => $positions,
            'citys' => $citys,
        ]);
    }
}