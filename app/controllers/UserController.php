<?php

class UserController extends \BaseController 
{
	// display the login page
	public function getUserLogin()
	{
		return View::make('login');
	}

	// log the user in, or return error if login data is bad
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

	// log the user out
	public function getUserLogout()
	{
		Auth::logout();

		return Redirect::to('/');
	}

	// display the user sign up page
	public function getUserSignup()
	{
		return View::make('signup');
	}

	// do some basic error checking and insert a new user to the users table, then log in
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

	// display the update profile page with the current user's email
	public function getUpdateProfile()
	{
		$uid = Auth::id();
		$user = User::find($uid);

		return View::make('/updateProfile')
			->with('email', $user->email);
	}

	// do some basic error checking, then update the user's email address and password
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
