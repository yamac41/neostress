<?php
$pagename = "Admin Dashboard";
include 'header.php';
?>
  <div class="main">
    <div class="container mt-5 mb-5 px-4">
      <div class="row g-4">
        
        <div class="col-md-3">
          <div class="card stats-card1 h-100 py-2 shadow bg-gray-800">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <p class="text-uppercase mb-1">
                  Earnings (Daily)</p>
                  <h5 class="mb-0 font-weight-bold text-white">
                    <?php   
                      $TodayEarn = $odb -> query("SELECT SUM(`amount`) as total FROM `payments` WHERE DAY(FROM_UNIXTIME(`created_at`)) = DAY(NOW()) AND MONTH(FROM_UNIXTIME(`created_at`)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(`created_at`)) = YEAR(NOW()) AND `status` = 'COMPLETED'");
                      $today = $TodayEarn -> fetchColumn(0);

                      echo '$'.($today ? $today : "0").'';
                    ?>


                  </h5>
                </div>
                <div class="col-auto">
                  <i class="fa-solid fa-calendar-day fa-2x"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      <div class="col-md-3">
        <div class="card stats-card2 h-100 py-2 shadow bg-gray-800">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <p class="text-uppercase mb-1">
                Earnings (Monthly)</p>
                <h5 class="mb-0 font-weight-bold text-white">
                  <?php   
                    $MonthlyEarn = $odb -> query("SELECT SUM(`amount`) as total FROM `payments` WHERE MONTH(FROM_UNIXTIME(`created_at`)) = MONTH(NOW()) AND YEAR(FROM_UNIXTIME(`created_at`)) = YEAR(NOW())  AND `status` = 'COMPLETED'");
                    $monthly = $MonthlyEarn -> fetchColumn(0);

                    echo '$'.($monthly ? $monthly : "0").'';
                  ?>
                    
                  </h5>
              </div>
              <div class="col-auto">
                <i class="fa-solid fa-calendar-days fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stats-card3 h-100 py-2 shadow bg-gray-800">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <p class="text-uppercase mb-1">
                Earnings (Annualy)</p>
                <h5 class="mb-0 font-weight-bold text-white">
                  <?php   
                    $AnnualyEarn = $odb -> query("SELECT SUM(`amount`) as total FROM `payments` WHERE YEAR(FROM_UNIXTIME(`created_at`)) = YEAR(NOW()) AND `status` = 'COMPLETED'");
                    $annualy = $AnnualyEarn -> fetchColumn(0);

                    echo '$'.($annualy ? $annualy : "0").'';
                  ?>



                </h5>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-3">
        <div class="card stats-card4 h-100 py-2 shadow bg-gray-800">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <p class="text-uppercase mb-1">
                Active Memberships</p>
                <h5 class="mb-0 font-weight-bold text-white">
                  <?php   
                    $ActiveMembers = $odb -> query("SELECT COUNT(*) FROM `users` WHERE `planexpire` > UNIX_TIMESTAMP() AND `plan` != '0'");
                    $members = $ActiveMembers -> fetchColumn(0);

                    echo $members;
                  ?>


                </h5>
              </div>
              <div class="col-auto">
                <i class="fa-solid fa-chart-line fa-2x"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="card active-tickets">
          <div class="card-header">
            Active Tickets
          </div>
          <div class="card-body">
            <div class="table-responsive mx-2">
              <table id="activetickets-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
                <thead>
                  <tr>
                    <th scope="col" class="text-start">ID</th>
                    <th scope="col" class="text-center">Title</th>
                    <th scope="col" class="text-center">Priority</th>
                    <th scope="col" class="text-center">User</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card active-tickets">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6 text-start">
                Lastest Payments
              </div>
              <div class="col-md-6 text-end">
                <button type="button" onclick="AddInvoice()" style="text-transform: uppercase; font-size: 0.8rem;" class="btn btn-primary btn-sm"><i class="fa-solid fa-file-invoice-dollar"></i> Add Invoice</button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive mx-2">
              <table id="lastpayments-table" data-page-length='7' style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
                <thead>
                  <tr>
                    <th scope="col" class="text-start">#</th>
                    <th scope="col" class="text-start">User</th>
                    <th scope="col" class="text-center">Amount</th>
                    <th scope="col" class="text-center">Date</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card active-tickets">
          <div class="card-header">
            Plan Purchases
          </div>
          <div class="card-body">
            <div class="table-responsive mx-2">
              <table id="planpurchases-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
                <thead>
                  <tr>
                    
                    <th scope="col" class="text-start">User</th>
                    <th scope="col" class="text-center">Plan</th>
                    <th scope="col" class="text-center">Amount</th>
                    <th scope="col" class="text-center">Date</th>
                    
                  </tr>
                </thead>
                
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card active-tickets">
          <div class="card-header">
            Add-on Purchases
          </div>
          <div class="card-body">
            <div class="table-responsive mx-2">
              <table id="addonspurchases-table" style="width:100%" class="mt-2 stripe display table bg-grayy-800 table-striped table-bordered border-gray">
                <thead>
                  <tr>
                    
                    <th scope="col" class="text-start">User</th>
                    <th scope="col" class="text-center">Concs</th>
                    <th scope="col" class="text-center">Attack Time</th>
                    <th scope="col" class="text-center">Blacklist</th>
                    <th scope="col" class="text-center">API Access</th>
                    <th scope="col" class="text-center">Date</th>
                    
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


  <div class="modal fade" id="viewticket-modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
      <div class="modal-content bg-gray-900">
        <div class="modal-header">
          <span id="ticket-label"></span>
          <button type="button" style="background: transparent;border: none;" data-bs-dismiss="modal" aria-label="Close"><i style="color: #fff;" class="fa-solid fa-x"></i></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-md-7">
              <div id="ticket-content">

              </div>
            </div>
            <div class="col-md-5">
              <textarea class="form-control" id="replyarea" placeholder="Leave your reply here.." style="height: 350px"></textarea>
        </div>
      </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-vnm-red" id="closeticket-btn"><i class="fa-solid fa-xmark" style="color: #fff !important;"></i> Close Ticket</button>
          <button type="button" class="btn btn-vnm-indigo" id="reply-btn"><i class="fa-solid fa-reply"></i> Reply</button>
        </div>
      </div>
    </div>
  </div>
<?php include 'footer.php' ?>