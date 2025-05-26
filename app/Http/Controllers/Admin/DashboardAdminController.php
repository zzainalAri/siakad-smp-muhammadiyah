<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function __invoke()

    {
        return inertia('Admin/Dashboard', [
            'page_setting' => [
                'title' => 'Dashboard',
                'subtitle' => 'Menampilkan semua statistik pada platform ini',
            ],
            'count' => [
                'faculties' => Faculty::count(),
                'departements' => Departement::count(),
                'classrooms' => Classroom::count(),
                'courses' => Course::count(),
            ]
        ]);
    }
}
