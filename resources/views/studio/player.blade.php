<!DOCTYPE html>
<html lang="en">
	<head>
		<title>RAW Digiplay - Studio Player {{ $location }}</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta charset="utf-8">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link rel="stylesheet" type="text/css" href="/css/app.css">
		
		<script src="/js/app.js"></script>
    </head>
    <body class="studio-player">
        <div class="container-fluid">
            <div class="row" style="height:100vh;">
                <div class="col-sm">
                    @for($i = 0; $i < 3; $i++)
                        <div class="row studio-player-row">
                            <div class="row no-gutters" style="width: 100%;">
                                <div class="col-sm">
                                    <h1 class="text-warning" style="font-variant-numeric: tabular-nums;">03:15.34</h1>
                                    <h3 class="text-warning" style="font-variant-numeric: tabular-nums;">End Time: 13:42.38</h3>
                                </div>
                                <div class="col-sm">
                                    <h2 class="text-warning">Queen</h2>
                                    <h2 class="text-warning">Killer Queen</h2>
                                </div>
                            </div>
                            <input type="range" class="studio-player">
                            <div class="row no-gutters" style="width: 100%;">
                                <div class="col-sm">
                                    <button class="btn btn-lg btn-warning">
                                        <i class="fa fa-play"></i>
                                    </button>
                                </div>
                                <div class="col-sm">
                                        <button class="btn btn-lg btn-warning">
                                            <i class="fa fa-stop"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    @endfor
                    
                </div>
                <div class="col-sm">
                    @for($i = 0; $i < 2; $i++)
                        <div class="row">
                            @for($j = 0; $j < 12; $j++)
                                <div class="audiowall-item" style="background:#428bca;color:#ffffff;">
                                    <div class="row audiowall-title no-gutters">
                                            <div class="col-sm audiowall-item-title-text">
                                                A track
                                            </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </body>
</html>