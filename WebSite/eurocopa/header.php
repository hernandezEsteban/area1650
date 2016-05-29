<?php
	function encabezado()
	{
		$script = '<!DOCTYPE HTML>'; 
		$script = $script . '    <html>';
		$script = $script . '        <head>';
		$script = $script . '            <title>Football Statistics</title>';
		$script = $script . '            <meta name="description" content="Football Statistic of All Times">';
		$script = $script . '            <meta name="robots" content="index, nofollow">';
		$script = $script . '            <meta http-equiv="content-type" content="text/html;charset=UTF-8">';
		$script = $script . '         </head>';
		$script = $script . '		<script>';
		$script = $script . '			(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
		$script = $script . '			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
		$script = $script . '			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
		$script = $script . '			})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');';
		$script = $script . '			ga(\'create\', \'UA-30258137-2\', \'auto\');';
		$script = $script . '			ga(\'send\', \'pageview\');';
		$script = $script . '		</script>';
		return $script;
	}		
?>