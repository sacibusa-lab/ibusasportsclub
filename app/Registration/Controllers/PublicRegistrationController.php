<?php

namespace App\Registration\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Registration\Models\CompetitionRegistration;
use App\Models\Competition;
use App\Models\Team;
use App\Models\CompetitionTeam;
use App\Models\Player;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublicRegistrationController extends Controller
{
    /**
     * Helper to get Paystack Secret Key
     */
    protected function getPaystackSecretKey()
    {
        $dbKey = Setting::where('key', 'paystack_secret_key')->value('value');
        return !empty($dbKey) ? $dbKey : env('PAYSTACK_SECRET_KEY');
    }

    /**
     * Helper to get Paystack Public Key
     */
    protected function getPaystackPublicKey()
    {
        $dbKey = Setting::where('key', 'paystack_public_key')->value('value');
        return !empty($dbKey) ? $dbKey : env('PAYSTACK_PUBLIC_KEY');
    }

    /**
     * Check if payment simulation is active
     */
    protected function isSimulationActive()
    {
        $key = $this->getPaystackSecretKey();
        return empty($key) || str_contains($key, 'xxxxxx') || str_contains($key, 'placeholder');
    }

    /**
     * Show registration instructions
     */
    public function instructions()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('registration::instructions', compact('settings'));
    }

    /**
     * Show Phase 1 (Participation) Form
     */
    public function showPhase1()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $isActive = ($settings['registration_phase1_active'] ?? '1') === '1';

        if (!$isActive) {
            return redirect()->route('registration.instructions')
                ->with('error', 'Phase 1 registration is currently closed.');
        }

        $competitions = Competition::where('is_active', true)->get();
        return view('registration::phase1', compact('competitions', 'settings'));
    }

    /**
     * Submit Phase 1 Form and initialize payment
     */
    public function submitPhase1(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $isActive = ($settings['registration_phase1_active'] ?? '1') === '1';

        if (!$isActive) {
            return redirect()->route('registration.instructions')
                ->with('error', 'Phase 1 registration is currently closed.');
        }

        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'team_name' => 'required|string|max:100',
            'president_name' => 'required|string|max:100',
            'contact_name' => 'required|string|max:100',
            'contact_email' => 'required|email|max:100',
            'contact_phone' => 'required|string|max:20',
        ]);

        $fee = floatval($settings['registration_phase1_fee'] ?? 5000);
        $reference = 'REG-P1-' . Str::upper(Str::random(10));

        // Create the registration record
        $registration = CompetitionRegistration::create([
            'competition_id' => $request->competition_id,
            'team_name' => $request->team_name,
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'status' => 'initiated',
            'phase1_amount' => $fee,
            'phase1_payment_status' => 'pending',
            'phase1_payment_ref' => $reference,
            'phase1_data' => [
                'president_name' => $request->president_name,
                'submitted_at' => now()->toDateTimeString(),
            ]
        ]);

        if ($fee <= 0) {
            // Free phase 1 registration
            $code = 'REG-' . Str::upper(Str::random(8));
            $registration->update([
                'phase1_payment_status' => 'paid',
                'phase1_paid_at' => now(),
                'status' => 'phase1_paid',
                'registration_code' => $code
            ]);

            $this->sendRegistrationSms($registration, $code);

            return redirect()->route('registration.callback', ['reference' => $reference]);
        }

        // Initialize Paystack payment
        if ($this->isSimulationActive()) {
            // Simulated gateway for testing
            return view('registration::mock_gateway', [
                'reference' => $reference,
                'email' => $request->contact_email,
                'amount' => $fee,
                'purpose' => 'Phase 1 - Slot Reservation'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getPaystackSecretKey(),
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $request->contact_email,
                'amount' => intval($fee * 100), // in kobo
                'callback_url' => route('registration.callback'),
                'reference' => $reference,
                'metadata' => [
                    'registration_id' => $registration->id,
                    'phase' => 1
                ]
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                return redirect($result['data']['authorization_url']);
            }

            Log::error('Paystack initialization failed', ['response' => $result]);
            return back()->withInput()->with('error', 'Paystack payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Paystack connection exception', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Could not connect to payment gateway. Please try again.');
        }
    }

    /**
     * Show Phase 2 Code Verification page
     */
    public function showPhase2Access()
    {
        return view('registration::phase2_access');
    }

    /**
     * Verify Phase 2 Registration Code
     */
    public function verifyPhase2Access(Request $request)
    {
        $request->validate([
            'registration_code' => 'required|string',
        ]);

        $reg = CompetitionRegistration::where('registration_code', trim($request->registration_code))->first();

        if (!$reg) {
            return back()->withInput()->with('error', 'Invalid Registration Code. Please verify and try again.');
        }

        if ($reg->status === 'initiated') {
            return back()->withInput()->with('error', 'This team has not completed Phase 1 payment yet.');
        }

        if ($reg->status === 'completed') {
            return redirect()->route('registration.instructions')
                ->with('success', 'Your registration is already completed! Welcome to the tournament.');
        }

        return redirect()->route('registration.phase2.form', ['code' => $reg->registration_code]);
    }

    /**
     * Show Phase 2 Form
     */
    public function showPhase2($code)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $isActive = ($settings['registration_phase2_active'] ?? '1') === '1';

        if (!$isActive) {
            return redirect()->route('registration.instructions')
                ->with('error', 'Phase 2 registration is currently closed.');
        }

        $registration = CompetitionRegistration::where('registration_code', $code)->firstOrFail();

        if ($registration->status !== 'phase1_paid') {
            return redirect()->route('registration.instructions')
                ->with('error', 'This registration code is not eligible for Phase 2.');
        }

        return view('registration::phase2', compact('registration', 'settings'));
    }

    /**
     * Submit Phase 2 and initialize payment
     */
    public function submitPhase2(Request $request, $code)
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $isActive = ($settings['registration_phase2_active'] ?? '1') === '1';

        if (!$isActive) {
            return redirect()->route('registration.instructions')
                ->with('error', 'Phase 2 registration is currently closed.');
        }

        $registration = CompetitionRegistration::where('registration_code', $code)->firstOrFail();

        if ($registration->status !== 'phase1_paid') {
            return redirect()->route('registration.instructions')
                ->with('error', 'Invalid registration state.');
        }

        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'jersey_home' => 'required|string|max:50',
            'jersey_away' => 'required|string|max:50',
            'players' => 'required|array|min:11',
            'players.*.name' => 'required|string|max:100',
            'players.*.shirt_number' => 'required|integer|min:1|max:99',
            'players.*.position' => 'required|string|in:Goalkeeper,Defender,Midfielder,Forward',
            'players.*.dob' => 'required|date|before:today',
        ]);

        $fee = floatval($settings['registration_phase2_fee'] ?? 15000);
        $reference = 'REG-P2-' . Str::upper(Str::random(10));

        // Save layout parameters and players list inside the record
        $registration->update([
            'phase2_amount' => $fee,
            'phase2_payment_status' => 'pending',
            'phase2_payment_ref' => $reference,
            'phase2_data' => [
                'primary_color' => $request->primary_color,
                'jersey_home' => $request->jersey_home,
                'jersey_away' => $request->jersey_away,
                'players' => $request->players,
                'submitted_at' => now()->toDateTimeString(),
            ]
        ]);

        if ($fee <= 0) {
            // Free phase 2
            $registration->update([
                'phase2_payment_status' => 'paid',
                'phase2_paid_at' => now(),
                'status' => 'completed'
            ]);

            // Auto-create team and players in main DB
            $this->createTeamFromRegistration($registration);

            return redirect()->route('registration.callback', ['reference' => $reference]);
        }

        // Initialize Paystack payment
        if ($this->isSimulationActive()) {
            return view('registration::mock_gateway', [
                'reference' => $reference,
                'email' => $registration->contact_email,
                'amount' => $fee,
                'purpose' => 'Phase 2 - Full Tournament Registration'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getPaystackSecretKey(),
                'Content-Type' => 'application/json',
            ])->post('https://api.paystack.co/transaction/initialize', [
                'email' => $registration->contact_email,
                'amount' => intval($fee * 100), // in kobo
                'callback_url' => route('registration.callback'),
                'reference' => $reference,
                'metadata' => [
                    'registration_id' => $registration->id,
                    'phase' => 2
                ]
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                return redirect($result['data']['authorization_url']);
            }

            Log::error('Paystack initialization failed', ['response' => $result]);
            return back()->withInput()->with('error', 'Paystack payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Paystack connection exception', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Could not connect to payment gateway. Please try again.');
        }
    }

    /**
     * Paystack Redirect Callback
     */
    public function paymentCallback(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect()->route('registration.instructions')->with('error', 'Transaction reference missing.');
        }

        // Find the registration by reference
        $registration = CompetitionRegistration::where('phase1_payment_ref', $reference)
            ->orWhere('phase2_payment_ref', $reference)
            ->first();

        if (!$registration) {
            return redirect()->route('registration.instructions')->with('error', 'Registration record not found.');
        }

        $isPhase1 = ($registration->phase1_payment_ref === $reference);

        // Verify transaction
        $paymentSuccess = false;

        if ($this->isSimulationActive() && str_starts_with($reference, 'REG-')) {
            // Simulator bypass
            $paymentSuccess = true;
        } else {
            // Real Paystack verification
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->getPaystackSecretKey(),
                ])->get('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));

                $result = $response->json();

                if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                    if ($result['data']['status'] === 'success') {
                        $paymentSuccess = true;
                    }
                } else {
                    Log::error('Paystack verification returned failure', ['response' => $result]);
                }
            } catch (\Exception $e) {
                Log::error('Paystack verification connection error', ['error' => $e->getMessage()]);
            }
        }

        if (!$paymentSuccess) {
            return redirect()->route('registration.instructions')->with('error', 'Payment verification failed. If your account was debited, please contact administration.');
        }

        // Process successful payment
        if ($isPhase1) {
            if ($registration->phase1_payment_status !== 'paid') {
                $code = 'REG-' . Str::upper(Str::random(8));
                $registration->update([
                    'phase1_payment_status' => 'paid',
                    'phase1_paid_at' => now(),
                    'status' => 'phase1_paid',
                    'registration_code' => $code
                ]);

                $this->sendRegistrationSms($registration, $code);
            }

            return view('registration::success', [
                'registration' => $registration,
                'phase' => 1,
                'message' => 'Your slot has been reserved successfully! Please save the registration code below.'
            ]);
        } else {
            if ($registration->phase2_payment_status !== 'paid') {
                $registration->update([
                    'phase2_payment_status' => 'paid',
                    'phase2_paid_at' => now(),
                    'status' => 'completed'
                ]);

                // Create resources inside main database automatically
                $this->createTeamFromRegistration($registration);
            }

            return view('registration::success', [
                'registration' => $registration,
                'phase' => 2,
                'message' => 'Congratulations! Full tournament registration is complete. Your roster is locked.'
            ]);
        }
    }

    /**
     * Helper to auto-create Team, Players, and Competition relationships
     */
    protected function createTeamFromRegistration(CompetitionRegistration $registration)
    {
        try {
            // 1. Find or create a default group for this team (since group_id is a non-nullable foreign key in teams table)
            $group = \App\Models\Group::where('competition_id', $registration->competition_id)->first();
            if (!$group) {
                $group = \App\Models\Group::where('name', 'Friendly')->first();
            }
            if (!$group) {
                $group = \App\Models\Group::create([
                    'name' => 'Friendly',
                    'competition_id' => $registration->competition_id
                ]);
            }

            // 2. Create the Team in 'teams' table
            // Check if team name already exists
            $team = Team::where('name', $registration->team_name)->first();

            if (!$team) {
                $p2Data = $registration->phase2_data;
                $team = Team::create([
                    'name' => $registration->team_name,
                    'short_name' => Str::upper(substr(preg_replace('/[^A-Za-z0-9]/', '', $registration->team_name), 0, 3)),
                    'manager' => $registration->contact_name,
                    'primary_color' => $p2Data['primary_color'] ?? '#3d195b',
                    'logo_url' => null, // empty logo by default
                    'group_id' => $group->id,
                    'played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'points' => 0
                ]);
            }

            // 2. Associate with Competition
            $compTeamExists = CompetitionTeam::where([
                'competition_id' => $registration->competition_id,
                'team_id' => $team->id
            ])->exists();

            if (!$compTeamExists) {
                CompetitionTeam::create([
                    'competition_id' => $registration->competition_id,
                    'team_id' => $team->id,
                    'played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'points' => 0
                ]);
            }

            // 3. Create Players list
            $players = $registration->phase2_data['players'] ?? [];
            $positionMap = [
                'Goalkeeper' => 'GK',
                'Defender' => 'DEF',
                'Midfielder' => 'MID',
                'Forward' => 'FWD',
            ];
            foreach ($players as $pData) {
                // Check if shirt number or player is already added for this team
                $playerExists = Player::where('team_id', $team->id)
                    ->where('name', $pData['name'])
                    ->exists();

                if (!$playerExists) {
                    Player::create([
                        'name' => $pData['name'],
                        'team_id' => $team->id,
                        'position' => $positionMap[$pData['position']] ?? 'GK',
                        'shirt_number' => $pData['shirt_number'],
                        'image_url' => null,
                        'full_image_url' => null
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Auto-creation of team from registration failed', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send SMS containing registration code to representative via Termii API
     */
    protected function sendRegistrationSms(CompetitionRegistration $registration, string $code)
    {
        $apiKey = Setting::where('key', 'termii_api_key')->value('value');
        $senderId = Setting::where('key', 'termii_sender_id')->value('value');
        $channel = Setting::where('key', 'termii_channel')->value('value') ?: 'dnd';

        if (empty($apiKey) || empty($senderId)) {
            Log::info("Termii SMS skipped: API key or Sender ID not configured.");
            return false;
        }

        $phone = $this->formatPhoneNumber($registration->contact_phone);
        $siteName = Setting::where('key', 'site_name')->value('value') ?: 'LC Tournament';
        $message = "Your participation reservation is successful for {$registration->team_name} in {$siteName}. Your Phase 2 registration code is: {$code}";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.ng.termii.com/api/sms/send', [
                'api_key' => $apiKey,
                'to' => $phone,
                'from' => $senderId,
                'sms' => $message,
                'type' => 'plain',
                'channel' => $channel,
            ]);

            $result = $response->json();
            Log::info('Termii SMS API response', ['response' => $result]);

            return $response->successful() && isset($result['code']) && $result['code'] === 'ok';
        } catch (\Exception $e) {
            Log::error('Termii SMS API connection error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Format phone number to international standard for Termii (e.g. 23480...)
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters
        $digits = preg_replace('/\D/', '', $phone);

        // If it starts with 0 and is 11 digits (typical Nigerian format), prefix with 234
        if (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = '234' . substr($digits, 1);
        }

        return $digits;
    }
}
