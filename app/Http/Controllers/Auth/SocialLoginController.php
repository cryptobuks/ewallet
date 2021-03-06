<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Log;
use Session;
use Validator;

/**
 * Class SocialLoginController
 * @package App\Http\Controllers\Auth
 */
class SocialLoginController extends Controller
{
    /**
     * SocialLoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirect to social provider
     *
     * @param $provider
     * @return mixed
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $authUser = $this->findOrCreate($user, $provider);

            if (is_null($authUser->email)) {
                Session::put('provider_id', $authUser->provider_id);
                return redirect()->route('social.enter_email')->with([
                    'title' => 'Email required',
                    'message' => 'You not share Email addres. Please enter your Email.',
                    'class' => 'info'
                ]);
            }

            Auth::login($authUser, true);

            return redirect()->route('home');
        } catch (\Exception $e) {
            \Log::error('Error during login with ' . $provider. '| ' . $e->getMessage());
            return redirect('/login')->with([
                'title'   => 'Error',
                'message' => 'Error during login with ' . $provider,
                'class'   =>  'danger'
            ]);
        }
    }

    /**
     * @param $user
     * @param $provider
     * @return User|\Illuminate\Database\Eloquent\Model|null|object
     */
    private function findOrCreate($user, $provider)
    {
        $authUser = User::where(['provider_id' => $user->id, 'provider' => $provider])->first();

        if ($authUser) {
            return $authUser;
        }

        if ($user->email) {
            $authUser = User::where('email', $user->email)->first();
            if ($authUser) {
                $authUser->avatar = $this->generateAvatarUrl($user, $provider);
                $authUser->provider = $provider;
                $authUser->provider_id = $user->id;
                $authUser->save();
                return $authUser;
            }
        }

        $newUser = User::create([
            'avatar' => $this->generateAvatarUrl($user, $provider),
            'name' => $user->name,
            'email' => $user->email,
            'password' => Hash::make($user->email),
            'provider' => $provider,
            'provider_id' => $user->id,
        ]);

        event(new UserRegistered($newUser));

        return $newUser;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enterEmail()
    {
        return view('vendor.adminlte.social_enter_email');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enterEmailPost(Request $request)
    {
        $request->merge(['provider_id' => Session::get('provider_id')]);
        $this->validator($request->all())->validate();

        try {
            $user = User::whereProviderId($request->provider_id)->first();
            $user->email = $request->email;
            $user->save();

            Auth::login($user, true);
            Session::remove('provider_id');

            Log::info('Success enter email, after redirect form provider');

            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Error during enter email | ' . $e->getMessage());
            return redirect()->back()->with([
                'title'   => 'Error',
                'message' => 'Error during enter email',
                'class'   =>  'danger'
            ]);
        }
    }

    /**
     * Get a validator for an update email address.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'provider_id' => 'required'
        ]);
    }

    /**
     * @param $user
     * @param $provider
     * @return string
     */
    private function generateAvatarUrl($user, $provider)
    {
        $avatar = asset(env('DEFAULT_AVATAR'));

        switch ($provider) {
            case 'facebook':
                $avatar = 'https://graph.facebook.com/v3.0/' . $user->id . '/picture?width=200&height=200';
                break;
            case 'twitter':
                $avatar = $user->avatar_original;
                break;
        }

        return $avatar;
    }
}
