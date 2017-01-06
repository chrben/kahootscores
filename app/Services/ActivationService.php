<?php

namespace App\Services;


use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use App\Http\ActivationRepository;
use App\User;

class ActivationService
{

    protected $mailer;

    protected $activationRepo;
    protected $overrideEmail;

    protected $resendAfter = 24;

    public function __construct(Mailer $mailer, ActivationRepository $activationRepo)
    {
        $this->mailer = $mailer;
        $this->activationRepo = $activationRepo;
        $this->overrideEmail = env('AUTH_ACTIVATIONMAIL');
    }

    public function sendActivationMail($user)
    {

        if ($user->activated || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->activationRepo->createActivation($user);

        $link = route('user.activate', $token);
        $message = sprintf('Activate account for %s: %s', $user->name, $link);

        $this->mailer->raw($message, function (Message $m) use ($user) {
            $m->to((isset($this->overrideEmail) ? $this->overrideEmail : $user->email))->subject('KahootScores: New user activation');
        });


    }

    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $user = User::find($activation->user_id);

        $user->activated = true;

        $user->save();

        $this->activationRepo->deleteActivation($token);

        return $user;

    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }

}