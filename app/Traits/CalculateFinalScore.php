<?php

namespace App\Traits;

use App\Models\Attendance;
use App\Models\Grade;

trait CalculateFinalScore
{
    public function getAttendanceCount($studentId, $courseId, $classroomId)
    {
        return Attendance::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('classroom_id', $classroomId)
            ->whereBetween('section', [1, 12])
            ->active()
            ->count();
    }

    public function getGradeCount($studentId,  $courseId, $classroomId, $category)
    {
        $grade = Grade::query()
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('classroom_id', $classroomId)
            ->where('category', $category);

        if ($category === 'tugas') {
            $grade->whereBetween('section', [1, 10]);
        } elseif (in_array($category, ['uts', 'uas'])) {
            $grade->whereNull('section');
        }

        return $grade->sum('grade');
    }

    public function calculateAttendancePercentage($attendanceCount, $totalSession = 12)
    {
        return round(($attendanceCount / $totalSession) * 10, 2);
    }

    public function calculateTaskPercentage($taskCount, $totalTasks = 10)
    {
        return round(($taskCount / $totalTasks) * 0.2, 2);
    }

    public function calculateUtsPercentage($utsCount)
    {
        return round($utsCount * 0.3, 2);
    }

    public function calculateUasPercentage($uasCount)
    {
        return round($uasCount * 0.4, 2);
    }

    public function calculateFinalScore($attendancePercentage, $taskPercentage, $utsPercentage, $uasPercentage)
    {
        return round($attendancePercentage + $taskPercentage + $utsPercentage + $uasPercentage, 2);
    }

    public function getWeight($letterGrade)
    {
        $gradePoints = [
            'A' => 4.00,
            'A-' => 3.70,
            'B+' => 3.30,
            'B-' => 2.70,
            'C+' => 2.30,
            'C' => 2.00,
            'C-' => 1.70,
            'D' => 1.00,
            'E' => 0.00

        ];
        return $gradePoints[$letterGrade] ?? 0.00;
    }
}
