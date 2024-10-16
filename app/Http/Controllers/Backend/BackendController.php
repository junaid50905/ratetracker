<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ExchangeRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use getID3;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class BackendController extends Controller
{
    /**
     * dashboard
     */
    public function dashboard()
    {
        return view('ui.backend.pages.dashboard');
    }
    /**
     * exchangeRate
     */
    public function exchangeRate()
    {
        $currencies = DB::table('currencies')->orderBy('name', 'asc')->get();
        $exchanges = ExchangeRate::orderBy('id', 'desc')->get();
        $exchange_rate_duration = Content::where('type', 'currency')->first();
        return view('ui.backend.pages.exchang-rate', compact('currencies', 'exchanges', 'exchange_rate_duration'));
    }

    /**
     * exchangeRateStore
     */
    public function exchangeRateStore(Request $request)
    {
        $validated = $request->validate([
            'currency_id' => 'required|unique:exchange_rates,currency_id',
            'buying_rate' => 'required|numeric',
            'selling_rate' => 'required|numeric',
        ]);
        try {
            // Store the exchange rate
            $allInputs = $request->except('_token');
            ExchangeRate::create($allInputs);

            // Redirect back with success message
            return redirect()->back()->withSuccess(__('New Exchange rate has been created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to create exchange rate. Please try again.'));
        }
    }

    /**
     * exchangeRateDestroy
     */
    public function exchangeRateDestroy($exchangeId)
    {
        ExchangeRate::destroy($exchangeId);
        return redirect()->back()->withSuccess(__('An exchange rate has been deleted.'));
    }

    /**
     * exchangeRateEdit
     */
    public function exchangeRateEdit($exchangeId)
    {
        $editExchange = ExchangeRate::findOrFail($exchangeId);
        $currencies = DB::table('currencies')->orderBy('name', 'asc')->get();
        $exchanges = ExchangeRate::orderBy('id', 'desc')->get();
        return view('ui.backend.pages.exchang-rate', compact('editExchange', 'currencies', 'exchanges'));
    }

    /**
     * exchangeRateUpdate
     */
    public function exchangeRateUpdate(Request $request, $exchangeId)
    {
        $validated = $request->validate([
            'buying_rate' => 'required|numeric',
            'selling_rate' => 'required|numeric',
        ]);

        try {
            // Update the exchange rate
            $exchangeRate = ExchangeRate::findOrFail($exchangeId);
            $exchangeRate->update($request->except('_token'));
            return redirect()->route('exchange_rate')->withSuccess(__('Exchange rate has been updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(__('Failed to update exchange rate. Please try again.'));
        }
    }
    /**
     * content
     */
    public function content()
    {
        $contents = Content::orderBy('id', 'desc')->get();
        return view('ui.backend.pages.content', compact('contents'));
    }
    /**
     * createNewContent
     */
    public function createNewContent()
    {
        return view('ui.backend.pages.create_new_content');
    }
    /**
     * contentStore
     */
    // public function contentStore(Request $request)
    // {
    //     if ($request->type == 'image') { // image
    //         $request->validate([
    //             'image' => 'required',
    //             'imageDuration' => 'required'
    //         ]);

    //         if ($request->hasFile('image')) {
    //             $imageDuration = $request->imageDuration * 1000;

    //             $image = $request->file('image');
    //             $imageName = time() . '_' . $image->getClientOriginalName();

    //             $image->move(public_path('ui/uploads/image'), $imageName);

    //             Content::create([
    //                 'type' => 'image',
    //                 'image' => $imageName,
    //                 'duration' => $imageDuration,
    //             ]);
    //             return redirect()->route('content')->withSuccess(__('Added new image'));
    //         }
    //     } elseif ($request->type == 'video') { // video
    //         $request->validate([
    //             'video' => 'required',
    //         ]);

    //         if ($request->hasFile('video')) {
    //             $video = $request->file('video');
    //             $videoName = time() . '_' . $video->getClientOriginalName();

    //             $video->move(public_path('ui/uploads/video'), $videoName);

    //             $getID3 = new getID3;
    //             $video_file = $getID3->analyze('ui/uploads/video/'.$videoName);
    //             $duration = $video_file['playtime_seconds'] * 1000;

    //             Content::create([
    //                 'type' => 'video',
    //                 'video' => $videoName,
    //                 'duration' => $duration,
    //             ]);

    //             return redirect()->route('content')->withSuccess(__('Added new video content'));
    //         }
    //     } elseif ($request->type == 'currency') { // currency
    //         $request->validate([
    //             'currencyDuration' => 'required'
    //         ]);
    //         $currencyDuration = $request->currencyDuration * 1000;

    //         Content::create([
    //             'type' => 'currency',
    //             'duration' => $currencyDuration,
    //         ]);
    //         return redirect()->route('content')->withSuccess(__('Currency content duration has been set'));
    //     }

    //     return redirect()->back()->with('success', __('Content uploaded successfully.'));
    // }
    public function contentStore(Request $request)
    {
        if ($request->type == 'image') { // image
            $request->validate([
                'image' => 'required',
                'imageDuration' => 'required'
            ]);

            if ($request->hasFile('image')) {
                $imageDuration = $request->imageDuration * 1000;

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('ui/uploads/image'), $imageName);

                Content::create([
                    'type' => 'image',
                    'image' => $imageName,
                    'duration' => $imageDuration,
                ]);
                return redirect()->route('content')->withSuccess(__('Added new image'));
            }
        } elseif ($request->type == 'video') { // video

            $request->validate([
                'video' => 'required',
            ]);


            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoName = time() . '_' . $video->getClientOriginalName();


                $video->move(public_path('ui/uploads/video'), $videoName);

                $getID3 = new getID3;
                // $video_file = $getID3->analyze('ui/uploads/video/' . $videoName);
                $video_file = $getID3->analyze(public_path('ui/uploads/video/' . $videoName));



                // Check if 'playtime_seconds' exists and calculate duration, otherwise fallback
                $duration = isset($video_file['playtime_seconds']) ? $video_file['playtime_seconds'] * 1000 : 0;


                if ($duration > 0) {
                    Content::create([
                        'type' => 'video',
                        'video' => $videoName,
                        'duration' => $duration,
                    ]);

                    return redirect()->route('content')->withSuccess(__('Added new video content'));
                } else {
                    return redirect()->route('content')->withErrors(__('Unable to detect video duration. Please check the file.'));
                }
            }
        } elseif ($request->type == 'currency') { // currency
            $request->validate([
                'currencyDuration' => 'required'
            ]);
            $currencyDuration = $request->currencyDuration * 1000;

            Content::create([
                'type' => 'currency',
                'duration' => $currencyDuration,
            ]);
            return redirect()->route('content')->withSuccess(__('Currency content duration has been set'));
        }

        return redirect()->back()->with('success', __('Content uploaded successfully.'));
    }


    /**
     * contentDestroy
     */
    public function contentDestroy($contentId)
    {
        Content::destroy($contentId);
        return redirect()->back()->withSuccess(__('Content has been deleted'));
    }
    /**
     * contentUpdateDuration
     */
    public function contentUpdateDuration(Request $request, $contentId)
    {
        $request->validate([
            'duration' => 'required'
        ]);

        $duration = $request->duration * 1000;

        Content::where('id', $contentId)->update([
            'duration' => $duration
        ]);
        return redirect()->back()->withSuccess(__('Content duration has been updated'));
    }
    /**
     * users
     */
    public function users()
    {
        $users = User::orderBy('id', 'desc')->get();
        return view('ui.backend.pages.users', compact('users'));

    }
    /**
     * userCreate
     */
    public function userCreate()
    {
        return view('ui.backend.pages.user_create');
    }
    /**
     * userStore
     */
    public function userStore(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required'
        ]);

        // Create a new user and hash the password
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('users')->withSuccess(__('Added new user'));
    }

    /**
     * login
     */
    public function login()
    {
        if (Auth::user()) {
            return redirect()->back();
        }
        return view('ui.backend.pages.login');
    }
    /**
     * loginStore
     */
    public function loginStore(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if credentials are correct
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session
            return redirect()->intended('admin/dashboard');
        }

        // If login fails
        // return back()->withErrors([
        //     'email' => 'Invalid credentials.',
        // ])->onlyInput('email');
        return redirect()->back()->withErrors(__('Invalid crediantials'));


    }
    /**
     * logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }
}
