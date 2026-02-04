<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // Check for honeypot field (bot detection)
        if (!empty($request->username)) {
            $this->logSuspiciousActivity($request, 'bot_detection');
            return $this->sendFailedLoginResponse($request);
        }

        // Validate login request
        $this->validateLogin($request);

        // Check rate limiting
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Attempt login
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // Increment login attempts on failure
        $this->incrementLoginAttempts($request);

        // Log failed attempt using your new Activity system
        $this->logFailedLoginAttempt($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Use your new User model method to record login activity
        $user->recordLogin($request->ip(), $request->userAgent());

        // Log successful login
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'time' => Carbon::now()
        ]);

        // Check if user account is active (using your status field)
        if (property_exists($user, 'status') && $user->status !== 'active') {
            auth()->logout();

            // Use toast notification instead of ValidationException
            return redirect()->route('login')->with('toast', [
                'type' => 'danger',
                'title' => 'Account Deactivated',
                'message' => 'Your account has been deactivated. Please contact administrator.'
            ]);
        }

        // Check if user needs to verify email
        if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
            auth()->logout();

            // Use toast notification instead of ValidationException
            return redirect()->route('login')->with('toast', [
                'type' => 'warning',
                'title' => 'Email Verification Required',
                'message' => 'Please verify your email address before logging in.'
            ]);
        }


        return redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function username()
    {
        return 'email';
    }

    public function maxAttempts()
    {
        return 5; // 5 attempts
    }

    public function decayMinutes()
    {
        return 1; // 1 minute lockout
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = RateLimiter::availableIn(
            $this->throttleKey($request)
        );

        // Log lockout event using your Activity system
        $this->logSuspiciousActivity($request, 'account_lockout', [
            'seconds_remaining' => $seconds,
            'attempts' => $this->maxAttempts(),
        ]);

        throw ValidationException::withMessages([
            'email' => [trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ])],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    public function logout(Request $request)
    {
        // Use your new User model method to record logout activity
        if (auth()->check()) {
            $user = auth()->user();
            $user->recordLogout($request->ip(), $request->userAgent());

            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'time' => Carbon::now()
            ]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    protected function loggedOut(Request $request)
    {
        session()->flash('status', 'You have been successfully logged out.');
    }

    /**
     * Log failed login attempt using your Activity system
     */
    private function logFailedLoginAttempt(Request $request)
    {
        // Find user by email to link the attempt
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Use your User model method for failed login
            $user->recordFailedLogin($request->ip(), $request->userAgent(), $request->email);
        } else {
            // Create anonymous failed login attempt
            Activity::create([
                'log_name' => 'auth',
                'description' => 'Failed login attempt - user not found',
                'event' => 'login_failed',
                'properties' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'email_attempted' => $request->email,
                    'user_agent_short' => $this->getShortUserAgent($request->userAgent()),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Log suspicious activity using your Activity system
     */
    private function logSuspiciousActivity(Request $request, string $event, array $additionalProperties = [])
    {
        Activity::create([
            'log_name' => 'security',
            'description' => 'Suspicious activity detected: ' . $event,
            'event' => $event,
            'properties' => array_merge([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'honeypot_value' => $request->username ?? null,
                'user_agent_short' => $this->getShortUserAgent($request->userAgent()),
            ], $additionalProperties),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        Log::warning("Suspicious activity: {$event}", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'event' => $event,
        ]);
    }

    /**
     * Get short version of user agent for display
     */
    private function getShortUserAgent($userAgent)
    {
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';
        return 'Unknown';
    }
}
