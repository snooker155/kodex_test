<html>
<head>
	<title>Parser</title>
</head>	
<body>
	<div>
		<h1>Parser</h1>
	</div>


@if(isset($results) && count($results))
	@foreach ($results as $result)
			{{-- $result->title --}}
			{{ $result->company }}
			{{ $result->city }}
			{{ $result->salary }}
			{{ $result->experience }}
			{{ $result->type_of_job }}
	@endforeach
@endif



</body>
</html>