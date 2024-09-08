<?php
$pagename = "News Manager";
include 'header.php';
?>

	<div class="main">
    	<div class="container mt-5 mb-5 px-4">
      		<div class="row g-4">
      			<div class="col-md-4">
      				<div class="card plan-card">
			          <div class="card-header">
			            Add News
			          </div>
			          <div class="card-body">
			          	<div class="row">
			          		<div class="col-md-12">
			          			<div class="mb-3">
								    <label for="newstitle" class="form-label">Title</label>
									<input type="text" class="form-control" placeholder="Title" id="newstitle" required>
			                    </div>
			                    <div class="mb-3">
								    <label for="newsdesc" class="form-label">Description</label>
									<textarea class="form-control" id="newsdesc" placeholder="" style="height: 100px"></textarea>
			                    </div>
			                    <div class="mb-3">
								    <label for="newsicon" class="form-label">Icon</label>
								    <select class="form-select" id="newsicon">
								    	<option selected>Select icon</option>
									  	<option value="fire">Fire</option>
										<option value="heart">Heart</option>
										<option value="bolt">Bolt</option>
										<option value="database">Database</option>
										<option value="adjust">Adjust</option>
										<option value="exclamation">Exclamation</option>
			                        	<option value="template">Template</option>
			                        	<option value="ban">Ban</option>
			                        	<option value="dollar">Dollar</option>
			                        </select>
								</div>
								<div class="mb-3">
								    <button type="button" onclick="AddNews()" id="createpbtn" class="btn btn-vnm-indigo w-100 py-2">
					  					<span id="createp_def"><i class="fa-solid fa-folder-plus"></i>
									 	 Add New
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
      			<div class="col-md-8">
      				<div class="card plan-card">
			          <div class="card-header">
			            All News
			          </div>
			          <div class="card-body">
			          	<div class="table-responsive mx-2">
			              <table id="news-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
			                <thead>
			                  <tr>
			                    
			                    <th scope="col" class="text-center">Title</th>
			                    <th scope="col" class="text-center">Icon</th>
			                    <th scope="col" class="text-center">Created At</th>
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




<?php include 'footer.php'; ?>