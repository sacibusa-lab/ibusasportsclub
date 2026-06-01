<?php

namespace App\Registration\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registration\Models\CompetitionRegistration;
use App\Models\Setting;

class AdminRegistrationController extends Controller
{
    /**
     * List all registrations
     */
    public function index(Request $request)
    {
        $query = CompetitionRegistration::with('competition');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('team_name', 'like', "%{$search}%")
                  ->orWhere('registration_code', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $registrations = $query->latest()->paginate(20);

        return view('registration::admin.index', compact('registrations'));
    }

    /**
     * Show registration details
     */
    public function show($id)
    {
        $registration = CompetitionRegistration::with('competition')->findOrFail($id);
        return view('registration::admin.show', compact('registration'));
    }

    /**
     * View configuration settings page
     */
    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('registration::admin.settings', compact('settings'));
    }

    /**
     * Update configuration settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'registration_instructions' => 'required|string',
            'registration_phase1_fee' => 'required|numeric|min:0',
            'registration_phase2_fee' => 'required|numeric|min:0',
            'registration_phase1_active' => 'required|boolean',
            'registration_phase2_active' => 'required|boolean',
        ]);

        $keys = [
            'registration_instructions',
            'registration_phase1_fee',
            'registration_phase2_fee',
            'registration_phase1_active',
            'registration_phase2_active'
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->get($key)]
            );
        }

        return redirect()->route('admin.registrations.settings')
            ->with('success', 'Registration settings updated successfully.');
    }

    /**
     * Delete registration record
     */
    public function destroy($id)
    {
        $registration = CompetitionRegistration::findOrFail($id);
        $registration->delete();

        return redirect()->route('admin.registrations.index')
            ->with('success', 'Registration record deleted successfully.');
    }
}
