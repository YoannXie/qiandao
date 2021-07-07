<!DOCTYPE html>
<html>
<head>
	<title>签到</title>
	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
	<input type="text" id="top_num" value="10">
		<button id="init_tops">发布签到top名额</button><br>

	<input type="text" id="user_num" value="100">
		<button id="init_users">发布签到用户</button><br>

	<button id="init_sign_table">清空签到表</button><br>

	@if($signs)
		@foreach($signs as $user_id => $sign_type)
			@if($sign_type == 3)
				<span>{{ '用户'.$user_id .'：奖励签到' }}，</span>
			@elseif($sign_type == 2)
				<span>{{ '用户'.$user_id .'：普通签到' }}，</span>
			@else
				<span>{{ '用户'.$user_id .'：未签到' }}，</span>
			@endif
		@endforeach
	@endif
	
</body>

<script>

	$('#init_tops').click(function(){
		$.ajax({
			url:'/api/setTops?top_num='+$('#top_num').val(),
			success:function(res){
				alert(res)
			}
		})
	})

	$('#init_users').click(function(){
		$.ajax({
			url:'/api/setUsers?user_num='+$('#user_num').val(),
			success:function(res){
				alert(res)
				window.location.reload()
			}
		})
	})

	$('#init_sign_table').click(function(){
		$.ajax({
			url:'/api/cleanTable',
			success:function(res){
				alert(res)
			}
		})
	})

</script>

</html>