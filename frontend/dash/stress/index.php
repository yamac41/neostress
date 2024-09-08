
<?php 
  $pagename = "Stress Panel";
  include '../header.php'; 
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.min.js" integrity="sha512-igVQ7hyQVijOUlfg3OmcTZLwYJIBXU63xL9RC12xBHNpmGJAktDnzl9Iw0J4yrSaQtDxTTVlwhY730vphoVqJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">

<style>
	#l4btn {
		border-radius: 6px;
		background: #0EB7FF;
		color: #0F141E;
		font-size: 16px;
		font-style: normal;
		font-weight: 500;
		line-height: 24px;
		opacity: 0.6;
		-webkit-transition: opacity 0.25s ease-in-out;
		-moz-transition: opacity 0.25s ease-in-out;
		-ms-transition: opacity 0.25s ease-in-out;
		-o-transition: opacity 0.25s ease-in-out;
		transition: opacity 0.25s ease-in-out;
	}

	#l4btn:hover {
		opacity: 1;
	}

	#l4concs, #l7concs {
		padding-left: 3px;
		padding-right: 8px;
		top: 3px;
		width: calc(100% - (55px)) !important;
	}

	.rounded-slider {
		height: 4px !important;
	}

	.rounded-slider-num {
		display: inline-block !important;
		position: relative !important;
		width: 45px !important;
		color: #fff !important;
		line-height: 20px !important;
		text-align: center !important;
		border-radius: 3px !important;
		background: #171C27 !important;
		padding: 5px 10px !important;
		margin-left: 8px !important;
		margin-top: -10px !important;
	}

	.rounded-slider .noUi-handle {
		height: 12px !important;
		width: 12px !important;
		top: -4px !important;
		right: -9px !important;
		border-radius: 9px !important;
		background: #0EB7FF;
		box-shadow: none;
		border: none;
	}

	.noUi-handle:after, .noUi-handle:before {
		display: none;
	}

	.noUi-target {
		box-shadow: none;
		border: none;
		background-color: #171C27;
	}

	.noUi-connect {
		background: #0EB7FF;
	}
	
</style>

<div class="p-4 lg:px-24 text-white page-cont">
	<div class="welcome-header">
		Stress Panel
	</div>
	<div class="welcome-subtitle">
		Unleash Layer 4 and Layer 7 attacks with precision.
    </div>
	<div class="grid grid-cols-3 gap-4 mt-8">
		<div class="item3 p-3">
			<div class="layers">
				<div id="layer_4" class="layer active" style="border-radius: 5px 0 0 5px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
						<circle cx="5" cy="5" r="4" fill="#0EB7FF"/>
					</svg>
					<span>Layer 4</span>
				</div>
				<div id="layer_7" class="layer" style="border-radius: 0 5px 5px 0;">
					<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
						<circle cx="5" cy="5" r="4" fill="#FF5555"/>
					</svg>
					<span>Layer 7</span>
				</div>
			</div>
			<div id="layer4_container" class="mt-3">
				<div class="mb-3">
				    <label for="l4host" class="form-label">Host</label></br>
				    <input type="text" class="form-control w-full mt-1" placeholder="1.1.1.1" id="l4host" required>
				</div>
				<div class="mb-3">
					<div class="grid grid-cols-2">
						<div class="mr-4">
							<label for="l4port" class="form-label">Port</label></br>
							<input type="number" class="form-control w-full" id="l4port" placeholder="80" required>    
						</div>
						<div>
							<label for="l4time" class="form-label">Time</label>
							<input type="number" class="form-control w-full" id="l4time" placeholder="60" required>
						</div>
					</div>
				</div>
				<div class="mb-3 w-full">
				    <label for="l4method" class="form-label">Attack Method</label></br>
					<select class="form-select" id="l4method" style="width: inherit;">
						<optgroup label="Amplification (AMP)" style="color: #8DA2FB;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'AMP' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
						<optgroup label="User Datagram Protocol (UDP)" style="color: #E74694;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'UDP' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
						<optgroup label="Transmission Control Protocol (TCP)" style="color: #5850EC;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'TCP' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
						<optgroup label="Game Methods" style="color: #1e81b0;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'GAME' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
						<optgroup label="Special Methods" style="color: #eab676;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'SPECIAL' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
						<optgroup label="Botnet Methods" style="color: #F05252;">
						<?php
						  $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'BOTNET' ORDER BY `id` ASC");
						  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
							  $apiname = $methodinfo['apiname'];
							  $publicname = $methodinfo['publicname'];
							  $about = $methodinfo['about'];
							  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  }
						?>
						</optgroup>
					</select>
				</div>
				<div class="mb-3">
				    <label for="l4concs" class="form-label">Concurrents</label></br>
					<div class="flex">
						<div id="l4concs" class="rounded-slider"></div>
						<span id="l4concs_num" value="" class="rounded-slider-num"></span>
					</div>
				</div>
				<div class="mb-3">
					<button type="button" onclick="StartL4Attack()" id="l4btn" class="p-2 w-full">
						<span id="l4_def">
					 	 Send Attack
					 	</span>
                    	<span id="l4_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                    	 Please wait...
                    	</span>
					</button>
				</div>
			</div>
			<div id="layer7_container" class="mt-3" style="display: none;">
				<div class="mb-3">
					<div class="grid grid-cols-3 mt-1">
						<div class="col-span-2 mr-4">
							<label for="l7host" class="form-label">URL</label></br>
							<input type="text" class="form-control w-full" placeholder="https://example.com" id="l7host" required>
						</div>
						<div class="col-span-1">
							<label for="l7time" class="form-label">Time</label>
							<input type="number" class="form-control w-full" id="l7time" placeholder="60" required>
						</div>
					</div>
				</div>
				<div class="mb-3 w-full">
					<label for="l7method" class="form-label">Attack Method</label>
					<select class="form-select" id="l7method" onclick="CheckL7Method()" style="width: inherit;">
					  	<optgroup label="Basic" style="color: #8DA2FB;">
						  <?php
						  	$SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'BASICL7' ORDER BY `id` ASC");
						  	while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
						  		$apiname = $methodinfo['apiname'];
						  		$publicname = $methodinfo['publicname'];
								  $about = $methodinfo['about'];
								  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  	}
						  ?>
					  	</optgroup>
					  	<optgroup label="Advanced" style="color: #D61F69;">
						  <?php
						  	$SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'PREMIUML7' ORDER BY `id` ASC");
						  	while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
						  		$apiname = $methodinfo['apiname'];
						  		$publicname = $methodinfo['publicname'];
								  $about = $methodinfo['about'];
								  echo '<option value="'.$apiname.'">'.($methodinfo['premium'] == "1" ? "[PREMIUM]" : "[BASIC]").' '.$publicname.' - '.$about.'</option>';
						  	}
						  ?>
					  	</optgroup>
					</select>
				</div>
				<div class="mb-3">
					<div class="grid grid-cols-2 mt-1">
						<div class="mr-4">
							<label for="l7reqmethod" class="form-label">Request Method</label>
							<select class="form-select w-full" id="l7reqmethod">
								<option value="GET">GET</option>
								<option value="POST">POST</option>
								<option value="HEAD">HEAD</option>
							</select>
						</div>
						<div>
							<label for="l7reqs" class="form-label">Requests per IP</label>
							<input type="number" class="form-control w-full" id="l7reqs" placeholder="64" required>
						</div>
					</div>	
				</div>
				<div class="mb-3">
				    <label for="l7concs" class="form-label">Concurrents</label></br>
					<div class="flex">
						<div id="l7concs" class="rounded-slider"></div>
						<span id="l7concs_num" value="" class="rounded-slider-num"></span>
					</div>
				</div>
				<div class="mb-3">
					<button type="button" onclick="StartL7Attack()" id="l4btn" class="p-2 w-full">
						<span id="l7_def">
					 	 Send Attack
					 	</span>
                    	<span id="l7_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                    	 Please wait...
                    	</span>
					</button>
				</div>
			</div>
		</div>
		<div class="item3 col-span-2">
			<div class="card-header">
				<div class="items-center justify-between pb-3 border-b border-gray-200 flex dark:border-gray-700 p-3">
					<div>
						<div style="background: #171C27; padding: 8px 16px; border-radius: 5px;" class="inline">
						  Running attacks
						  <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-1 text-sm font-medium rounded-full dark:bg-gray-800 text-white">0</span>
						</div>
					</div>
					<div>
						<button type="button" style="text-transform: uppercase; font-size: 0.8rem;font-weight: 400 !important;" id="stopallbtn" onclick="StopAllAttacks()" class="btn btn-r1"><span id="stopall_def"><i class="fa-solid fa-power-off"></i> Stop All</span><span id="stopall_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait..</span></button>
					</div>
				</div>
  			</div>

  			<div class="card-body">
  				<div>
  					<div class="table-responsive mx-2">
			          <table id="attacks-table" style="width:100%" class="mt-2 stripe display table bg-gray-800 table-striped table-bordered border-gray">
			            <thead>
			              <tr>
			                <th scope="col" class="text-start">ID</th>
			                <th scope="col" class="text-center">Target</th>
			                <th scope="col" class="text-center">Method</th>
			                <th scope="col" class="text-center">Expire</th>
			                <th scope="col" class="text-center">Action</th>
			              </tr>
			            </thead>
			            
			          </table>
			        </div>
				</div>
  			</div>
		</div>
	</div>
</div>

<script>
var l4concs = document.getElementById('l4concs');
var l4concspan = document.getElementById('l4concs_num');
noUiSlider.create(l4concs, {
    start: [1],
    connect: 'lower',
    step: 1,
    keyboardSupport: false, 
    format: wNumb({
        decimals: 0,
        
    }),
    range: {
        'min': 0,
        'max': 15
    }
    
});
l4concs.noUiSlider.on('update', function(values, handle) {
    l4concspan.innerHTML = values[handle];
    
});

var l7concs = document.getElementById('l7concs');
var l7concspan = document.getElementById('l7concs_num');
noUiSlider.create(l7concs, {
    start: [1],
    connect: 'lower',
    step: 1,
    keyboardSupport: false, 
    format: wNumb({
        decimals: 0,
        
    }),
    range: {
        'min': 0,
        'max': 15
    }
    
});
l7concs.noUiSlider.on('update', function(values, handle) {
    l7concspan.innerHTML = values[handle];
    
});

let currentTab = 0

$("#layer_4").click(() => {
	if(currentTab == 0) return;
	currentTab = 0

	$("#layer_7").removeClass("active")
	$("#layer_4").addClass("active")

	$("#layer7_container").hide()
	$("#layer4_container").show()
})

$("#layer_7").click(() => {
	if(currentTab == 1) return;
	currentTab = 1

	$("#layer_4").removeClass("active")
	$("#layer_7").addClass("active")
	
	$("#layer4_container").hide()
	$("#layer7_container").show()

})
</script>

<?php include '../footer.php'; ?>

