// PRELOADER ANIMATION
$("#preloader").fadeOut(1200);

GetActiveTickets();
AllTickets();




function GetActiveTickets(){
  $('#activetickets-table').DataTable({
      'processing': true,
      'serverSide': true,
      'bFilter': false,
      'sPaginationType' : 'full_numbers',
      'serverMethod': 'post',
      'responsive': true,
      "language": {
        "emptyTable": "No active tickets"
      },
      'ajax': {
          'url':'rest/tickets/get',
          'type':'POST'
      },
      'columnDefs': [
        { 'className': 'text-center', 'targets': [1, 2, 3, 4] },
       
      ],
      "bDestroy": true,
      'columns': [
        { data: 'id' },
        { data: 'title' },
        { data: 'priority' },
        { data: 'user' },
        { data: 'action' },
      ]
  });
}

function ViewTicket(id){
	var ticketid = id;

	if(ticketid == ""){
    	Toastify({text: "TicketID is empty!", duration: 1500, backgroundColor: "red"}).showToast();
    	return;
  	}

  	$('#ticket-label').text('Ticket # '+ticketid);
  	$('#viewticket-modal').modal('show');

  	$.ajax({
    	url: "rest/tickets/view",
    	type: "post",
    	data: {
      		ticketid:ticketid
    	},
    	cache: false,
	    success: function(response){
	      document.getElementById("ticket-content").innerHTML = response;
	    }
  	});

  	$('#reply-btn').attr('onclick', 'ReplyTicket('+ticketid+')');
  	$('#closeticket-btn').attr('onclick', 'CloseTicket('+ticketid+')');
}

function CloseTicket(id){
	var ticketid = id;

	if(ticketid == ""){
    	Toastify({text: "TicketID is empty!", duration: 1500, backgroundColor: "red"}).showToast();
    	return;
  	}

  	$.ajax({
    	url: "rest/tickets/close",
    	type: "post",
    	data: {
      		ticketid:ticketid
    	},
    	cache: false,
	    success: function(response){
	      const result = JSON.parse(response);
	      var status = result.status;
	      var message = result.message;

	      if(status == 'error'){
	        Toastify({text: message, duration: 1500, backgroundColor: "red"}).showToast();
	        GetActiveTickets();
	      }else{
	        Toastify({text: message, duration: 1500, backgroundColor: "green"}).showToast();
	        GetActiveTickets();
	      }
	    }
  	});
}

function ReplyTicket(id){
  var ticketid = id;

  var reply = $('#replyarea').val();
  if(ticketid == ""){
    Toastify({text: "TicketID is empty!", duration: 1500, backgroundColor: "red"}).showToast();
    return;
  }
  if(reply == ""){
    Toastify({text: "Please fill all required fields!", duration: 1500, backgroundColor: "red"}).showToast();
    return;
  }

  $.ajax({
    url: "rest/tickets/reply",
    type: "post",
    data: {
      ticketid:ticketid,
      reply:reply
    },
    cache: false,
    success: function(response){
      const result = JSON.parse(response);
      var status = result.status;
      var message = result.message;

      if(status == 'error'){
        Toastify({text: message, duration: 1500, backgroundColor: "red"}).showToast();
        GetActiveTickets();
      }else{
        Toastify({text: message, duration: 1500, backgroundColor: "green"}).showToast();
        GetActiveTickets();
        $('#viewticket-modal').modal('hide');
      }
     
    }
  });
}


function AllTickets(){
  $('#alltickets-table').DataTable({
      'processing': true,
      'serverSide': true,
      'sPaginationType' : 'full_numbers',
      'serverMethod': 'post',
      'responsive': true,
      "language": {
        "emptyTable": "No tickets"
      },
      'ajax': {
          'url':'rest/tickets/getall',
          'type':'POST'
      },
      "columnDefs": [
        { 'className': 'text-center', 'targets': [0, 1, 2, 3, 4, 5] },
      ],
      "bDestroy": true,
      'columns': [
        { data: 'user' },
        { data: 'subject' },
        { data: 'status' },
        { data: 'priority' },
        { data: 'created' },
        { data: 'action' }
      ]
  });
}


function SignOut(){
    Toastify({
      text: "You are successfully signed out. Redirecting..",
      duration: 1000,
      destination: "../login.php",
      newWindow: false,
      close: false,
      gravity: "top",
      position: "right",
      stopOnFocus: true,
      style: {
        background: "#FFC700",
      },
    }).showToast()
    setTimeout(function() {
      window.location.replace('rest/admin/logout');
    }, 1000);
    
}