<?php
session_start();
require "../Facebook/function/function.php";
require "../Facebook/database/database.php";
if (isset($_POST['login'])) {

	if (required($_POST) == null) {

		$user = login($_POST['email'], $_POST['password'], $pdo);
		if (!is_null($user)) {
			$_SESSION['users'] = $user;
			$info = "{$_POST['email']}"." ".date('Y-m-d H:i:s').PHP_EOL;
			file_put_contents('login.txt',$info,FILE_APPEND);
			header("location:index.php");
		} else {
			$_SESSION['erreur'] = "Email ou mot de passe incorrect";
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

<body class="isolate bg-white ">
	<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
		<div class="sm:mx-auto sm:w-full sm:max-w-sm">
			<img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
			<h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
		</div>

		<div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
			<?php if (isset($_SESSION['erreur'])) : ?>
				<div class="alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
					<span class="block sm:inline"><?= $_SESSION['erreur'] ?></span>
				</div>
			<?php endif ?>
			<br>
			<form class="space-y-6" action="#" method="POST">
				<div>
					<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
					<div class="mt-2">
						<input id="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : null  ?>" name="email" type="email" autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 <?php echo isset($_SESSION['message']['email']) ? 'border-red-500' : 'border-0'; ?> shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
					</div>
					<?php if (isset($_SESSION['message']['email'])) : ?>
						<p class="text-red-500 text-xs mt-1"><?php echo $_SESSION['message']['email']; ?></p>
					<?php endif; ?>
				</div>

				<div>
					<div class="flex items-center justify-between">
						<label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
					</div>
					<div class="mt-2">
						<input id="password" name="password" type="password" autocomplete="current-password" class="block w-full <?php echo isset($_SESSION['message']['password']) ? 'border-red-500' : 'border-0'; ?> rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
					</div>
					<?php if (isset($_SESSION['message']['password'])) : ?>
						<p class="text-red-500 text-xs mt-1"><?php echo $_SESSION['message']['password']; ?></p>
					<?php endif; ?>
					<br>
					<div class="text-sm">
						<a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
					</div>
				</div>
				<div>
					<button type="submit" name="login" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
				</div>
			</form>

		</div>
	</div>



	<?php require "../Facebook/layouts/footer.php";
	unset($_SESSION['erreur']);
	?>
</body>

</html>