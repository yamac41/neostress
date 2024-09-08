<?php 
  $pagename = "Dashboard";
  include '../header.php'; 

  $concs = $plan['concs']+$user['addon_concs'];
  $atime = $plan['time']+$user['addon_time'];
?>

<div class="p-4 lg:px-24 text-white page-cont">
	<div class="welcome-header">
    	Profile
	</div>
	<div class="welcome-subtitle">
		You can manage your profile here.<br>Empowering you with control and convenience.
  	</div>

	<div class="grid grid-cols-3 gap-3 mt-8">
		<div class="card p-4">
			<div class="flex flex-column align-items-center text-center">
				<button type="button" class="flex text-sm bg-gray-800 rounded-full gradient-avatar focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
					<div class="rounded-full user-pfp2" alt="user photo">
						<?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
					</div>
				</button>
				<div class="mt-3">
					<h6><?= $_SESSION['username']?></h6>
					<span class="vnm-indigo-badge px-2 py-1 rounded-lg"><?php echo $plan['name']; ?></span>
				</div>
			</div>
			<div class="mt-4">
		        <ul class="profile-plan list-group list-group-flush d-inline text-start">
		           	<li class="list-group-item">
						<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
						</svg> 
						<span>
							<strong>Concurrents</strong>
						</span>
						<br>
						<?php echo $concs; ?>
					</li>
		        	<li class="list-group-item">
				  		<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
						<span>
							<strong>Attack Time</strong>
						</span>
						<br>
						<?php echo $atime; ?>
					</li>
		            <li class="list-group-item">
						<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
						</svg>
						<span>
							<strong>Expire</strong>
						</span>
						<br>
						<?php echo $expire; ?>
					</li>
		         </ul>
		    </div>
		</div>
		<div class="card p-4 col-span-2 max-xl:mt-3">
			<ul class="text-sm font-medium text-center text-gray-500 rounded-lg flex dark:text-gray-400 mb-2" id="fullWidthTab" data-tabs-toggle="#fullWidthTabContent" role="tablist">
				<li class="mr-3">
					<button id="account-tab" data-tabs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true" class="inline-block px-4 p-2">
						Account
					</button>
				</li>
				<li>
					<button id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false" class="inline-block px-4 p-2">
						Settings
					</button>
				</li>
				<li>
					<button id="delete-tab" data-tabs-target="#delete" type="button" role="tab" aria-controls="delete" aria-selected="false" class="inline-block px-4 p-2">
						Delete Account
					</button>
				</li>
			</ul>
			
			<div id="fullWidthTabContent" class="border-t border-gray-200 dark:border-gray-600">
				<div class="hidden pt-2" id="account" role="tabpanel" aria-labelledby="account-tab">
					<div class="col-md-12">
						<div class="card-body py-0">
							<div class="mt-2">
								<div class="py-1 fs-6">
									<div class="flex flex-center">
										<div class="symbol symbol-40px me-2">
											<span class="svg-icon svg-icon-muted svg-icon-2hx">
											<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z" fill="currentColor"></path>
												<path d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z" fill="currentColor"></path>
												<rect x="7" y="6" width="4" height="4" rx="2" fill="currentColor"></rect>
											</svg>
											</span>
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="fw-bolder text-color-dark opacity-75 first-upperchase">Username:</div>
											<div class="text-gray-600">
											<span class="badge badge-light-primary"><?=$_SESSION['username']?></span>
											</div>
										</div>
									</div>
									<div class="d-flex flex-center mt-4">
										<div class="symbol symbol-40px me-2">
											<span class="svg-icon svg-icon-muted svg-icon-2hx">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M4.425 20.525C2.525 18.625 2.525 15.525 4.425 13.525L14.825 3.125C16.325 1.625 18.825 1.625 20.425 3.125C20.825 3.525 20.825 4.12502 20.425 4.52502C20.025 4.92502 19.425 4.92502 19.025 4.52502C18.225 3.72502 17.025 3.72502 16.225 4.52502L5.82499 14.925C4.62499 16.125 4.62499 17.925 5.82499 19.125C7.02499 20.325 8.82501 20.325 10.025 19.125L18.425 10.725C18.825 10.325 19.425 10.325 19.825 10.725C20.225 11.125 20.225 11.725 19.825 12.125L11.425 20.525C9.525 22.425 6.425 22.425 4.425 20.525Z" fill="currentColor"></path>
												<path d="M9.32499 15.625C8.12499 14.425 8.12499 12.625 9.32499 11.425L14.225 6.52498C14.625 6.12498 15.225 6.12498 15.625 6.52498C16.025 6.92498 16.025 7.525 15.625 7.925L10.725 12.8249C10.325 13.2249 10.325 13.8249 10.725 14.2249C11.125 14.6249 11.725 14.6249 12.125 14.2249L19.125 7.22493C19.525 6.82493 19.725 6.425 19.725 5.925C19.725 5.325 19.525 4.825 19.125 4.425C18.725 4.025 18.725 3.42498 19.125 3.02498C19.525 2.62498 20.125 2.62498 20.525 3.02498C21.325 3.82498 21.725 4.825 21.725 5.925C21.725 6.925 21.325 7.82498 20.525 8.52498L13.525 15.525C12.325 16.725 10.525 16.725 9.32499 15.625Z" fill="currentColor"></path>
											</svg>
											</span>
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="fw-bolder text-color-dark opacity-75 first-upperchase">Email:</div>
											<div class="text-gray-600">
											<span class="text-gray-600 text-hover-primary">
												<span class="badge badge-light-primary"><?=$user['email']?></span>
											</span>
											</div>
										</div>
									</div>
									<div class="d-flex flex-center mt-4">
										<div class="symbol symbol-40px me-2">
											<span class="svg-icon svg-icon-muted svg-icon-2hx">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
												<rect x="11" y="17" width="7" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"></rect>
												<rect x="11" y="9" width="2" height="2" rx="1" transform="rotate(-90 11 9)" fill="currentColor"></rect>
											</svg>
											</span>
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="fw-bolder text-color-dark opacity-75 first-upperchase">Secret key:</div>
											<div class="text-gray-600">
											<span class="badge badge-light-primary">
												<span class="spoiler" id="secretkey"><?=$user['secretkey']?></span>
											</span>
											</div>
										</div>
									</div>
									<div class="d-flex flex-center mt-4">
										<div class="symbol symbol-40px me-2">
											<span class="svg-icon svg-icon-muted svg-icon-2hx">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M3.20001 5.91897L16.9 3.01895C17.4 2.91895 18 3.219 18.1 3.819L19.2 9.01895L3.20001 5.91897Z" fill="currentColor"></path>
												<path opacity="0.3" d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21C21.6 10.9189 22 11.3189 22 11.9189V15.9189C22 16.5189 21.6 16.9189 21 16.9189H16C14.3 16.9189 13 15.6189 13 13.9189ZM16 12.4189C15.2 12.4189 14.5 13.1189 14.5 13.9189C14.5 14.7189 15.2 15.4189 16 15.4189C16.8 15.4189 17.5 14.7189 17.5 13.9189C17.5 13.1189 16.8 12.4189 16 12.4189Z" fill="currentColor"></path>
												<path d="M13 13.9189C13 12.2189 14.3 10.9189 16 10.9189H21V7.91895C21 6.81895 20.1 5.91895 19 5.91895H3C2.4 5.91895 2 6.31895 2 6.91895V20.9189C2 21.5189 2.4 21.9189 3 21.9189H19C20.1 21.9189 21 21.0189 21 19.9189V16.9189H16C14.3 16.9189 13 15.6189 13 13.9189Z" fill="currentColor"></path>
											</svg>
											</span>
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="fw-bolder text-color-dark opacity-75 first-upperchase">Balance:</div>
											<div class="text-gray-600">
											<span class="badge badge-light-primary"><?=$user['balance']?>$</span>
											</div>
										</div>
									</div>
									<div class="d-flex flex-center mt-4">
										<div class="symbol symbol-40px me-2">
											<span class="svg-icon svg-icon-muted svg-icon-2hx">
											<svg width="14" height="21" viewBox="0 0 14 21" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path opacity="0.3" d="M12 6.20001V1.20001H2V6.20001C2 6.50001 2.1 6.70001 2.3 6.90001L5.6 10.2L2.3 13.5C2.1 13.7 2 13.9 2 14.2V19.2H12V14.2C12 13.9 11.9 13.7 11.7 13.5L8.4 10.2L11.7 6.90001C11.9 6.70001 12 6.50001 12 6.20001Z" fill="currentColor"></path>
												<path d="M13 2.20001H1C0.4 2.20001 0 1.80001 0 1.20001C0 0.600012 0.4 0.200012 1 0.200012H13C13.6 0.200012 14 0.600012 14 1.20001C14 1.80001 13.6 2.20001 13 2.20001ZM13 18.2H10V16.2L7.7 13.9C7.3 13.5 6.7 13.5 6.3 13.9L4 16.2V18.2H1C0.4 18.2 0 18.6 0 19.2C0 19.8 0.4 20.2 1 20.2H13C13.6 20.2 14 19.8 14 19.2C14 18.6 13.6 18.2 13 18.2ZM4.4 6.20001L6.3 8.10001C6.7 8.50001 7.3 8.50001 7.7 8.10001L9.6 6.20001H4.4Z" fill="currentColor"></path>
											</svg>
											</span>
										</div>
										<div class="d-flex justify-content-between flex-grow-1">
											<div class="fw-bolder text-color-dark opacity-75 first-upperchase">Expire:</div>
											<div class="text-gray-600">
											<span class="badge badge-light-primary"><?=$expire?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="hidden pt-2" id="settings" role="tabpanel" aria-labelledby="settings-tab">
					<div class="col-md-6 mt-3">
						<div class="mb-3">
						    <label for="currpassword" class="form-label">Current Password</label><br>
						    <input type="password" class="w-full form-control mt-1" placeholder="Current password" id="currpassword" required>
						</div>
						<div class="mb-3">
						    <label for="npassword" class="form-label">New Password</label><br>
						    <input type="password" class="w-full form-control mt-1" placeholder="New password" id="npassword" required>
						    
						</div>
						<div class="mb-3">
						    <label for="cpassword" class="form-label">Confirm Password</label><br>
						    <input type="password" class="w-full form-control mt-1" placeholder="Current password" id="cpassword" required>
						</div>
						<button type="button" onclick="ChangePass()" id="savepassbtn" class="btn btn-r2 w-full">
							<span id="savepass_def"><i class="fa-solid fa-floppy-disk"></i>
						 	 Save
						 	</span>
                    		<span id="savepass_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                    		 Please wait..
                    		</span>
						</button>
					</div>
				</div>
				<div class="hidden pt-2" id="delete" role="tabpanel" aria-labelledby="delete-tab">
					<div class="col-md-6 mt-3">
						<div class="mb-3">
						    <h6>Delete your account</h6>
						    <p>Your account can not be recovered in any way, so please be careful.</p>
						    <div class="flex">
								<button type="button" onclick="DeleteAcc()" id="deleteaccbtn" class="btn btn-r1 mt-1">
									<span id="delete_def"><i class="fa-solid fa-trash-can"></i>
									Delete
									</span>
									<span id="delete_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									Please wait..
									</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include '../footer.php'; ?>