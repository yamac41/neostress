$("#preloader").fadeOut(1200);
GetAttacks();
PaymentHistory();
GetTickets();
GetApiToken();
CustomPlanRange();
function PurchaseCustomPlan() {
  var _0x4e28a1 = $("#custompremium").val();
  var _0x30b6b0 = $("#customapi").val();
  var _0x495e89 = $("#customtime").val();
  var _0x15a959 = $("#customconcs").val();
  var _0x23ef27 = $("#customperiod").val();
  if (_0x4e28a1 == "" || _0x30b6b0 == "" || _0x495e89 == "" || _0x15a959 == "" || _0x23ef27 == "" || _0x495e89 == null || _0x15a959 == null || _0x23ef27 == null) {
    Toastify({
      text: "There is problem with purchasing custom plan!",
      duration: 150,
      backgroundColor: "red"
    }).showToast();
    return;
  } else {
    $.ajax({
      url: "/dash/rest/purchase/customplan/",
      type: "post",
      data: {
        premium: _0x4e28a1,
        api: _0x30b6b0,
        time: _0x495e89,
        concs: _0x15a959,
        period: _0x23ef27
      },
      cache: false,
      success: function (_0x156365) {
        const _0x2c42c5 = JSON.parse(_0x156365);
        var _0x3c828c = _0x2c42c5.status;
        var _0x356df4 = _0x2c42c5.message;
        if (_0x3c828c == "error") {
          Toastify({
            text: _0x356df4,
            duration: 1500,
            backgroundColor: "red"
          }).showToast();
        } else {
          Toastify({
            text: _0x356df4,
            duration: 1500,
            backgroundColor: "green"
          }).showToast();
          setTimeout(function () {
            window.location.href = "home";
          }, 2000);
        }
      }
    });
  }
}
function CustomPlanRange() {
  $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
  var _0xa5ea3a = $("#customtime");
  var _0x245782 = $("#customtimespan");
  var _0x2637ed = _0xa5ea3a.attr("value");
  _0x245782.html(_0x2637ed * 300);
  _0xa5ea3a.on("input", function () {
    const _0x177828 = _0xa5ea3a.val();
    _0x245782.html(_0x177828 * 300);
    if (_0x177828 > 12) {
      Toastify({
        text: "Invalid attack time value!",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    } else if (_0x177828 < 0) {
      Toastify({
        text: "Invalid attack time value!",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    }
    $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
  });
  var _0x57f978 = $("#customconcs");
  var _0x3c21fc = $("#customconcsspan");
  var _0x38484a = _0x57f978.attr("value");
  _0x3c21fc.html(_0x38484a);
  _0x57f978.on("input", function () {
    const _0x1ddf54 = _0x57f978.val();
    _0x3c21fc.html(_0x1ddf54);
    if (_0x1ddf54 > 30) {
      Toastify({
        text: "Concurrents cannot be greater then 50concs",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    } else if (_0x1ddf54 < 1) {
      Toastify({
        text: "Concurrents cannot be lower then 1conc",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    }
    $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
  });
  var _0x4a89d3 = $("#customperiod");
  var _0x4fd4ea = $("#customperiodspan");
  var _0x1425a2 = _0x4a89d3.attr("value");
  _0x4fd4ea.html(_0x1425a2);
  _0x4a89d3.on("input", function () {
    const _0x3d1f66 = _0x4a89d3.val();
    _0x4fd4ea.html(_0x3d1f66);
    if (_0x3d1f66 > 12) {
      Toastify({
        text: "Plan period cannot be more then 12 months",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    } else if (_0x3d1f66 < 1) {
      Toastify({
        text: "Plan period cannot be shorter then 12 months",
        duration: 1500,
        backgroundColor: "red"
      }).showToast();
      setTimeout(function () {
        location.reload();
      }, 2000);
    }
    $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
  });
}
;
function customapi_upd() {
  $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
}
function custompremium_upd() {
  $("#customprice").html("" + (parseInt($("#customtime").val() * 6) + (parseInt($("#customconcs").val()) * 11) + (parseInt($("#custompremium").val()) * 15) + parseInt($("#customapi").val() * 20)) * parseInt($("#customperiod").val()));
}
function DeleteSchedule(_0x9b9bab) {
  var _0x726b80 = _0x9b9bab;
  if (_0x726b80 == "") {
    Toastify({
      text: "Attack ID is empty!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $("#deletesch_def").hide();
  $("#deletesch_loadi").show();
  $("#deleteschbtn").prop("disabled", true);
  $.ajax({
    url: "/dash/rest/user/schedule?type=delete",
    type: "post",
    data: {
      shedid: _0x726b80
    },
    cache: false,
    success: function (_0x5d1f78) {
      const _0x3e9b01 = JSON.parse(_0x5d1f78);
      var _0x5daab4 = _0x3e9b01.status;
      var _0x205897 = _0x3e9b01.message;
      if (_0x5daab4 == "error") {
        Toastify({
          text: _0x205897,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#deletesch_def").show();
        $("#deletesch_loadi").hide();
        $("#deleteschbtn").prop("disabled", false);
      } else {
        Toastify({
          text: _0x205897,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        LoadSchedule();
        $("#deletesch_def").show();
        $("#deletesch_loadi").hide();
        $("#deleteschbtn").prop("disabled", false);
      }
    }
  });
}
function ScheduledAttacks() {
  $("#scheduled-modal").modal("show");
  LoadSchedule();
}
function LoadSchedule() {
  $("#scheduled-table").DataTable({
    processing: true,
    serverSide: true,
    bFilter: false,
    serverMethod: "post",
    responsive: true,
    language: {
      emptyTable: "No scheduled attacks"
    },
    ajax: {
      url: "/dash/rest/user/scheduled/",
      type: "POST"
    },
    columnDefs: [{
      className: "text-center",
      targets: [1, 2, 3, 4, 5]
    }],
    bDestroy: true,
    columns: [{
      data: "id"
    }, {
      data: "target"
    }, {
      data: "method"
    }, {
      data: "created"
    }, {
      data: "scheduled"
    }, {
      data: "action"
    }]
  });
}
function ScheduleL4Attack() {
  var _0x1ab3b4 = $("#l4hostsch").val();
  var _0x4806f9 = $("#l4timesch").val();
  var _0x442891 = $("#l4portsch").val();
  var _0x5d44b7 = $("#l4methodsch").val();
  var _0x32aeb6 = $("#l4datetimesch").val();
  $("#l4sch_def").hide();
  $("#l4sch_loadi").show();
  $("#l4btnsch").prop("disabled", true);
  if (_0x1ab3b4 == "" || _0x4806f9 == "" || _0x442891 == "" || _0x5d44b7 == "") {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    $("#l4sch_def").show();
    $("#l4sch_loadi").hide();
    $("#l4btnsch").prop("disabled", false);
    return;
  }
  $.ajax({
    url: "/dash/rest/user/schedule?type=l4",
    type: "post",
    data: {
      host: _0x1ab3b4,
      time: _0x4806f9,
      port: _0x442891,
      method: _0x5d44b7,
      datetime: _0x32aeb6
    },
    cache: false,
    success: function (_0x2e727a) {
      const _0x307647 = JSON.parse(_0x2e727a);
      var _0x2ce001 = _0x307647.status;
      var _0x2b1041 = _0x307647.message;
      if (_0x2ce001 == "error") {
        Toastify({
          text: _0x2b1041,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#l4sch_def").show();
        $("#l4sch_loadi").hide();
        $("#l4btnsch").prop("disabled", false);
      } else {
        Toastify({
          text: _0x2b1041,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        LoadSchedule();
        $("#l4sch_def").show();
        $("#l4sch_loadi").hide();
        $("#l4btnsch").prop("disabled", false);
      }
    }
  });
}
function ScheduleL7Attack() {
  var _0x45b06d = $("#l7hostsch").val();
  var _0x4efbe9 = $("#l7timesch").val();
  var _0x37e3e5 = $("#l7reqmethodsch").val();
  var _0x1697a0 = $("#l7methodsch").val();
  var _0x46fff7 = $("#l7reqssch").val();
  var _0x297b6d = $("#l7datetimesch").val();
  $("#l7sch_def").hide();
  $("#l7sch_loadi").show();
  $("#l7btnsch").prop("disabled", true);
  if (_0x45b06d == "" || _0x4efbe9 == "" || _0x37e3e5 == "" || _0x1697a0 == "") {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    $("#l7sch_def").show();
    $("#l7sch_loadi").hide();
    $("#l7btnsch").prop("disabled", false);
    return;
  }
  $.ajax({
    url: "/dash/rest/user/schedule?type=l7",
    type: "post",
    data: {
      host: _0x45b06d,
      time: _0x4efbe9,
      reqmethod: _0x37e3e5,
      method: _0x1697a0,
      reqs: _0x46fff7,
      datetime: _0x297b6d
    },
    cache: false,
    success: function (_0x5f5db5) {
      const _0x26b928 = JSON.parse(_0x5f5db5);
      var _0x3704e2 = _0x26b928.status;
      var _0x2dacc6 = _0x26b928.message;
      if (_0x3704e2 == "error") {
        Toastify({
          text: _0x2dacc6,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#l7sch_def").show();
        $("#l7sch_loadi").hide();
        $("#l7btnsch").prop("disabled", false);
      } else {
        Toastify({
          text: _0x2dacc6,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        LoadSchedule();
        $("#l7sch_def").show();
        $("#l7sch_loadi").hide();
        $("#l7btnsch").prop("disabled", false);
      }
    }
  });
}
function OpenSchedule() {
  $("#schedule-modal").modal("show");
}
function GetApiToken() {
  (function () {})();
  $.ajax({
    url: "/dash/rest/api/token/",
    type: "post",
    cache: false,
    success: function (_0x3fb659) {
      const _0x3ca386 = JSON.parse(_0x3fb659);
      var _0x2024a3 = _0x3ca386.status;
      var _0x3cfe89 = _0x3ca386.message;
      if (_0x2024a3 == "error") {
        $("#apitokenfield").val(_0x3cfe89);
      } else {
        $("#apitokenfield").val(_0x3cfe89);
      }
    }
  });
}
function GenerateToken() {
  $.ajax({
    url: "/dash/rest/api/generate/",
    type: "post",
    cache: false,
    success: function (_0x4f572e) {
      const _0x2136af = JSON.parse(_0x4f572e);
      var _0x4937dd = _0x2136af.status;
      var _0x26d20f = _0x2136af.message;
      if (_0x4937dd == "error") {
        Toastify({
          text: _0x26d20f,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x26d20f,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetApiToken();
        $("#gentoken-btn").attr("disabled", true);
        setTimeout(enableButton, 15000);
      }
    }
  });
}
function DisableToken() {
  $.ajax({
    url: "/dash/rest/api/disable/",
    type: "post",
    cache: false,
    success: function (_0x2b23a3) {
      const _0x5762d1 = JSON.parse(_0x2b23a3);
      var _0x23617f = _0x5762d1.status;
      var _0x4e46cd = _0x5762d1.message;
      if (_0x23617f == "error") {
        Toastify({
          text: _0x4e46cd,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x4e46cd,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetApiToken();
      }
    }
  });
}
function enableButton() {
  $("#gentoken-btn").attr("disabled", false);
}
function CloseTicket(_0x438a40) {
  var _0x1180bf = _0x438a40;
  if (_0x1180bf == "") {
    Toastify({
      text: "TicketID is empty!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $.ajax({
    url: "/dash/rest/tickets/close/",
    type: "post",
    data: {
      ticketid: _0x1180bf
    },
    cache: false,
    success: function (_0x434640) {
      const _0x234578 = JSON.parse(_0x434640);
      var _0x2edcca = _0x234578.status;
      var _0x4ea653 = _0x234578.message;
      if (_0x2edcca == "error") {
        Toastify({
          text: _0x4ea653,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x4ea653,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetTickets();
        $("#viewticket-modal").modal("hide");
      }
    }
  });
}
function OpenTicket() {
  var _0x2a95a4 = $("#ticketsubject").val();
  var _0x15d609 = $("#ticketpriority").val();
  var _0x3bfda9 = $("#ticketmessage").val();
  if (_0x2a95a4 == "" || _0x15d609 == "" || _0x3bfda9 == "") {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $.ajax({
    url: "/dash/rest/tickets/open/",
    type: "post",
    data: {
      subject: _0x2a95a4,
      priority: _0x15d609,
      msg: _0x3bfda9
    },
    cache: false,
    success: function (_0x1d757b) {
      const _0x2dd2b9 = JSON.parse(_0x1d757b);
      var _0x726700 = _0x2dd2b9.status;
      var _0x454b35 = _0x2dd2b9.message;
      if (_0x726700 == "error") {
        Toastify({
          text: _0x454b35,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        GetTickets();
      } else {
        Toastify({
          text: _0x454b35,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetTickets();
        $("#openticket-modal").modal("hide");
      }
    }
  });
}
function OpenTicketModal() {
  $("#openticket-modal").modal("show");
}
function ViewTicket(_0x456e02) {
  var _0x3c6ba9 = _0x456e02;
  if (_0x3c6ba9 == "") {
    Toastify({
      text: "TicketID is empty!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $("#ticket-label").text("Ticket # " + _0x3c6ba9);
  $("#viewticket-modal").modal("show");
  $.ajax({
    url: "/dash/rest/tickets/view/",
    type: "post",
    data: {
      ticketid: _0x3c6ba9
    },
    cache: false,
    success: function (_0x11bfbb) {
      document.getElementById("ticket-content").innerHTML = _0x11bfbb;
    }
  });
  $("#reply-btn").attr("onclick", "ReplyTicket(" + _0x3c6ba9 + ")");
  $("#closeticket-btn").attr("onclick", "CloseTicket(" + _0x3c6ba9 + ")");
}
function ReplyTicket(_0x406231) {
  var _0xee0c53 = _0x406231;
  var _0x363944 = $("#replyarea").val();
  if (_0xee0c53 == "") {
    Toastify({
      text: "TicketID is empty!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  if (_0x363944 == "") {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $.ajax({
    url: "/dash/rest/tickets/reply/",
    type: "post",
    data: {
      ticketid: _0xee0c53,
      reply: _0x363944
    },
    cache: false,
    success: function (_0xb755eb) {
      const _0x2893db = JSON.parse(_0xb755eb);
      var _0x29c3c1 = _0x2893db.status;
      var _0x315644 = _0x2893db.message;
      if (_0x29c3c1 == "error") {
        Toastify({
          text: _0x315644,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        GetTickets();
      } else {
        Toastify({
          text: _0x315644,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        ViewTicket(_0xee0c53);
        GetTickets();
      }
    }
  });
}
function GetTickets() {
  $("#tickets-table").DataTable({
    processing: true,
    serverSide: true,
    bFilter: false,
    serverMethod: "post",
    responsive: true,
    language: {
      emptyTable: "No active tickets"
    },
    ajax: {
      url: "/dash/rest/tickets/get/",
      type: "POST"
    },
    columnDefs: [{
      className: "text-center",
      targets: [0, 1, 2]
    }],
    bDestroy: true,
    columns: [{
      data: "subject"
    }, {
      data: "status"
    }, {
      data: "created"
    }]
  });
}
function StartL4Attack() {
  const _0x214af0 = document.getElementById("l4concs_num");
  var _0x59e3d8 = $("#l4host").val();
  var _0x3666bb = $("#l4time").val();
  var _0x13b8ea = $("#l4port").val();
  var _0x58dd8c = $("#l4method").val();
  var _0x2a0bd1 = _0x214af0.textContent;
  var _0x5c5276 = $("#csrf_token").val();
  $("#l4_def").hide();
  $("#l4_loadi").show();
  $("#l4btn").prop("disabled", true);
  if (_0x59e3d8 == "" || _0x3666bb == "" || _0x13b8ea == "" || _0x58dd8c == "" || _0x2a0bd1 == "" || _0x5c5276 == "" || _0x2a0bd1 == "0") {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    $("#l4_def").show();
    $("#l4_loadi").hide();
    $("#l4btn").prop("disabled", false);
    return;
  }
  $.ajax({
    url: "/dash/rest/user/start?type=l4",
    type: "post",
    data: {
      host: _0x59e3d8,
      time: _0x3666bb,
      port: _0x13b8ea,
      method: _0x58dd8c,
      concs: _0x2a0bd1
    },
    cache: false,
    success: function (_0xc12f54) {
      const _0x150d36 = JSON.parse(_0xc12f54);
      var _0x557e83 = _0x150d36.status;
      var _0x2aeca8 = _0x150d36.message;
      if (_0x557e83 == "error") {
        Toastify({
          text: _0x2aeca8,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x2aeca8,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetAttacks();
      }
      $("#l4_def").show();
      $("#l4_loadi").hide();
      $("#l4btn").prop("disabled", false);
    }
  });
}
function StartL7Attack() {
  const _0x44927c = document.getElementById("l7concs_num");
  var _0x5beb5a = $("#l7host").val();
  var _0x4947d4 = $("#l7time").val();
  var _0x2ec1f2 = $("#l7reqmethod").val();
  var _0x5661db = $("#l7method").val();
  var _0x1b5e50 = $("#l7reqs").val();
  var _0x4db2ad = $("#l7version").val();
  var _0x638eb = $("#l7referrer").val();
  var _0x58bf1f = $("#l7cookies").val();
  var _0x4a1306 = $("#l7geo").val();
  var _0x522fbe = _0x44927c.textContent;
  var _0x85ba3d = $("#csrf_token").val();
  $("#l7_def").hide();
  $("#l7_loadi").show();
  $("#l7btn").prop("disabled", true);
  if (_0x5beb5a == "" || _0x4947d4 == "" || _0x2ec1f2 == "" || _0x5661db == "" || _0x522fbe == "" || _0x85ba3d == "" || _0x522fbe == "0" || _0x1b5e50 == "" || _0x1b5e50 == null) {
    Toastify({
      text: "Please fill all required fields!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    $("#l7_def").show();
    $("#l7_loadi").hide();
    $("#l7btn").prop("disabled", false);
    return;
  }
  $.ajax({
    url: "/dash/rest/user/start?type=l7",
    type: "post",
    data: {
      host: _0x5beb5a,
      time: _0x4947d4,
      reqmethod: _0x2ec1f2,
      method: _0x5661db,
      reqs: _0x1b5e50,
      httpversion: _0x4db2ad,
      referrer: _0x638eb,
      cookies: _0x58bf1f,
      geoloc: _0x4a1306,
      concs: _0x522fbe
    },
    cache: false,
    success: function (_0x52f418) {
      const _0x500458 = JSON.parse(_0x52f418);
      var _0x44c6e4 = _0x500458.status;
      var _0x222790 = _0x500458.message;
      if (_0x44c6e4 == "error") {
        Toastify({
          text: _0x222790,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#l7_def").show();
        $("#l7_loadi").hide();
        $("#l7btn").prop("disabled", false);
      } else {
        Toastify({
          text: _0x222790,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        GetAttacks();
        $("#l7_def").show();
        $("#l7_loadi").hide();
        $("#l7btn").prop("disabled", false);
      }
    }
  });
}
function CheckL7Method() {
  var _0x54a7a5 = $("#l7method").val();
  if (_0x54a7a5 != "TLS1" || _0x54a7a5 != "TLS2") {
    $("#advanced-drop").hide();
    $("#advancedcoll").hide();
  } else {
    $("#advanced-drop").show();
  }
}
function StopAttack(_0x58a665) {
  var _0x2291fa = _0x58a665;
  $("#stop_def").hide();
  $("#stop_loadi").show();
  $("#stopbtn").prop("disabled", true);
  if (_0x2291fa == "") {
    Toastify({
      text: "Attack ID is empty!",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
  }
  $.ajax({
    url: "/dash/rest/user/start?type=stop",
    type: "post",
    data: {
      attackid: _0x2291fa
    },
    cache: false,
    success: function (_0x4cd12e) {
      const _0x4cf2f2 = JSON.parse(_0x4cd12e);
      var _0x50fce9 = _0x4cf2f2.status;
      var _0x4ea08d = _0x4cf2f2.message;
      if (_0x50fce9 == "error") {
        Toastify({
          text: _0x4ea08d,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#stop_def").show();
        $("#stop_loadi").hide();
        $("#stopbtn").prop("disabled", false);
        GetAttacks();
      } else {
        Toastify({
          text: _0x4ea08d,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        $("#stop_def").show();
        $("#stop_loadi").hide();
        $("#stopbtn").prop("disabled", false);
        GetAttacks();
      }
    }
  });
}
function StopAllAttacks() {
  $("#stopall_def").hide();
  $("#stopall_loadi").show();
  $("#stopallbtn").prop("disabled", true);
  $.ajax({
    url: "/dash/rest/user/start?type=stopall",
    type: "get",
    cache: false,
    success: function (_0x121bc3) {
      const _0x4c54b7 = JSON.parse(_0x121bc3);
      var _0x1b0c34 = _0x4c54b7.status;
      var _0x5000c7 = _0x4c54b7.message;
      if (_0x1b0c34 == "error") {
        Toastify({
          text: _0x5000c7,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#stopall_def").show();
        $("#stopall_loadi").hide();
        $("#stopallbtn").prop("disabled", false);
      } else {
        Toastify({
          text: _0x5000c7,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        $("#stopall_def").show();
        $("#stopall_loadi").hide();
        $("#stopallbtn").prop("disabled", false);
        GetAttacks();
      }
    }
  });
}
function CountAttackTime(_0x1b787f, _0x3fcd5c) {
  var _0x1b734f = setInterval(function () {
    if (_0x3fcd5c <= 0) {
      clearInterval(_0x1b734f);
      $("#expires-" + _0x1b787f).html("Expired");
      GetAttacks();
    } else {
      $("#expires-" + _0x1b787f).html(new Date(_0x3fcd5c * 1000).toISOString().substr(11, 8));
    }
    _0x3fcd5c -= 1;
  }, 1000);
}
function GetAttacks() {
  $("#attacks-table").DataTable({
    processing: true,
    serverSide: true,
    bFilter: false,
    serverMethod: "post",
    responsive: true,
    language: {
      emptyTable: "No running attacks"
    },
    ajax: {
      url: "/dash/rest/user/attacks/",
      type: "POST"
    },
    columnDefs: [{
      className: "text-left",
      targets: [1, 2, 3, 4]
    }],
    bDestroy: true,
    columns: [{
      data: "id"
    }, {
      data: "target"
    }, {
      data: "method"
    }, {
      data: "expire"
    }, {
      data: "action"
    }]
  });
}
function Deposit() {
  var _0x32c174 = $("#depamount").val();
  var _0x56f29b = $("#gateway").val();
  if (_0x32c174 == "" || _0x56f29b == "") {
    Toastify({
      text: "Please fill all fields.",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
  }
  $("#dep_def").hide();
  $("#dep_loadi").show();
  $("#depbtn").prop("disabled", true);
  $.ajax({
    url: "/dash/rest/payment/create/",
    type: "post",
    data: {
      amount: _0x32c174,
      gateway: _0x56f29b
    },
    cache: false,
    success: function (_0x1645b0) {
      const _0x46c9ed = JSON.parse(_0x1645b0);
      var _0x759034 = _0x46c9ed.status;
      var _0x10d612 = _0x46c9ed.message;
      if (_0x759034 == "error") {
        Toastify({
          text: _0x10d612,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#dep_def").show();
        $("#dep_loadi").hide();
        $("#depbtn").prop("disabled", false);
      } else {
        Toastify({
          text: _0x10d612,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();

        $("#crypto_create").hide()
        $("#crypto-header").html("#ID " + _0x46c9ed.id)
        $("#crypto-status").html(_0x46c9ed.pstatus)
        $("#qrimage").attr("src", _0x46c9ed.qr);
        $("#processing_addy").val(_0x46c9ed.address)
        $("#processing_amount").val(_0x46c9ed.amount)
        $("#crypto-expire-container").html(_0x46c9ed.expires)
        $("#processing_paid").val(_0x46c9ed.amount_paid)
        $("#processing_conf").val(_0x46c9ed.confirmations)
        $("#crypto_waiting").show()
        $("#recheck-btn").attr("onclick", "ReCheck(" + _0x46c9ed.id + ")");
        $("#cancel-btn").attr("onclick", "CancelPayment(" + _0x46c9ed.id + ")");
        //setTimeout(function () {
        //  $("#qrimage").attr("src", _0x46c9ed.qr);
        //  $("#cryptocoin").val(_0x46c9ed.coin);
        //  $("#address").val(_0x46c9ed.address);
        //  $("#cryptoamount").val(_0x46c9ed.amount);
        //  $("#cryptoamountpaid").val(_0x46c9ed.amount_paid);
        //  $("#cryptoexpires").val(_0x46c9ed.expires);
        //  $("#cryptostatus").val(_0x46c9ed.pstatus);
        //  $("#cryptoconfirms").val(_0x46c9ed.confirmations);
        //  $("#cryptohash").val("Please pay to get hash");
        //  $("#modal-label").text("Payment # " + _0x46c9ed.id);
        //  $("#recheck-btn").attr("onclick", "ReCheck(" + _0x46c9ed.id + ")");
        //  $("#cancel-btn").attr("onclick", "CancelPayment(" + _0x46c9ed.id + ")");
        //  $("#payment-modal").modal("show");
        //}, 1500);
        //$("#dep_def").show();
        //$("#dep_loadi").hide();
        //$("#depbtn").prop("disabled", false);
        PaymentHistory();
      }
    }
  });
}
function PaymentHistory() {
  $("#billing-table").DataTable({
    processing: true,
    serverSide: true,
    bFilter: false,
    serverMethod: "post",
    responsive: true,
    language: {
      emptyTable: "No payment history"
    },
    ajax: {
      url: "/dash/rest/user/payments/",
      type: "POST"
    },
    columnDefs: [{
      className: "text-center",
      targets: [5]
    }],
    bDestroy: true,
    columns: [{
      data: "id"
    }, {
      data: "type"
    }, {
      data: "amount"
    }, {
      data: "status"
    }, {
      data: "date"
    }, {
      data: "action"
    }]
  });
}
function OpenPayment(_0x18d635) {
  var _0x459a4d = _0x18d635;
  $.ajax({
    url: "/dash/rest/payment/info/",
    type: "post",
    data: {
      payid: _0x459a4d
    },
    cache: false,
    success: function (_0x40852b) {
      const _0x4a8656 = JSON.parse(_0x40852b);
      var _0x1def1f = _0x4a8656.status;
      var _0x3de9f8 = _0x4a8656.message;
      if (_0x1def1f == "error") {
        Toastify({
          text: _0x3de9f8,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        $("#crypto_create").hide()
        $(".crypto-header").html("Payment ID " + _0x4a8656.id)
        $("#crypto-status").html(_0x4a8656.pstatus)
        $("#qrimage").attr("src", _0x4a8656.qr);
        $("#processing_addy").val(_0x4a8656.address)
        $("#processing_amount").val(_0x4a8656.amount)
        $("#crypto-expire-container").html(_0x4a8656.expires)
        $("#processing_paid").val(_0x4a8656.amount_paid)
        $("#processing_conf").val(_0x4a8656.confirmations)
        $("#crypto_waiting").show()
        $("#recheck-btn").attr("onclick", "ReCheck(" + _0x4a8656.id + ")");
        $("#cancel-btn").attr("onclick", "CancelPayment(" + _0x4a8656.id + ")");
        //$("#qrimage").attr("src", _0x4a8656.qr);
        //$("#cryptocoin").val(_0x4a8656.coin);
        //$("#address").val(_0x4a8656.address);
        //$("#cryptoamount").val(_0x4a8656.amount);
        //$("#cryptoamountpaid").val(_0x4a8656.amount_paid);
        //$("#cryptoexpires").val(_0x4a8656.expires);
        //$("#cryptostatus").val(_0x4a8656.pstatus);
        //$("#cryptoconfirms").val(_0x4a8656.confirmations);
        //$("#cryptohash").val(_0x4a8656.hash);
        //$("#modal-label").text("Payment # " + _0x4a8656.id);
        //$("#recheck-btn").attr("onclick", "ReCheck(" + _0x4a8656.id + ")");
        //$("#cancel-btn").attr("onclick", "CancelPayment(" + _0x4a8656.id + ")");
        //$("#payment-modal").modal("show");
      }
    }
  });
}
function ReCheck(_0x43c0c1) {
  var _0x495d35 = _0x43c0c1;
  $("#recheck_def").hide();
  $("#recheck_loadi").show();
  $("#recheck-btn").prop("disabled", true);
  $.ajax({
    url: "/dash/rest/payment/info/",
    type: "post",
    data: {
      payid: _0x495d35
    },
    cache: false,
    success: function (_0x1d58d1) {
      const _0x4f84d8 = JSON.parse(_0x1d58d1);
      var _0x343682 = _0x4f84d8.status;
      var _0x4df7c5 = _0x4f84d8.message;
      if (_0x343682 == "error") {
        Toastify({
          text: _0x4df7c5,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#recheck_def").show();
        $("#recheck_loadi").hide();
        $("#recheck-btn").prop("disabled", false);
      } else {
        Toastify({
          text: "Payment successfully re-checked.",
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        $("#qrimage").attr("src", _0x4f84d8.qr);
        $("#cryptocoin").val(_0x4f84d8.coin);
        $("#address").val(_0x4f84d8.address);
        $("#cryptoamount").val(_0x4f84d8.amount);
        $("#cryptoamountpaid").val(_0x4f84d8.amount_paid);
        $("#cryptoexpires").val(_0x4f84d8.expires);
        $("#cryptostatus").val(_0x4f84d8.pstatus);
        $("#cryptoconfirms").val(_0x4f84d8.confirmations);
        $("#cryptohash").val(_0x4f84d8.hash);
        $("#recheck_def").show();
        $("#recheck_loadi").hide();
        $("#recheck-btn").prop("disabled", false);
      }
    }
  });
}
function CancelPayment(_0x1128cc) {
  var _0x58d1e1 = _0x1128cc;
  if (_0x58d1e1 == "") {
    Toastify({
      text: "Payment ID is empty.",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $("#cancelpay_def").hide();
  $("#cancelpay_loadi").show();
  $("#cancel-btn").prop("disabled", true);
  $.ajax({
    url: "/dash/rest/payment/cancel/",
    type: "post",
    data: {
      payid: _0x58d1e1
    },
    cache: false,
    success: function (_0x52ce53) {
      const _0x42f7fc = JSON.parse(_0x52ce53);
      var _0x1af3f4 = _0x42f7fc.status;
      var _0x1352dd = _0x42f7fc.message;
      if (_0x1af3f4 == "error") {
        Toastify({
          text: _0x1352dd,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        $("#cancelpay_def").show();
        $("#cancelpay_loadi").hide();
        $("#cancel-btn").prop("disabled", false);
      } else {
        Toastify({
          text: _0x1352dd,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        $("#crypto_create").show()
        PaymentHistory();
        $("#cancelpay_def").show();
        $("#cancelpay_loadi").hide();
        $("#cancel-btn").prop("disabled", false);
        $("#crypto_waiting").hide()
      }
    }
  });
}
function copyaddr() {
  $("#processing_addy").prop("disabled", false);
  $("#processing_addy").select();
  document.execCommand("copy");
  Toastify({
    text: "Address successfully copied to clipboard.",
    duration: 1500,
    backgroundColor: "green"
  }).showToast();
  $("#processing_addy").prop("disabled", true);
}
function copyamount() {
  $("#processing_amount").prop("disabled", false);
  $("#processing_amount").select();
  document.execCommand("copy");
  Toastify({
    text: "Amount successfully copied to clipboard.",
    duration: 1500,
    backgroundColor: "green"
  }).showToast();
  $("#processing_amount").prop("disabled", true);
}
function openhash() {
  var _0x155456 = $("#cryptohash").val();
  var _0xecae74 = "https://mempool.space/tx/" + _0x155456 + "";
  window.open(_0xecae74, "_blank");
}
function PurchasePlan(_0x3e81a2) {
  var _0x37c8af = _0x3e81a2.id;
  var _0xe1208b = _0x3e81a2.name;
  $("#purchasemodal-label").text("Purchasing - " + _0xe1208b);
  $("#purchase-modal").modal("show");
  PlanInfo(_0x37c8af);
  $("#purchase-btnnnn").attr("onclick", "BuyPlan(" + _0x37c8af + ")");
}
function BuyPlan(_0x42a88d) {
  var _0x2f96e1 = _0x42a88d;
  var _0x3e1e87 = $("#couponcode2").val();
  $.ajax({
    url: "/dash/rest/purchase/plan/",
    type: "post",
    data: {
      planid: _0x2f96e1,
      coupon: _0x3e1e87
    },
    cache: false,
    success: function (_0x15ed8f) {
      const _0xcb23ac = JSON.parse(_0x15ed8f);
      var _0x499ffc = _0xcb23ac.status;
      var _0x3a8524 = _0xcb23ac.message;
      if (_0x499ffc == "error") {
        Toastify({
          text: _0x3a8524,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x3a8524,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        setTimeout(function () {
          window.location.href = "home";
        }, 2000);
      }
    }
  });
}
function PlanInfo(_0x5b8e77) {
  var _0x1d6394 = _0x5b8e77;
  $.ajax({
    url: "/dash/rest/purchase/planinfo/",
    type: "post",
    data: {
      planid: _0x1d6394
    },
    cache: false,
    success: function (_0x44deb9) {
      const _0x4c8f86 = JSON.parse(_0x44deb9);
      var _0x4917eb = _0x4c8f86.status;
      var _0x1e9f15 = _0x4c8f86.message;
      if (_0x4917eb == "error") {
        Toastify({
          text: _0x1e9f15,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        $("#planconcs").text(_0x4c8f86.concs);
        $("#planatime").text(_0x4c8f86.time);
        $("#planlength").text(_0x4c8f86.length);
        $("#planpremium").text(_0x4c8f86.premium);
        $("#planapi").text(_0x4c8f86.api);
        $("#planprice").text(_0x4c8f86.price);
      }
    }
  });
}
function addon_price() {
  var _0x45617f = document.getElementById("addonconcurrents");
  addon = _0x45617f.value;
  total_concurrents = Number(0) + Number(addon);
  concurrents_price = Number(total_concurrents) * Number(35);
  current_price = concurrents_price;
  var _0x45617f = document.getElementById("addonboottime");
  addon = _0x45617f.value;
  if (addon == 0) {
    maxboot_increase = 0;
  }
  if (addon == 300) {
    maxboot_increase = 10;
  }
  if (addon == 600) {
    maxboot_increase = 20;
  }
  if (addon == 900) {
    maxboot_increase = 30;
  }
  if (addon == 1200) {
    maxboot_increase = 40;
  }
  if (addon == 1500) {
    maxboot_increase = 50;
  }
  if (addon == 1800) {
    maxboot_increase = 60;
  }
  if (addon == 2100) {
    maxboot_increase = 70;
  }
  if (addon == 2400) {
    maxboot_increase = 80;
  }
  if (addon == 2700) {
    maxboot_increase = 90;
  }
  if (addon == 3000) {
    maxboot_increase = 100;
  }
  var _0x422939 = maxboot_increase;
  maxboot_price = _0x422939;
  total_maxboot = addon;
  current_price = Number(maxboot_price) + Number(concurrents_price);
  var _0x2e72d9 = document.getElementById("addonpremium");
  status = _0x2e72d9.value;
  if (status == 0) {
    _0x422939 = 0;
  }
  if (status == 1) {
    _0x422939 = 70;
  }
  current_price = Number(current_price) + Number(_0x422939);
  var _0x4bc3b1 = document.getElementById("addonblacklist");
  blacklist = _0x4bc3b1.value;
  if (blacklist == 0) {
    increasebl = 0;
  }
  if (blacklist == 1) {
    increasebl = 15;
  }
  current_price = Number(current_price) + Number(increasebl);
  var _0x371fad = document.getElementById("addonapi");
  apiaccess = _0x371fad.value;
  if (apiaccess == 0) {
    increaseapi = 0;
  }
  if (apiaccess == 1) {
    increaseapi = 25;
  }
  total_amount = Number(current_price) + Number(increaseapi);
  var _0x3ceabe = Math.floor(total_amount);
  document.getElementById("price_total").value = _0x3ceabe + "$";
  document.getElementById("price").value = _0x3ceabe;
  document.getElementById("concurrents_total").value = total_concurrents + "c";
  document.getElementById("extraconcurrents").value = total_concurrents;
  document.getElementById("maxboot_total").value = total_maxboot + "s";
  document.getElementById("maxboot").value = total_maxboot;
  document.getElementById("premium").value = status;
  document.getElementById("blacklist").value = blacklist;
  document.getElementById("apiaccess").value = apiaccess;
}
function PurchaseAddon() {
  var _0x426169 = $("#extraconcurrents").val();
  var _0x49c38c = $("#maxboot").val();
  var _0x4d8162 = $("#premium").val();
  var _0x4e12f4 = $("#blacklist").val();
  var _0x3ffd53 = $("#apiaccess").val();
  var _0x3ad391 = $("#price").val();
  var _0x4e7db2 = $("#couponcodeaddon").val();
  $.ajax({
    url: "/dash/rest/purchase/addon/",
    type: "post",
    data: {
      concs: _0x426169,
      attacktime: _0x49c38c,
      premium: _0x4d8162,
      blacklist: _0x4e12f4,
      apiaccess: _0x3ffd53,
      price: _0x3ad391,
      coupon: _0x4e7db2
    },
    cache: false,
    success: function (_0x20c392) {
      const _0x366b24 = JSON.parse(_0x20c392);
      var _0x278fa1 = _0x366b24.status;
      var _0x580c81 = _0x366b24.message;
      if (_0x278fa1 == "error") {
        Toastify({
          text: _0x580c81,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x580c81,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        setTimeout(function () {
          window.location.href = "home";
        }, 2000);
      }
    }
  });
}
function SignOut() {
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
      background: "#FFC700"
    }
  }).showToast();
  setTimeout(function () {
    window.location.replace("/dash/rest/user/logout");
  }, 1000);
}
function ClearNotifications() {
  Toastify({
    text: "Notifications successfully cleared.",
    duration: 1000,
    newWindow: false,
    close: false,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    style: {
      background: "#FFC700"
    }
  }).showToast();
}
function ChangePass() {
  var _0x136714 = $("#currpassword").val();
  var _0xd97421 = $("#npassword").val();
  var _0x19ac77 = $("#cpassword").val();
  if (_0xd97421 != _0x19ac77) {
    Toastify({
      text: "Passwords do not match",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  if (_0x136714 == "" || _0xd97421 == "" || _0x19ac77 == "") {
    Toastify({
      text: "Please fill all required fields",
      duration: 1500,
      backgroundColor: "red"
    }).showToast();
    return;
  }
  $.ajax({
    url: "/dash/rest/user/password/",
    type: "post",
    data: {
      currpass: _0x136714,
      newpass: _0xd97421
    },
    cache: false,
    success: function (_0x12ca1b) {
      const _0x5c143f = JSON.parse(_0x12ca1b);
      var _0x1f2075 = _0x5c143f.status;
      var _0x5b607a = _0x5c143f.message;
      if (_0x1f2075 == "error") {
        Toastify({
          text: _0x5b607a,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
      } else {
        Toastify({
          text: _0x5b607a,
          duration: 1500,
          backgroundColor: "green"
        }).showToast();
        setTimeout(function () {
          location.reload();
        }, 2000);
      }
    }
  });
}
function DeleteAcc() {
  Swal.fire({
    title: "Delete Account",
    html: "<input type=\"text\" id=\"secret\" class=\"swal2-input\" placeholder=\"Secret key\">",
    confirmButtonText: "Confirm",
    focusConfirm: false,
    preConfirm: () => {
      const _0xa62e44 = Swal.getPopup().querySelector("#secret").value;
      return {
        secret: _0xa62e44
      };
    }
  }).then(_0x2e6518 => {
    $.ajax({
      url: "/dash/rest/user/delete/",
      type: "post",
      data: {
        secretkey: _0x2e6518.value.secret
      },
      cache: false,
      success: function (_0x562d2b) {
        const _0xb26840 = JSON.parse(_0x562d2b);
        var _0x45bbbb = _0xb26840.status;
        var _0xf5d21 = _0xb26840.message;
        if (_0x45bbbb == "error") {
          Toastify({
            text: _0xf5d21,
            duration: 1500,
            backgroundColor: "red"
          }).showToast();
        } else {
          Toastify({
            text: _0xf5d21,
            duration: 1500,
            backgroundColor: "green"
          }).showToast();
          setTimeout(function () {
            location.reload();
          }, 2000);
        }
      }
    });
  });
}
var check = window.setInterval(function () {
  $.ajax({
    url: "/dash/rest/user/check/",
    type: "get",
    cache: false,
    success: function (_0x13f046) {
      const _0x42026f = JSON.parse(_0x13f046);
      var _0x3a913a = _0x42026f.status;
      var _0x1b309f = _0x42026f.message;
      if (_0x3a913a == "error") {
        Toastify({
          text: _0x1b309f,
          duration: 1500,
          backgroundColor: "red"
        }).showToast();
        setTimeout(function () {
          window.location.href = "../login";
        }, 1500);
      }
    }
  });
}, 10000);
const tooltipTriggerList = document.querySelectorAll("[data-bs-toggle=\"tooltip\"]");
const tooltipList = [...tooltipTriggerList].map(_0x4c5626 => new bootstrap.Tooltip(_0x4c5626));
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".spoiler").forEach(_0x21b18d => {
    _0x21b18d.onclick = () => {
      if (_0x21b18d.classList.contains("spoiler-shown")) {
        _0x21b18d.classList.remove("spoiler-shown");
      } else {
        _0x21b18d.classList.add("spoiler-shown");
      }
    };
  });
}, false);
function _0x203190(_0x4708ab) {
  function _0x12fe89(_0x32723e) {
    if (typeof _0x32723e === "string") {
      return function (_0x5a2e02) {}.constructor("while (true) {}").apply("counter");
    } else if (("" + _0x32723e / _0x32723e).length !== 1 || _0x32723e % 20 === 0) {
      (function () {
        return true;
      }).constructor("debugger").call("action");
    } else {
      (function () {
        return false;
      }).constructor("debugger").apply("stateObject");
    }
    _0x12fe89(++_0x32723e);
  }
  try {
    if (_0x4708ab) {
      return _0x12fe89;
    } else {
      _0x12fe89(0);
    }
  } catch (_0x596e1a) {}
}