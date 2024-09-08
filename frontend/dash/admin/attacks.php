<?php
$pagename = "Running Attacks";
include 'header.php';
?>
    <div class="main">
    	<div class="container mt-5 mb-5 px-4">
      		<div class="row g-4">
      			<div class="col-md-12">
      				<div class="card running-attacks">
      					<div class="card-header">
				            <div class="row">
				            	<div class="col-md-6 text-start">
				            		Running Attacks
				            	</div>
				            	<div class="col-md-6 text-end">
				            		<button type="button" style="text-transform: uppercase; font-size: 0.8rem;" id="stopallbtn" onclick="StopAllAttacks()" class="btn btn-danger btn-sm"><span id="stopall_def"><i class="fa-solid fa-power-off"></i> Stop All</span><span id="stopall_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait..</span></button>
				            		<button type="button" style="text-transform: uppercase; font-size: 0.8rem;background: #f6c23e;" onclick="OpenBlacklist()" class="btn btn-warning btn-sm"><i class="fa-solid fa-ban"></i> BlackList</button>
				            		<button type="button" style="text-transform: uppercase; font-size: 0.8rem;background:#4e73df;" onclick="OpenLogs()" class="btn btn-primary btn-sm"><i class="fa-solid fa-list"></i> Logs</button>
				            	</div>
				            </div>
				        </div>
				          <div class="card-body">
				            <div class="table-responsive mx-2">
				              <table style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
				                <thead>
				                  <tr>
				                    <th scope="col" class="text-center">User</th>
				                    <th scope="col" class="text-center">Target</th>
				                    <th scope="col" class="text-center">Method</th>
				                    <th scope="col" class="text-center">Server</th>
				                    <th scope="col" class="text-center">Started</th>
				                    <th scope="col" class="text-center">Expires</th>
				                    <th scope="col" class="text-center">Action</th>
				                  </tr>
				                </thead>
				                <tbody id="live-attacks">
				                	

				                </tbody>
				                
				              </table>
				            </div>
				          </div>






      				</div>
      			</div>
      		</div>
      	</div>
    </div>


    <div class="modal fade" id="blacklist-modal" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        BlackList
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">

	        	<div class="col-md-12">
	        		<div class="table-responsive mx-2">
		              <table id="blacklist-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
		                <thead>
		                  <tr>
		                    <th scope="col" class="text-center">Target</th>
		                    <th scope="col" class="text-center">Type</th>
		                    <th scope="col" class="text-center">User</th>
		                    <th scope="col" class="text-center">Action</th>
		                  </tr>
		                </thead>
		                <tbody id="blacklist-body">



		                </tbody>
		                
		              </table>
		            </div>
				</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>

	        <button type="button" class="btn btn-vnm-indigo" onclick="BlackList()"><i class="fa-solid fa-folder-plus" style="color: #fff !important;"></i> BlackList</button>
	        
	      </div>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="logs-modal" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        Attack Logs
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body" style="overflow-x: auto;">
	        <div class="row">

	        	<div class="col-md-12">
	        		<div class="table-responsive mx-2">
		              <table id="logs-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
		                <thead>
		                  <tr>
		                    <th scope="col" class="text-center">User</th>
		                    <th scope="col" class="text-center">Target</th>
		                    <th scope="col" class="text-center">Attack Time</th>
		                    <th scope="col" class="text-center">Started At</th>
		                    <th scope="col" class="text-center">Server</th>
		                  </tr>
		                </thead>
		                <tbody id="logs-body">



		                </tbody>
		                
		              </table>
		            </div>
				</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>

	        <button type="button" class="btn btn-vnm-indigo" onclick="ClearLogs()"><i class="fa-solid fa-trash-can" style="color: #fff !important;"></i> Clear Logs</button>
	        
	      </div>
	    </div>
	  </div>
	</div>
<?php include 'footer.php' ?>