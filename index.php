<?php
$server_ip = '';
$server_name = '';
$token = '';
//server_ip内填入需要监控的服务器地址,server_name内填入服务器的名字,token内填入在https://ipstack.com/申请的API KEY.什么?你这都不会?那我帮不了你了
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui-flag@2.4.0/flag.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0,user-scalable=no, minimal-ui">
	<title>Minecraft Player Stats</title>
	<style>body{background-color:black;}</style>
</head>
<?php 
$status = json_decode(file_get_contents('https://api.mcsrvstat.us/2/'.$server_ip));
$address = 'http://api.ipstack.com/';
$ip = $status->ip;
$fgip = file_get_contents($address.$ip.'?access_key='.$token);
$deip = json_decode($fgip);
?>
<body>
<?php if($status->online === true): ?>
	<div class="ui container">
	<br>
	<div class="ui <?php if($status->debug->query === true){echo "success";}else{echo "warning";} ?> message">
	<div class="header">服务器在线</div>
	<p><?php if($status->debug->query === true){echo "服务器开启了Query,好耶!";}else{echo "不过服务器关闭了Query,坏耶!";} ?></p>
	</div>
	<table class="ui celled inverted table">
	<tr>
		<td>服务器名称</td>
		<td><i class="<?php echo strtolower($deip->country_code); ?> flag"></i><?php echo $server_name; ?></td>
	</tr>
	<tr>
		<td>服务器图标</td>
		<td><img src="<?php echo str_replace("\\/", "/",$status->icon); ?>"></td>
	</tr>	
	<tr>
		<td>服务器MOTD</td>
		<td><?php foreach ($status->motd->html as $motd){echo $motd;} ?></td>
	</tr>	
	<tr>
		<td>服务器版本</td>
		<td><?php echo $status->software; ?>&nbsp;<?php echo $status->version; ?></td>
	</tr>
	<tr>
		<td>网络协议版本号&nbsp;<a data-tooltip="Minecraft中使用的网络协议版本号(1.7以上使用Netty)">[?]</a></td>
		<td><?php echo $status->protocol; ?></td>
	</tr>
	<tr>
		<td>最大人数</td>
		<td><?php echo $status->players->max; ?></td>
	</tr>
	<tr>
		<td>在线人数</td>
		<td><?php echo $status->players->online; ?></td>
	</tr>
	<tr>
	<td>在线玩家</td>
	<td><?php if(isset($status->players->list)){foreach ($status->players->list as $player){echo $player.'<br />';}}else{echo "由于服务端关闭了Query,所以无法从服务端获取数据";} ?></td>
	</tr>
	<tr>
	<td>数据缓存时间</td>
	<td><?php if($status->debug->cachetime == '0'){echo "数据暂时没有缓存哦";}else{echo date('Y-m-d H:i:s',$status->debug->cachetime);}?></td>
	</tr>
	</table><br>
<?php else: ?>
		<div class="ui container">
			<br>
			<div class="ui negative message">
			<div class="header">服务器离线</div>
			<p>虽然非常抱歉，不过请稍后再回来看看</p>
			</div></div>
			<?php endif;?>
</div>
</body>
</html>