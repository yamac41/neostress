<?php 
  $pagename = "API Manager";
  include '../header.php';


  $SelectAPI = $odb -> prepare("SELECT `apitoken` FROM `users` WHERE `username` = :username AND `id` = :id");
  $SelectAPI -> execute(array(':username' => $_SESSION['username'], ':id' => $_SESSION['id']));
  $apitoken = $SelectAPI -> fetchColumn(0);

  
?>


<div class="p-4 lg:px-24 text-white page-cont">
	<div class="welcome-header">
    API Manager
	</div>
	<div class="welcome-subtitle">
		API Manager: <br>Streamline, Monitor, and Optimize Your Integration Capabilities
  </div>

  <div class="w-full mt-8 card">
    <div class="card-header">
      <div class="p-3">
        Authorization Token
      </div>
    </div>
    <div class="card-container">
      <div class="mb-3 px-5 mt-5">
        <input type="text" class="form-control text-center w-full" id="apitokenfield" readonly="">
      </div>
      <div class="mb-3 px-5 pb-5 flex justify-center">
        <button type="button" onclick="GenerateToken()" id="gentoken-btn" class="px-2 btn btn-r2 mr-3">
          <span id="createp_def"><i class="fa-solid fa-arrows-rotate"></i> Generate Token</span>
          <span id="createp_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait..</span>
        </button>
        <button type="button" onclick="DisableToken()" id="distoken-btn" class="px-2 btn btn-r1">
          <span id="createp_def"><i class="fa-solid fa-circle-xmark"></i> Disable Access</span>
          <span id="createp_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait..</span>
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-3 mt-8">
    <div>
      <div class="card">
        <div class="card-header">
          <div class="p-3">
            API Params
          </div>
        </div>
        <div class="card-container p-3">
          <ul>
            <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">host</span> - IP Address or Website URL</li>
            <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">port</span> - Dest. Port (0-65535) | for Websites not required</li>
            <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">time</span> - Attack Time (min 30sec)</li>
            <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">method</span> - Method Name from list ('stop' or 'stopall' to stop attack/s)</li>
          </ul>
          <div class="mt-3">
            <details class="group">
              <summary class="flex justify-between items-center font-medium cursor-pointer list-none py-1 px-2">
                <span>View more</span>
                  <span class="transition group-open:rotate-180">
                  <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                </span>
              </summary>
              <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                <ul>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">concs</span> - Concurrents number (default 1)</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">req_method</span> - Request method (GET, POST, HEAD)</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">reqs</span> - Requests per IP</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">httpversion</span> - HTTP Version (HTTP1/HTTP2)</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">referrer</span> - set the URL from which requests are coming</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">cookies</span> - set a specifically cookie</li>
                  <li><span class="vnm-dark-badge py-1 px-2 rounded-lg">geoloc</span> - from which country requrests are coming (rand, us, eu, ch, au)</li>
                </ul>
              </p>
            </details>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="card">
        <div class="card-header">
          <div class="p-3">
            Attack Methods
          </div>
        </div>
        <div class="card-container p-3">
          <ul>
            <li>Amplification <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'AMP' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>User Datagram Protocol <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'UDP' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Transmission Control Protocol <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'TCP' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Game Methods<br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'GAME' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Special Methods<br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'SPECIAL' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Botnet <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'BOTNET' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Basic Layer7 <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'BASICL7' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
            <li>Premium Layer7 <br>
              <?php
                $SelectL7 = $odb -> query("SELECT * FROM `methods` WHERE `type` = 'PREMIUML7' ORDER BY `id` ASC");
                $countm = $SelectL7 -> rowCount();
                if($countm == 0){
                  echo '<span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">NONE</span>';
                }else{
                  while($methodinfo = $SelectL7 -> fetch(PDO::FETCH_ASSOC)){
                    $apiname = $methodinfo['apiname'];
                    echo ' <span class="vnm-dark-badge py-1 px-2 rounded-lg" style="font-size: .6rem;">'.$apiname.'</span>';
                  }
                }
              ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include '../footer.php'; ?>