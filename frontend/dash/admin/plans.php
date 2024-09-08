<?php
$pagename = "Plans Manager";
include 'header.php';
?>


  	<div class="main">
    	<div class="container mt-5 mb-5 px-4">
      		<div class="row g-4">
      			<div class="col-md-6">
      				<div class="card plan-card">
			          <div class="card-header">
			            <div class="row">
			            	<div class="col-md-6 text-start"> Create Plan</div>
			            	<div class="col-md-6 text-end">
								<button type="button" onclick="IncreaseExpModal()" style="text-transform: uppercase; font-size: 0.8rem;" id="addexpire" class="btn btn-primary btn-sm"><i class="fa-solid fa-clock-rotate-left"></i> Increase expiration to users</button>
							</div>
			            </div>
			          </div>
			          <div class="card-body">
			           	  <div class="row mt-1">
			           	  	<div class="col-md-6">
			           	  		<div class="mb-3">
								    <label for="planname" class="form-label">Name</label>
								    <input type="text" class="form-control" placeholder="Name" id="planname" required>
								    
								</div>
								<div class="mb-3">
								    <label for="planprice" class="form-label">Price</label>
								    <input type="number" class="form-control" placeholder="Price" id="planprice" required>
								    
								</div>
								<div class="mb-3">
								    <label for="planlength" class="form-label">Plan length</label>
								    <input type="number" class="form-control" placeholder="Length" id="planlength" required>
								    
								</div>
								<div class="mb-3">
								    <label for="plantype" class="form-label">Length type</label>
								    <select class="form-select" id="plantype">
								    	<option selected>Select</option>
									  	<option value="Days">Days</option>
										<option value="Weeks">Weeks</option>
                                        <option value="Months">Months</option>
										<option value="Years">Years</option>
									</select>
								</div>
								<div class="mb-3">
								    <label for="planpublic" class="form-label">Lenght on page</label>
								    <input type="text" class="form-control" placeholder="day / month" id="planpublic" required>
								    
								</div>
								<div class="row">
									<div class="col-md-6">
										<label for="planconcs" class="form-label">Concurrents</label>
									    <input type="number" class="form-control" placeholder="Concs" id="planconcs" required>
									</div>
									<div class="col-md-6">
										<label for="plantime" class="form-label">Attack Time</label>
									    <input type="number" class="form-control" placeholder="Time" id="plantime" required>
									</div>

								</div>
			           	  	</div>
			           	  	<div class="col-md-6">
			           	  		<div class="mb-3">
								    <label for="planpremium" class="form-label">Premium</label>
								    <select class="form-select" id="planpremium">
								    	<option selected>Select</option>
									  	<option value="1">Yes</option>
										<option value="0">No</option>
                                    </select>
								</div>
								<div class="mb-3">
								    <label for="planapi" class="form-label">API Access</label>
								    <select class="form-select" id="planapi">
								    	<option selected>Select</option>
									  	<option value="1">Yes</option>
										<option value="0">No</option>
                                    </select>
								</div>
								<div class="mb-3">
								    <label for="planprivate" class="form-label">Private</label>
								    <select class="form-select" id="planprivate">
								    	<option selected>Select</option>
									  	<option value="yes">Yes</option>
										<option value="no">No</option>
                                    </select>
								</div>
								<div class="mb-3">
								    <label for="plancustom" class="form-label">Custom</label>
								    <select class="form-select" id="plancustom">
								    	<option selected>Select</option>
									  	<option value="yes">Yes</option>
										<option value="no">No</option>
                                    </select>
								</div>
								<div class="mb-5">
								    <label for="planprio" class="form-label">Support Priority</label>
								    <select class="form-select" id="planprio">
								    	<option selected>Select</option>
									  	<option value="1">Yes</option>
										<option value="0">No</option>
                                    </select>
								</div>
								<div class="mb-3">
								    <button type="button" onclick="CreatePlan()" id="createpbtn" class="btn btn-vnm-indigo w-100 py-2">
					  					<span id="createp_def"><i class="fa-solid fa-folder-plus"></i>
									 	 Create
									 	</span>
                        				<span id="createp_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                        				 Please wait..
                        				</span>
									  
									</button>
								</div>

			           	  	</div>
			           	  </div>
			          </div>
			        </div>


      			</div>
      			<div class="col-md-6">
      				<div class="card plan-card">
			          <div class="card-header">
			            <div class="row">
			            	<div class="col-md-6 text-start"> All Plans</div>
			            	<div class="col-md-6 text-end">
								<button type="button" onclick="CreateCoupon()" style="text-transform: uppercase; font-size: 0.8rem;color:#fff;" id="addcoupon" class="btn btn-warning btn-sm"><i class="fa-solid fa-ticket"></i></i> Create Coupon</button>
							</div>
			            </div>
			          </div>
			          <div class="card-body">
			          	<div class="table-responsive mx-2">
			              <table id="allplans-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
			                <thead>
			                  <tr>
			                    
			                    <th scope="col" class="text-center">Name</th>
			                    <th scope="col" class="text-center">Price</th>
			                    <th scope="col" class="text-center">Time</th>
			                    <th scope="col" class="text-center">Concs</th>
			                    <th scope="col" class="text-center">Length</th>
			                    <th scope="col" class="text-center">Premium</th>
			                    <th scope="col" class="text-center">API</th>
			                    <th scope="col" class="text-center">Private</th>
			                    <th scope="col" class="text-center">Users</th>
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


    <div class="modal fade" id="expire-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content bg-gray-800">
	      <div class="modal-header">
	        Increase Expiration
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <div class="row">
	        	<div class="col-md-12">
	        		<div class="mb-3">
					    <label for="explength" class="form-label">Length</label>
						<input type="number" class="form-control" placeholder="Please input length" id="explength" required>
                        
					</div>
					<div class="mb-3">
					    <label for="expunit" class="form-label">Unit</label>
					    <select class="form-select" id="expunit">
					    	<option selected>Select unit</option>
						  	<option value="Minutes">Minutes</option>
							<option value="Hours">Hours</option>
							<option value="Days">Days</option>
							<option value="Weeks">Weeks</option>
							<option value="Months">Months</option>
							<option value="Years">Years</option>
                        </select>
					</div>




	        	</div>

	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-vnm-red" data-bs-dismiss="modal"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
	        <button type="button" class="btn btn-vnm-indigo" onclick="IncreaseExp()"><i class="fa-solid fa-calendar-plus"></i> Increase</button>
	      </div>
	    </div>
	  </div>
	</div>





<?php include 'footer.php'; ?>