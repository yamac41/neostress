<?php 
  $pagename = "Purchase";
  include '../header.php'; 
?>

<div class="p-4 lg:px-24 text-white page-cont">
	<div class="welcome-header">
    Purchase
	</div>
	<div class="welcome-subtitle">
		Purchase with confidence.<br>Seamless transactions and exceptional service.
  </div>

  <div class="grid grid-cols-12 gap-4 mt-8">
    <?php
      $SQLPlans = $odb -> query("SELECT * FROM `plans` WHERE `private` = 'no' ORDER BY `id` ASC");
      while ($plan = $SQLPlans -> fetch(PDO::FETCH_ASSOC)){
        $id = $plan['id'];
        $name = $plan['name'];
        $price = $plan['price'];
        $length = $plan['length'];
        $lengthtype = $plan['pagelength'];
        
        $concs = $plan['concs'];
        $time = $plan['time'];
        $premium = $plan['premium'];
        $apiaccess = $plan['apiaccess'];
        $support = $plan['supportprio'];

        $checkSVG = '
        <svg style="color: #75cc68;" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
          <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"></path>
        </svg>';

        $uncheckSVG = ' 
        <svg style="color: #565674;" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
          <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"></rect>
          <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"></rect>
        </svg>';

        if($premium == 0){
          $premiumtext = $uncheckSVG;
        }else if($premium == 1){
          $premiumtext = $checkSVG;
        }
        if($apiaccess == 0){
          $apitext = $uncheckSVG;
        }else if($apiaccess == 1){
          $apitext = $checkSVG;
        }
        if($support == 0){
          $supptext = $uncheckSVG;
        }else if($support == 1){
          $supptext = $checkSVG;
        }

        if($length == 1){
          $lenghtt = '1 day';
        }else if($length >= 30){
          $lenghtt = '1 month';
        }

        echo '
          <div class="col-span-4">
            <div class="rounded-lg purchase-card p-3 px-16">
              <h5 class="text-center">'.$name.'</h5>
              <div class="purchase-price text-center mb-4">
                <span class="pricing-currency">$</span>
                <span class="pricing-price">'.$price.'</span>
                <span class="pricing-time">/ '.strtolower($lengthtype).'</span>
              </div>
              <div class="items-center justify-between flex">
                <div>
                  <span>'.$concs.' concurrents</span>
                </div>
                <div>
                '.$checkSVG.'
                </div>
              </div>
              <div class="items-center justify-between flex">
                <div>
                  <span>'.$time.' seconds</span>
                </div>
                <div>
                  '.$checkSVG.'
                </div>
              </div>
              <div class="items-center justify-between flex">
                <div>
                  Premium network
                </div>
                <div>
                  '.$premiumtext.'
                </div>
              </div>
              <div class="items-center justify-between flex">
                <div>
                  API access
                </div>
                <div>
                  '.$apitext.'
                </div>
              </div>
              <div class="items-center justify-between flex">
                <div>
                Prioritized support
                </div>
                <div>
                  '.$supptext.'
                </div>
              </div>
              <div class="inline-flex items-center justify-center w-full">
                <div>
                  <button type="button" name="'.$name.'" id="'.$id.'" onclick="PurchasePlan(this)" id="purchase-btn" class="text-base mt-4 btn-gradient1 rounded-lg text-center inline-flex items-center justify-center w-full py-1">
                    <span id="purcp_def"><i class="fa-solid fa-basket-shopping"></i>
                  Purchase
                  </span>
                        <span id="purcp_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                        Please wait..
                        </span>
                  </button>
                </div>
              </div>
            </div>
          </div>';
      }

    ?>
             <div class="col-span-4">
            <div class="rounded-lg purchase-card p-3 px-16">
              <h5 class="text-center">Custom plan</h5>
              <div class="purchase-price text-center mb-4">
                <span class="pricing-currency">$</span>
                <span class="pricing-price" id="customprice"></span>
                <span class="pricing-time">/ month</span>
              </div>
              <div class="items-center justify-between flex">
                <ul>
                 <li>
                   <div class="range-slider">
                     <span for="customconcs" class="plan-spec" style="margin-left: 0 !important;">Concurrents</span>
                     <input id="customconcs" class="range-slider__range" type="range" value="1" min="1" max="30">
                     <span id="customconcsspan" class="range-slider__value">0</span>
                   </div>
                 </li>
                 <li>
                   <div class="range-slider">
                     <span for="customtime" class="plan-spec" style="margin-left: 0 !important;">Attack Time</span>
                     <input id="customtime" class="range-slider__range" type="range" value="1" min="1" max="12">
                     <span id="customtimespan" class="range-slider__value">0</span>
                   </div>
                   
                 </li>
                 
                 <li>
                   <div class="range-slider">
                     <span for="customperiod" class="plan-spec" style="margin-left: 0 !important;">Period (months)</span>
                     <input id="customperiod" class="range-slider__range" type="range" value="1" min="1" max="12">
                     <span id="customperiodspan" class="range-slider__value">0</span>
                   </div>
                 </li>
                 <li>
                   <span for="custompremium" class="plan-spec" style="margin-left: 0 !important;">Premium Methods</span><br>
                   <select class="form-select w-full" id="custompremium" onclick="custompremium_upd()">
                     <option selected value="0">No</option>
                     <option value='1'>Yes</option>
                   </select>
                 </li>
                 <li>
                   <span for="customapi" class="plan-spec" style="margin-left: 0 !important;">API Access</span><br>
                   <select class="form-select w-full" id="customapi" onclick="customapi_upd()">
                     <option selected value="0">No</option>
                     <option value='1'>Yes</option>
                   </select>
                 </li>
                 
                </ul>
              </div>
              <div class="inline-flex items-center justify-center w-full">
                <div>
                  <button type="button" onclick="PurchaseCustomPlan()" id="purchase-btn" class="text-base mt-4 btn-gradient1 rounded-lg text-center inline-flex items-center justify-center w-full py-1">
                    <span id="purcp_def"><i class="fa-solid fa-basket-shopping"></i>
                  Purchase
                  </span>
                        <span id="purcp_loadi" style="display: none;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                        Please wait..
                        </span>
                  </button>
                </div>
              </div>
            </div>
          </div>
</div>

<div class="modal fade" id="purchase-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg-gray-800">
      <div class="modal-header">
        <p class="modal-title" id="purchasemodal-label"></p>
      </div>
      <div class="modal-body">
        <ul class="profile-plan list-group list-group-flush d-inline text-start">
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> <span>Concurrents</span><br><b id="planconcs"></b></li>
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span>Attack Time</span><br><b id="planatime"></b></li>
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <span>Plan Length</span><br><b id="planlength"></b></li>
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg> <span>Premium</span><br><b id="planpremium"></b></li>
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <span>API Access</span><br><b id="planapi"></b></li>
          <li class="list-group-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span>Total Price</span><br><b id="planprice"></b></li>
        </ul>
      </div>
      <div class="modal-footer px-3">
        <div class="grid grid-cols-2 gap-4 mt-2">
          <div>
            <div class="input-group flex-nowrap mb-2">
              <input type="text" class="form-control" placeholder="Coupon code" aria-label="Coupon" id="couponcode2" aria-describedby="addon-wrapping">
            </div>
          </div>
          <div>
            <div class="d-grid gap-2 flex justify-content-end">
              <button type="button" class="btn btn-r1 py-2" id="cancelpurchase-btn" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Cancel</button>
              <button type="button" class="btn btn-r2 py-2" id="purchase-btnnnn"><i class="fa-solid fa-cash-register"></i> Purchase</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>