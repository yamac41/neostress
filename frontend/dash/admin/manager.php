<?php
$pagename = "API Manager";
include 'header.php';
?>

	<div class="main">
    	<div class="container mt-5 mb-5 px-4">
      		<div class="row g-4">
      			
      			<div class="col-md-12">
      				<div class="card plan-card">
			          <div class="card-header">
			            <div class="row">
			              <div class="col-md-6 text-start">
			                All Servers
			              </div>
			              <div class="col-md-6 text-end">
			                <button type="button" onclick="AddServerModal()" style="text-transform: uppercase; font-size: 0.8rem;" class="btn btn-primary btn-sm"><i class="fa-solid fa-server"></i> Add Server</button>
			              </div>
			            </div>
			          </div>
			          <div class="card-body">
			          	<div class="table-responsive mx-2">
			              <table id="servers-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
			                <thead>
			                  <tr>
			                    
			                    <th scope="col" class="text-center">#</th>
			                    <th scope="col" class="text-center">Name</th>
			                    <th scope="col" class="text-center">API URL</th>
			                    <th scope="col" class="text-center">Slots</th>
			                    <th scope="col" class="text-center">Type</th>
			                    <th scope="col" class="text-center">Network</th>
			                    <th scope="col" class="text-center">Methods</th>
			                    <th scope="col" class="text-center">Status</th>
			                    <th scope="col" class="text-center">Actions</th>
			                  </tr>
			                </thead>
			                
			              </table>
			            </div>


			          </div>
			        </div>
				</div>
				<div class="col-md-12">
      				<div class="card plan-card">
			          <div class="card-header">
			            <div class="row">
			              <div class="col-md-6 text-start">
			                Methods
			              </div>
			              <div class="col-md-6 text-end">
			                <button type="button" onclick="AddMethod()" style="text-transform: uppercase; font-size: 0.8rem;" class="btn btn-primary btn-sm"><i class="fa-solid fa-gear"></i> Add Method</button>
			              </div>
			            </div>
			          </div>
			          <div class="card-body">
			          	<div class="table-responsive mx-2">
			              <table id="methods-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
			                <thead>
			                  <tr>
			                    
			                    <th scope="col" class="text-center">API Name</th>
			                    <th scope="col" class="text-center">Public Name</th>
			                    <th scope="col" class="text-center">Type</th>
			                    <th scope="col" class="text-center">Premium</th>
			                    <th scope="col" class="text-center">Timelimit</th>
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
    </div>

    <div class="modal fade" id="editserver-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        <span id="editserver-header"></span>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-name" class="form-label">Name</label>
					    <input type="text" class="form-control" id="s-name" required>
					</div>
				</div>
				<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-status" class="form-label">Status</label>
					    <select class="form-select" id="s-status">
					    	<option value="online">Online</option>
							<option value="offline">Offline</option>
							<option value="maintaince">Maintaince</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-slots" class="form-label">Slots</label>
					    <input type="number" class="form-control" id="s-slots" required>
					</div>
				</div>
				<div class="col-md-3">
	        		<div class="mb-1">
					    <label for="s-premium" class="form-label">Premium</label>
					    <select class="form-select" id="s-premium">
					    	<option value="yes">Yes</option>
							<option value="no">No</option>
							
						</select>
					</div>
				</div>
				<div class="col-md-3">
	        		<div class="mb-1">
					    <label for="s-type" class="form-label">Type</label>
					    <select class="form-select" id="s-type">
					    	<option value="l4">L4</option>
							<option value="l7">L7</option>
							<option value="mixed">Mixed</option>
							
						</select>
					</div>
				</div>
				<div class="col-md-12">
	        		<div class="mb-1">
					    <label for="s-apiurl" class="form-label">API URL</label>
					    <input type="text" class="form-control" id="s-apiurl" required>
					</div>
				</div>
				<div class="col-md-12">
	        		<div class="mb-1">
					    <label for="s-methods" class="form-label">Methods</label>
					    <textarea class="form-control" id="s-methods" required></textarea>
					</div>
				</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
	        <button type="button" class="btn btn-vnm-indigo" id="saveserver-btn"><i class="fa-solid fa-floppy-disk"></i> Save</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="addserver-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        Add Server
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-servername" class="form-label">Name</label>
					    <input type="text" class="form-control" id="s-servername" required>
					</div>
				</div>
				<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-serverstatus" class="form-label">Status</label>
					    <select class="form-select" id="s-serverstatus">
					    	<option value="online">Online</option>
							<option value="offline">Offline</option>
							<option value="maintaince">Maintaince</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="s-serverslots" class="form-label">Slots</label>
					    <input type="number" class="form-control" id="s-serverslots" required>
					</div>
				</div>
				<div class="col-md-3">
	        		<div class="mb-1">
					    <label for="s-serverpremium" class="form-label">Premium</label>
					    <select class="form-select" id="s-serverpremium">
					    	<option value="yes">Yes</option>
							<option value="no">No</option>
							
						</select>
					</div>
				</div>
				<div class="col-md-3">
	        		<div class="mb-1">
					    <label for="s-servertype" class="form-label">Type</label>
					    <select class="form-select" id="s-servertype">
					    	<option value="l4">L4</option>
							<option value="l7">L7</option>
							<option value="mixed">Mixed</option>
							
						</select>
					</div>
				</div>
				<div class="col-md-12">
	        		<div class="mb-1">
					    <label for="s-serverapiurl" class="form-label">API URL</label>
					    <input type="text" class="form-control" id="s-serverapiurl" required>
					</div>
				</div>
				<div class="col-md-12">
	        		<div class="mb-1">
					    <label for="s-servermethods" class="form-label">Methods</label>
					    <textarea class="form-control" id="s-servermethods" required></textarea>
					</div>
				</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
	        <button type="button" class="btn btn-vnm-indigo" id="addserver-btn" onclick="AddServer()"><i class="fa-solid fa-plus"></i> Add Server</button>
	      </div>
	    </div>
	  </div>
	</div>



<?php include 'footer.php'; ?>