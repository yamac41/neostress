<?php 
  $pagename = "Deposit";
  include '../header.php'; 
?>

<div class="p-4 lg:px-24 text-white page-cont">
	<div class="grid justify-center">
		<div class="welcome-header">
    		Deposit
		</div>
		<div class="welcome-subtitle">
			Flexible deposits.<br>Securely add funds with multiple cryptocurrencies and payment options.
  		</div>
		<div class="w-full mt-3">
			<div class="card" style="max-width: 870px;">
				<div class="grid grid-cols-6 gap-3 mt-8">
					<div class="col-span-4 p-6">
						<div id="crypto_create">
							<p class="crypto-header">Crypto Processing - Checkout</p>
							<p class="crypto-desc">Choose a cryptocurrency in which you will make payment, specify the amount to be deposited and a refund address</p>

							<div class="mb-3 mt-6">
								<label for="gateway" class="form-label">Cryptocurrency</label><br>
								<select class="form-select w-full mt-1" id="gateway">
								<option value="0" selected>Select payment gateway</option>
								<option value="BITCOIN">Bitcoin</option>
								<option value="BITCOIN_CASH">Bitcoin Cash</option>
								<option value="ETHEREUM">Ethereum</option>
								<option value="LITECOIN">Litecoin</option>
								<option value="USDT:ERC20">USDT:ERC20</option>
								<option value="USDT:TRC20">USDT:TRC20</option>
								<option value="TRON">TRON</option>
								<option value="SOLANA">Solana</option>
								</select>
							</div>
							<div class="mb-3">
								<label for="depamount" class="form-label">Amount</label><br>
								<input type="text" class="form-control mt-1 w-full" placeholder="10$" id="depamount" required>	    
							</div>
							<div class="mt-8">
								<button type="button" onclick="Deposit()" id="depbtn" class="btn btn-gradient1 w-full">
									<span id="dep_def"><i class="fa-solid fa-coins"></i>
									Proceed to payment
									</span>
									<span id="dep_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									Please wait..
									</span>
								</button>
							</div>
						</div>
						<div id="crypto_waiting" style="display: none;">
							<p class="crypto-header">n/a</p>
							<p class="crypto-desc">Send the specified amount to the wallet address below.</p>

							<div class="crypto-border mt-5">
								<div class="flex items-center space-x-4">
    	                            <div class="flex-shrink-0 pl-3">
    	                                <img id="qrimage" src="https://api.qrserver.com/v1/create-qr-code/?size=123x123&data=bitcoin:bc1q76wg77ag09v0nwdypqcaa27pp8t62g6je7gg6h?amount=0.00051310" alt="Crypto QR">
    	                            </div>
    	                            <div class="flex-1 min-w-0">
    	                                <div>
    	                                    <p>
												<div class="mb-3 pr-3">
													<label for="processing_addy" class="form-label">Deposit Address</label><br>
													<div class="input-group mb-3 crypto-detail">
														<input type="text" class="form-control mt-1 mw-full" disabled id="processing_addy">
														<button class="btn btn-vnm-indigo" type="button" onclick="copyaddr()" id="button-addr">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
																<path d="M5 2H9.73333C11.2268 2 11.9735 2 12.544 2.29065C13.0457 2.54631 13.4537 2.95426 13.7094 3.45603C14 4.02646 14 4.77319 14 6.26667V11M4.13333 14H9.53333C10.2801 14 10.6534 14 10.9387 13.8547C11.1895 13.7268 11.3935 13.5229 11.5213 13.272C11.6667 12.9868 11.6667 12.6134 11.6667 11.8667V6.46667C11.6667 5.71993 11.6667 5.34656 11.5213 5.06135C11.3935 4.81046 11.1895 4.60649 10.9387 4.47866C10.6534 4.33333 10.2801 4.33333 9.53333 4.33333H4.13333C3.3866 4.33333 3.01323 4.33333 2.72801 4.47866C2.47713 4.60649 2.27316 4.81046 2.14532 5.06135C2 5.34656 2 5.71993 2 6.46667V11.8667C2 12.6134 2 12.9868 2.14532 13.272C2.27316 13.5229 2.47713 13.7268 2.72801 13.8547C3.01323 14 3.3866 14 4.13333 14Z" stroke="#96A6C6" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
															</svg>
														</button>
													</div>  
												</div>
    	                                    </p>
    	                                    <p>
												<div class="mb-3 pr-3">
													<label for="processing_amount" class="form-label">Amount</label><br>
													<div class="input-group mb-3 crypto-detail">
														<input type="text" class="form-control mt-1 mw-full" disabled id="processing_amount">
														<button class="btn btn-vnm-indigo" type="button" onclick="copyamount()" id="button-addr">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
																<path d="M5 2H9.73333C11.2268 2 11.9735 2 12.544 2.29065C13.0457 2.54631 13.4537 2.95426 13.7094 3.45603C14 4.02646 14 4.77319 14 6.26667V11M4.13333 14H9.53333C10.2801 14 10.6534 14 10.9387 13.8547C11.1895 13.7268 11.3935 13.5229 11.5213 13.272C11.6667 12.9868 11.6667 12.6134 11.6667 11.8667V6.46667C11.6667 5.71993 11.6667 5.34656 11.5213 5.06135C11.3935 4.81046 11.1895 4.60649 10.9387 4.47866C10.6534 4.33333 10.2801 4.33333 9.53333 4.33333H4.13333C3.3866 4.33333 3.01323 4.33333 2.72801 4.47866C2.47713 4.60649 2.27316 4.81046 2.14532 5.06135C2 5.34656 2 5.71993 2 6.46667V11.8667C2 12.6134 2 12.9868 2.14532 13.272C2.27316 13.5229 2.47713 13.7268 2.72801 13.8547C3.01323 14 3.3866 14 4.13333 14Z" stroke="#96A6C6" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
															</svg>
														</button>
													</div>
												</div>
    	                                    </p>
										</div>
    	                            </div>
    	                        </div>
							</div>
							<div class="mt-3">
								<p id="crypto-status">
									We are looking for your payment..
								</p>
								<p class="crypto-expire">
									This payment will expire at <span id="crypto-expire-container">N/A</span>
								</p>
								<div class="mt-5">
									<div class="grid grid-cols-2">
										<div class="mr-4">
											<label for="processing_paid" class="form-label">Amount paid</label><br>
											<input type="text" class="form-control mt-1" disabled id="processing_paid">
										</div>
										<div>
											<label for="processing_conf" class="form-label">Confirmations</label><br>
											<input type="text" class="form-control mt-1" disabled id="processing_conf">	
										</div>
									</div>
								</div>
							</div>
							<div class="mt-5 flex justify-center">
								<button type="button" class="px-2 btn btn-r1 py-2 mr-3" id="cancel-btn">
									<span id="cancelpay_def"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i>
									Cancel Payment
									</span>
									<span id="cancelpay_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									Please wait..
									</span>

								</button>
								<button type="button" class="px-2 btn btn-r2 py-2" id="recheck-btn">
									<span id="recheck_def"><i class="fa-solid fa-arrows-rotate"></i>
									Re-Check
									</span>
									<span id="recheck_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									Please wait..
									</span>


								</button>
							</div>
						</div>
					</div>
					<div class="flex col-span-2 w-full justify-end">
						<div class="crypto-bg"></div>
					</div>
				</div>
			</div>
			<div class="mt-8">
		  		<div class="card rounded-lg billing-div g-4" style="max-width: 870px;">
				  	<div class="card-header">
						<div class="p-3">
							Payment history
						</div>
					</div>

		  			<div class="card-container p-3">
		  				<div>
		  					<div class="table-responsive mx-2">
			                  <table id="billing-table" style="width:100%" class="mt-2 stripe display table bg-gray-800 table-striped table-bordered border-gray">
			                    <thead>
			                      <tr>
			                      	<th scope="col" class="text-start">ID</th>
			                        <th scope="col" class="text-center">Type</th>
			                        <th scope="col" class="text-center">Amount</th>
			                       	<th scope="col" class="text-center">Status</th>
			                        <th scope="col" class="text-center">Date</th>
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
</div>
  	
<?php include '../footer.php'; ?>