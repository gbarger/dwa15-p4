<?php

class UserController extends \BaseController 
{
	
	public function getUserLogin()
	{
		return View::make('login');
	}

	public function postUserLogin()
	{
		$cred = Input::only('email','password');
		$rem = Input::get('remember');

		if (Auth::attempt($cred, $remember = $rem))
		{
			return Redirect::intended('/library');
		}
		else
		{
			$errors = array();
			$errors[] = 'Your login attempt was incorrect';

			return Redirect::to('/login')->with('errors', $errors);
		}
	}

	public function getUserLogout()
	{
		Auth::logout();

		return Redirect::to('/');
	}

	public function getUserSignup()
	{
		return View::make('signup');
	}

	public function postUserSignup()
	{
		$errors = array();
		$em = Input::get('email');
		$pw = Input::get('password');
		$pw2 = Input::get('confirm');
		$rem = Input::get('remember');

		if ($em == '')
			$errors[] = 'Email must not be blank.';

		if ($pw == '')
			$errors[] = 'Password must not be blank.';

		if ($pw2 == '')
			$errors[] = 'Password confirmation must be completed.';

		if ($pw != $pw2)
			$errors[] = 'Password and confirmation must match.';

		if (count($errors) > 0)
		{
			return View::make('signup')->with('errors', $errors);
		}
		else
		{
			$user = new User;
			$user->email = $em;
			$user->password = Hash::make($pw);

			$user->save();

			Auth::login($user, $remember = $rem);

			return View::make('/library');
		}
	}

	public function getUpdateProfile()
	{
		$uid = Auth::id();
		$user = User::find($uid);

		return View::make('/updateProfile')
			->with('email', $user->email);
	}

	public function postUpdateProfile()
	{
		$errors = array();
		$email = Input::get('email');
		$newPass = Input::get('newPassword');
		$confirmPass = Input::get('confirm');

		if ($email == '')
			$errors[] = 'Email must not be blank.';

		if ($newPass != '' && $newPass != null && $newPass != $confirmPass)
			$errors[] = 'The password and confirmation must match';

		if (count($errors) > 0)
		{
			return View::make('/updateProfile')
				->with('email', $email)
				->with('errors', $errors);
		}
		else
		{
			$user = User::find(Auth::id());
			$user->email = $email;

			if ($newPass != null && $newPass != '')
				$user->password = Hash::make($newPass);

			$user->save();

			return View::make('/library');
		}
	}
}
