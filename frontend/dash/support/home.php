<?php
$pagename = "Support Dashboard";
include 'header.php';
?>
  <div class="main">
    <div class="container mt-5 mb-5 px-4">
      <div class="row g-4">
        
      <div class="col-md-12">
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