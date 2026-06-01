<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Competition;
use App\Models\Team;
use App\Models\Player;
use App\Models\CompetitionTeam;
use App\Registration\Models\CompetitionRegistration;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic settings
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

        // Share siteSettings with views for testing since AppServiceProvider boots before migrations/seeding run
        $defaults = [
            'site_name' => 'LOCAL CHAMPIONSHIP',
            'site_short_name' => 'LC',
            'contact_email' => 'admin@tournament.com',
            'primary_color' => '#3d195b',
            'secondary_color' => '#00ff85',
            'accent_color' => '#ff005a',
            'current_season' => date('Y'),
            'footer_text' => 'Local Community Football Championship. Built with Laravel. Not affiliated with the Premier League.',
            'copyright_text' => '© ' . date('Y') . ' Local Community Football Championship.',
        ];
        view()->share('siteSettings', $defaults);
    }

    /**
     * Test public instructions page renders.
     */
    public function test_instructions_page_loads_successfully()
    {
        $response = $this->get(route('registration.instructions'));
        $response->assertStatus(200);
        $response->assertSee('Phase 1: Slot Reservation');
        $response->assertSee('Phase 2: Roster Upload');
    }

    /**
     * Test Phase 1 submission.
     */
    public function test_phase1_submission_creates_registration_record()
    {
        $competition = Competition::create([
            'name' => 'Main Competition',
            'slug' => 'main-competition',
            'type' => 'league',
            'is_active' => true
        ]);

        $response = $this->post(route('registration.phase1.submit'), [
            'competition_id' => $competition->id,
            'team_name' => 'Test FC',
            'president_name' => 'President Doe',
            'contact_name' => 'Representative Smith',
            'contact_email' => 'smith@test.com',
            'contact_phone' => '08012345678',
        ]);

        // Assert record exists in the DB
        $this->assertDatabaseHas('competition_registrations', [
            'team_name' => 'Test FC',
            'contact_name' => 'Representative Smith',
            'contact_email' => 'smith@test.com',
            'status' => 'initiated'
        ]);

        // In simulation mode, it renders the mock_gateway template
        $response->assertStatus(200);
        $response->assertSee('Paystack Checkout (Simulation Mode)');
    }

    /**
     * Test payment callback activates Phase 1 registration.
     */
    public function test_phase1_payment_callback_generates_registration_code()
    {
        $competition = Competition::create([
            'name' => 'Main Competition',
            'slug' => 'main-competition',
            'type' => 'league',
            'is_active' => true
        ]);

        $registration = CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'team_name' => 'Test FC',
            'contact_name' => 'Coach Smith',
            'contact_email' => 'smith@test.com',
            'contact_phone' => '08012345678',
            'status' => 'initiated',
            'phase1_amount' => 5000,
            'phase1_payment_status' => 'pending',
            'phase1_payment_ref' => 'REG-P1-TESTREF123',
            'phase1_data' => []
        ]);

        $response = $this->get(route('registration.callback', ['reference' => 'REG-P1-TESTREF123']));

        $response->assertStatus(200);
        $response->assertSee('Your slot has been reserved successfully!');

        $registration->refresh();
        $this->assertEquals('phase1_paid', $registration->status);
        $this->assertEquals('paid', $registration->phase1_payment_status);
        $this->assertNotNull($registration->registration_code);
        $this->assertStringStartsWith('REG-', $registration->registration_code);
    }

    /**
     * Test Phase 2 verification code redirects.
     */
    public function test_phase2_verification_redirects_to_form()
    {
        $competition = Competition::create([
            'name' => 'Main Competition',
            'slug' => 'main-competition',
            'type' => 'league',
            'is_active' => true
        ]);
        
        $registration = CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'team_name' => 'Test FC',
            'contact_name' => 'Coach Smith',
            'contact_email' => 'smith@test.com',
            'contact_phone' => '08012345678',
            'status' => 'phase1_paid',
            'registration_code' => 'REG-VAL12345',
            'phase1_payment_status' => 'paid',
            'phase1_payment_ref' => 'REG-P1-TESTREF123',
            'phase1_data' => []
        ]);

        $response = $this->post(route('registration.phase2.verify'), [
            'registration_code' => 'REG-VAL12345'
        ]);

        $response->assertRedirect(route('registration.phase2.form', ['code' => 'REG-VAL12345']));
    }

    /**
     * Test Phase 2 submission & full payment creates team/roster.
     */
    public function test_phase2_submission_and_payment_auto_creates_team_and_players()
    {
        $competition = Competition::create([
            'name' => 'Main Competition',
            'slug' => 'main-competition',
            'type' => 'league',
            'is_active' => true
        ]);
        
        $registration = CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'team_name' => 'Raptors FC',
            'contact_name' => 'Manager Ken',
            'contact_email' => 'ken@raptors.com',
            'contact_phone' => '08087654321',
            'status' => 'phase1_paid',
            'registration_code' => 'REG-RAPTORS',
            'phase1_payment_status' => 'paid',
            'phase1_payment_ref' => 'REG-P1-REF',
            'phase1_data' => []
        ]);

        // Submit roster list
        $players = [];
        for ($i = 1; $i <= 11; $i++) {
            $players[] = [
                'name' => "Player $i",
                'shirt_number' => $i,
                'position' => $i === 1 ? 'Goalkeeper' : 'Defender',
                'dob' => '2000-01-01'
            ];
        }

        $response = $this->post(route('registration.phase2.submit', ['code' => 'REG-RAPTORS']), [
            'primary_color' => '#123456',
            'jersey_home' => 'Green stripes',
            'jersey_away' => 'White stripes',
            'players' => $players
        ]);

        $response->assertStatus(200);
        $response->assertSee('Paystack Checkout (Simulation Mode)');

        $registration->refresh();
        $this->assertNotNull($registration->phase2_payment_ref);

        // Run the callback to simulate successful payment confirmation
        $callbackResponse = $this->get(route('registration.callback', ['reference' => $registration->phase2_payment_ref]));
        $callbackResponse->assertStatus(200);
        $callbackResponse->assertSee('Full tournament registration is complete');

        // Check if Team and players are synced into the main database!
        $this->assertDatabaseHas('teams', [
            'name' => 'Raptors FC',
            'manager' => 'Manager Ken',
            'primary_color' => '#123456'
        ]);

        $team = Team::where('name', 'Raptors FC')->first();
        $this->assertNotNull($team);

        // Check if team is mapped to the active competition
        $this->assertDatabaseHas('competition_teams', [
            'competition_id' => $competition->id,
            'team_id' => $team->id
        ]);

        // Check that players were inserted in the players table
        $this->assertDatabaseHas('players', [
            'team_id' => $team->id,
            'name' => 'Player 1',
            'position' => 'GK',
            'shirt_number' => 1
        ]);
        $this->assertDatabaseHas('players', [
            'team_id' => $team->id,
            'name' => 'Player 11',
            'position' => 'DEF',
            'shirt_number' => 11
        ]);

        $this->assertCount(11, $team->players);
    }

    /**
     * Test payment callback triggers Termii SMS when configured.
     */
    public function test_phase1_payment_callback_sends_sms_via_termii_when_configured()
    {
        // Mock Http facade
        \Illuminate\Support\Facades\Http::fake([
            'https://api.ng.termii.com/api/sms/send' => \Illuminate\Support\Facades\Http::response([
                'code' => 'ok',
                'message_id' => '1234567890',
                'status' => 'success'
            ], 200)
        ]);

        // Configure Termii settings
        \App\Models\Setting::updateOrCreate(['key' => 'termii_api_key'], ['value' => 'test-api-key']);
        \App\Models\Setting::updateOrCreate(['key' => 'termii_sender_id'], ['value' => 'TEST-ID']);
        \App\Models\Setting::updateOrCreate(['key' => 'termii_channel'], ['value' => 'generic']);

        $competition = Competition::create([
            'name' => 'Main Competition',
            'slug' => 'main-competition',
            'type' => 'league',
            'is_active' => true
        ]);

        $registration = CompetitionRegistration::create([
            'competition_id' => $competition->id,
            'team_name' => 'Test FC',
            'contact_name' => 'Representative Smith',
            'contact_email' => 'smith@test.com',
            'contact_phone' => '08012345678', // Nigerian number
            'status' => 'initiated',
            'phase1_amount' => 5000,
            'phase1_payment_status' => 'pending',
            'phase1_payment_ref' => 'REG-P1-SMSREF',
            'phase1_data' => []
        ]);

        $response = $this->get(route('registration.callback', ['reference' => 'REG-P1-SMSREF']));

        $response->assertStatus(200);

        // Assert Termii API was called with correct payload
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->url() === 'https://api.ng.termii.com/api/sms/send' &&
                $request['api_key'] === 'test-api-key' &&
                $request['to'] === '2348012345678' && // formatted number
                $request['from'] === 'TEST-ID' &&
                $request['channel'] === 'generic' &&
                str_contains($request['sms'], 'Phase 2 registration code is:');
        });
    }
}
