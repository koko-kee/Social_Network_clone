<?php
require "../Facebook/function/function.php";
require "../Facebook/database/database.php";

if (isset($_POST['register'])) {


	if(required($_POST) == null)
	{
		$file_tmp = $_FILES['profil']['tmp_name'];
		$file_name =  $_FILES['profil']['name'];
		$Path = "../Facebook/images/" . $file_name;
		if (move_uploaded_file($file_tmp, $Path)) {
			$data = [
				"username" => $_POST['username'],
				"email" => $_POST['email'],
				"profil" =>  $file_name,
				"password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
			];
			$lastUserId = insert("users", $data, $pdo);
			saveUserInFile($lastUserId, $pdo);
			header("location:login.php");
		}
	}
	
}

?>
<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
	<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
		<div class="sm:mx-auto sm:w-full sm:max-w-sm">
			<img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
			<h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
		</div>

		<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
			<?php if (isset($_SESSION['global'])) : ?>
				<div role="alert">
					<div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
						Danger
					</div>
					<div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
						<p><?= $_SESSION['global'] ?></p>
					</div>
				</div>
			<?php endif ?>
			<br>
			<form class="space-y-6" action="#" method="POST" enctype="multipart/form-data">
				<div>
					<label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
					<div class="mt-2">
						<input id="username" value="<?php echo (isset($_POST['username'])) ? $_POST['username'] : null  ?>" name="username" type="text" autocomplete="username"  class="block w-full rounded-md border-2 <?php echo isset($_SESSION['message']['username']) ? ' border-red-500' : null   ?> py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
						<?php if(isset($_SESSION['message']['username'])):?>
							<p class="text-red-500 text-xs mt-1"><?=$_SESSION['message']['username']?></p>
						<?php endif ?>
					</div>
				</div>
				<div>
					<label for="picture" class="block text-sm font-medium leading-6 text-gray-900">Profil</label>
					<div class="mt-2">
						<input id="picture" name="profil" type="file" autocomplete="file" class="block w-full rounded-md border-2  py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
					</div>
				</div>
				<div>
					<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
					<div class="mt-2">
						<input id="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : null  ?>" name="email" type="email" autocomplete="email"  class="block w-full rounded-md border-2 <?php echo isset($_SESSION['message']['email']) ? ' border-red-500' : null   ?> py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
						<?php if(isset($_SESSION['message']['email'])):?>
							<p class="text-red-500 text-xs mt-1"><?=$_SESSION['message']['email']?></p>
						<?php endif ?>
					</div>
				</div>
				<div>
					<label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
					<div class="mt-2">
						<input id="password" name="password" type="password" autocomplete="current-password"  class="block w-full rounded-md border-2 <?php echo isset($_SESSION['message']['password']) ? ' border-red-500' : null   ?> py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
					    <?php if(isset($_SESSION['message']['password'])):?>
							<p class="text-red-500 text-xs mt-1"><?=$_SESSION['message']['password']?></p>
						<?php endif ?>
					</div>
				</div>
				<div class="flex items-center justify-between">
					<div class="text-sm">
						<a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
					</div>
				</div>
				<div>
					<button type="submit" name="register" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
				</div>
			</form>


			<p class="mt-10 text-center text-sm text-gray-500">
				Not a member?
				<a href="login.php" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login</a>
			</p>
		</div>
	</div>

</body>

</html>