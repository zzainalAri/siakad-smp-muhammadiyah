<?php

namespace App\Http\Middleware;

use App\Enums\FeeStatus;
use App\Http\Resources\UserSingleResource;
use App\Models\AcademicYear;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $authUser = Auth::user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? new UserSingleResource($request->user()) : null,
            ],
            'flash_message' => fn() => [
                'type' => $request->session()->get('type'),
                'message' => $request->session()->get('message'),
            ],
            'academic_year' => fn() => AcademicYear::query()->where('is_active', true)->first(),
            'checkFee' => fn() => $request->user() && $request->user()->student && activeAcademicYear()
                ? Fee::query()->where('student_id', $authUser->student->id)
                ->where('academic_year_id', activeAcademicYear()->id)
                ->where('semester', $authUser->student->semester)
                ->where('status', FeeStatus::SUCCESS->value)->first()
                : null,
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
