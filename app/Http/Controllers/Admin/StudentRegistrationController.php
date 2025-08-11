<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\StudentRegistrationResource;
use App\Models\Student;
use App\Traits\HasFile;
use Illuminate\Http\Request;

class StudentRegistrationController extends Controller
{
    use HasFile;
    public function index()
    {
        $students = Student::filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);




        return inertia('Admin/StudentRegistrations/Index', [
            'page_setting' => [
                'title' => 'PPDB',
                'subtitle' => 'Menampilkan semua data PPDB yang tersedia di Sekolah ini'
            ],
            'students' => StudentRegistrationResource::collection($students)->additional([
                'meta' => [
                    'has_pages' => $students->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }
}
