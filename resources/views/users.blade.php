<!doctype html>
<html>
<head>
	<title>Test</title>
</head>
<body>
	<ul>
	@foreach($users as $user)
		<li>{{ $user->username }}</li>
	@endforeach
	</ul>
</body>
</html>