<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'lunch_out',
        'lunch_in',
        'clock_out',
        'total_hours',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'clock_in' => 'datetime:H:i',
            'lunch_out' => 'datetime:H:i',
            'lunch_in' => 'datetime:H:i',
            'clock_out' => 'datetime:H:i',
            'total_hours' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateTotalHours()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0;
        }

        $clockIn = Carbon::parse($this->clock_in);
        $clockOut = Carbon::parse($this->clock_out);
        
        $totalMinutes = $clockOut->diffInMinutes($clockIn);
        
        // Subtrair tempo de almoço se houver
        if ($this->lunch_out && $this->lunch_in) {
            $lunchOut = Carbon::parse($this->lunch_out);
            $lunchIn = Carbon::parse($this->lunch_in);
            $lunchMinutes = $lunchIn->diffInMinutes($lunchOut);
            $totalMinutes -= $lunchMinutes;
        }
        
        return round($totalMinutes / 60, 2);
    }

    public function updateStatus()
    {
        if ($this->clock_in && $this->clock_out) {
            $this->status = 'complete';
        } elseif ($this->clock_in) {
            $this->status = 'incomplete';
        } else {
            $this->status = 'pending';
        }
        
        $this->total_hours = $this->calculateTotalHours();
        $this->save();
    }
}
