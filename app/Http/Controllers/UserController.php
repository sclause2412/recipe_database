<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Mail\AdminMail;
use App\Models\User;
use App\Notifications\UserList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function locked()
    {
        return view('auth.locked');
    }

    public function unlock(Request $request): RedirectResponse
    {

        $request->validate([
            'reason' => 'required|string',
            'file' => 'required|file|mimes:jpg,png,pdf'
        ]);

        $reason = $request->get('reason');
        $file = $request->file('file');

        $user = auth()?->user();

        if (!is_null($user)) {
            $eol = "\n";
            $mail = new AdminMail('User locked complain');
            $mail->content = 'Following user complains about a lock:' . $eol;
            $mail->content .= '* Username: ' . $user->name . $eol;
            $mail->content .= '* Full name: ' . $user->firstname . ' ' . $user->lastname . $eol;
            $mail->content .= '* Email: ' . $user->email . $eol . $eol;
            $mail->content .= 'This is the reason he/she gives for his/her complain:' . $eol . $eol;
            $mail->content .= '    ' . str_replace("\n", "\n    ", $reason) . $eol;
            $mail->url = route('login');
            if ($file->isValid()) {
                $mail->addFile($file->path(), 'idcard.' . $file->guessExtension());
            }

            foreach (User::where('admin', true)->where('active', true)->whereNotNull('email')->get() as $user) {
                Mail::to($user->email)->send($mail);
            }
        }

        return back()->with('status', __('Your request was sent.'));
    }

    public function policy()
    {
        return view('auth.policy');
    }

    public function policy_accept(Request $request)
    {
        $request->validate([
            'terms' => ['accepted', 'required'],
        ]);

        $user = Auth::user();
        if (is_null($user)) {
            return redirect()->route('welcome');
        }

        $user->policy_accepted = true;
        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->route('welcome');
    }


    public function username()
    {
        return view('auth.forgot-username');
    }

    public function usernamesend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $usernames = User::where('email', $request->email)->pluck('name')->toArray();

        if (count($usernames) > 0) {
            Notification::route('mail', $request->email)->notify(new UserList($usernames));
        }

        return back()->with('status', __('We sent a email with the usernames we know.'));
    }

    public function email()
    {
        return view('auth.forgot-email');
    }

    public function emailsend(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required|string',
            'emailcurrent' => 'required|email',
            'file' => 'required|file|mimes:jpg,png,pdf'
        ]);

        $file = $request->file('file');

        $eol = "\n";


        $mail = new AdminMail('E-Mail forgotten');
        $mail->content = 'A user tried to log in but forgot his username and email:' . $eol;
        $mail->content .= '* Tried email: ' . $request->get('email') . $eol;
        $mail->content .= '* Username: ' . $request->get('name') . $eol;
        $mail->content .= '* Full name: ' . $request->get('fullname') . $eol;
        $mail->content .= '* Email: ' . $request->get('emailcurrent') . $eol;
        $mail->content .= '* Address: ' . $request->get('address') . $eol;
        $mail->content .= '* Phone: ' . $request->get('phone') . $eol;
        $mail->url = route('login');
        if ($file->isValid()) {
            $mail->addFile($file->path(), 'idcard.' . $file->guessExtension());
        }

        foreach (User::where('admin', true)->where('active', true)->whereNotNull('email')->get() as $user) {
            Mail::to($user->email)->send($mail);
        }

        return backorhome()->with('status', __('Your request was sent.'));
    }

    public function elevate()
    {
        $user = Auth::user();
        if (is_null($user)) {
            return backorhome()->with('message', ['text' => __('You must be logged in.'), 'title' => __('Admin'), 'icon' => 'error']);
        }
        if (!$user->admin) {
            return backorhome()->with('message', ['text' => __('You must be an admin.'), 'title' => __('Admin'), 'icon' => 'error']);
        }
        if ($user->elevated) {
            $user->elevated = true;
            return backorhome();
        }
        return view('auth.confirm-elevate', ['forward' => url()->previous()]);
    }

    public function elevate_confirm(Request $request): RedirectResponse
    {
        /** @var \App\Model\User */
        $user = Auth::user();
        if (is_null($user)) {
            return backorhome();
        }

        $user->elevated = true;

        $forward = $request->get('forward', url('/'));

        if ($user->elevated) {
            return redirect($forward)->with('message', ['text' => __('You now have high level admin rights.') . '<br />' . __('Use them wisely.'), 'title' => __('Admin'), 'icon' => 'success']);
        } else {
            return redirect($forward)->with('message', ['text' => __('There was an error setting your rights. Please try again later.'), 'title' => __('Admin'), 'icon' => 'error']);
        }
    }

    public function elevate_drop()
    {
        /** @var \App\Model\User */
        $user = Auth::user();
        if (!is_null($user)) {
            $user->elevated = false;
        }

        return redirect(url()->previous());
    }
}
