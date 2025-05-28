<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TimeRecordReportController extends Controller
{
    public function index()
{
    $registros = DB::select("
        SELECT
            tr.id AS registro_id,
            e.name AS funcionario,
            e.position AS cargo,
            TIMESTAMPDIFF(YEAR, e.birthdate, CURDATE()) AS idade,
            a.name AS gestor,
            tr.date AS data,
            tr.clock_in AS entrada,
            tr.lunch_out AS almoco_saida,
            tr.lunch_in AS almoco_volta,
            tr.clock_out AS saida
        FROM time_records tr
        INNER JOIN employees e ON e.id = tr.employee_id
        INNER JOIN admins a ON a.id = e.admin_id
        ORDER BY tr.date DESC
    ");

    return view('admin.reports.time_records', compact('registros'));
}
}

