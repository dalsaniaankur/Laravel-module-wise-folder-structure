Click here to reset your password: <a href="{{ $link = url('member_reset_password', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
