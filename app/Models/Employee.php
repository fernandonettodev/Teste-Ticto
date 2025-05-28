<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'document',
        'position',
        'zipcode',
        'birthdate',
        'address',
        'number',
        'neighbourhood',
        'city',
        'state',
        'active',
        'password',
        'admin_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function timeRecords(): HasMany
    {
        return $this->hasMany(TimeRecord::class);
    }

    public function todayRecord()
    {
        return $this->timeRecords()->where('date', today())->first();
    }

    public function getFormattedCpfAttribute()
    {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }

    public function recentRecords($days = 30)
    {
        return $this->timeRecords()
            ->where('date', '>=', now()->subDays($days))
            ->orderBy('date', 'desc')
            ->get();
    }


    public function responsavel()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

}