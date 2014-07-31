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

			try
			{
				$user->save();
			}
			catch(Exception $e)
			{
				$errors = array('There was an error saving the user: ' . $e);
				return View::make('signup')->with('errors', $errors);
			}

			Auth::login($user, $remember = $rem);

			return View::make('/library');
		}
	}
}
