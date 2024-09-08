<?php
$pagename = "Users Manager";
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
			            		All Users
			            	</div>
			            	<div class="col-md-6 text-end">
			            		<button type="button" style="text-transform: uppercase; font-size: 0.8rem;" id="viewbannedbtn" onclick="BannedUsers()" class="btn btn-primary btn-sm"><i class="fa-solid fa-ban"></i> View Banned</button>
			            	</div>
			            </div>
			          </div>
			          <div class="card-body">
			          	<div class="table-responsive mx-2">
			              <table id="users-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
			                <thead>
			                  <tr>
			                    
			                    <th scope="col" class="text-start">#</th>
			                    <th scope="col" class="text-center">Username</th>
			                    <th scope="col" class="text-center">Email</th>
			                    <th scope="col" class="text-center">Plan</th>
			                    <th scope="col" class="text-center">Last Login</th>
			                    <th scope="col" class="text-center">Rank</th>
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

    <div class="modal fade" id="edituser-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        <span id="edituser-header"></span>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="m-username" class="form-label">Username</label>
					    <input type="text" class="form-control" id="m-username" required>
					</div>
					<div class="mb-1">
					    <label for="m-email" class="form-label">Email</label>
					    <input type="email" class="form-control" id="m-email" required>
					</div>
					<div class="mb-1">
					    <label for="m-rank" class="form-label">Rank</label>
					    <select class="form-select" id="m-rank">
					    	<option selected>Select</option>
						  	<option value="Admin">Admin</option>
							<option value="Support">Support</option>
							<option value="User">User</option>
						</select>
					</div>
					<div class="mb-1">
					    <label for="m-plan" class="form-label">Plan</label>
					    <select class="form-select" id="m-plan">
					    	
							<?php 
					    		$Plans = $odb -> query("SELECT `name`, `id` FROM `plans` ORDER BY `id` ASC");
					    		while($plan = $Plans -> fetch(PDO::FETCH_ASSOC)){
					    			echo '<option value="'.$plan['id'].'">'.$plan['name'].'</option>';
					    		}
							?>
						</select>
					</div>
					<div class="mb-1">
					    <label for="m-expire" class="form-label">Set Plan Expiration</label>
					    <input type="datetime-local" class="form-control" id="m-expire" value="2" required>
					</div>
					<div class="mb-1">
					    <label for="m-balance" class="form-label">Balance</label>
					    <input type="number" class="form-control" id="m-balance" required>
					</div>
					<div class="mb-1">
					    <label for="m-premium" class="form-label">Premium</label>
					    <select class="form-select" id="m-premium">
					    	<option selected>Select</option>
						  	<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</div>
					<div class="mb-1">
					    <label for="m-apiaccess" class="form-label">API Access</label>
					    <select class="form-select" id="m-apiaccess">
					    	<option selected>Select</option>
						  	<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</div>



	        	</div>
	        	<div class="col-md-6">
	        		<div class="mb-1">
					    <label for="m-apikey" class="form-label">API Key</label>
					    <input type="text" class="form-control" id="m-apikey" required>
					</div>
					<div class="mb-1">
					    <label for="m-concs" class="form-label">Add-on Concs</label>
					    <input type="number" class="form-control" id="m-concs" required>
					</div>
					<div class="mb-1">
					    <label for="m-time" class="form-label">Add-on Time</label>
					    <input type="number" class="form-control" id="m-time" required>
					</div>
					<div class="mb-1">
					    <label for="m-blacklist" class="form-label">Add-on Blacklist</label>
					    <input type="number" class="form-control" id="m-blacklist" required>
					</div>
					<div class="mb-1">
					    <label for="m-secret" class="form-label">Secret Key</label>
					    <input type="text" class="form-control" id="m-secret" required>
					</div>
					<div class="mb-1">
					    <label for="m-cexpire" class="form-label">Currently Plan Expiration</label>
					    <input type="text" class="form-control" id="m-cexpire" disabled>
					</div>
					<div class="mb-1">
					    <label for="m-created" class="form-label">Created</label>
					    <input type="text" class="form-control" id="m-created" disabled>
					</div>
					<div class="mb-1">
					    <label for="m-lastlogin" class="form-label">Last Login</label>
					    <input type="text" class="form-control" id="m-lastlogin" disabled>
					</div>

	        	</div>

	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
	        <button type="button" class="btn btn-vnm-indigo" id="saveuser-btn"><i class="fa-solid fa-floppy-disk"></i> Save</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div class="modal fade" id="bannedusers-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        Banned Users
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-12">
	        		<div class="table-responsive mx-2">
		              <table id="users-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
		                <thead>
		                  <tr>
		                    <th scope="col" class="text-center">User</th>
		                    <th scope="col" class="text-center">Reason</th>
		                    <th scope="col" class="text-center">Date</th>
		                    <th scope="col" class="text-center">Expires</th>
		                    <th scope="col" class="text-center">Action</th>
		                  </tr>
		                </thead>
		                <tbody id="banned-users">



		                </tbody>
		                
		              </table>
		            </div>
				</div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
	        
	      </div>
	    </div>
	  </div>
	</div>
<?php include 'footer.php'; ?>