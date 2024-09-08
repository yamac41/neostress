$('#preloader').fadeOut(1200);
GetActiveTickets();
LastPayments();
PlanPurchases();
AddonPurchases();
GetAttacks();
setTimeout(GetAttacks, 3000);
PlanList();
NewsList();
ServerList();
Methods();
AllTickets();
AllUsers();
AllGiftCards();
PowerProofsList()
function LastPayments() {
    $('#lastpayments-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        bInfo: false,
        sPaginationType: 'full_numbers',
        iDisplayLength: 7,
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No payments' },
        ajax: {
            url: 'rest/payments/get',
            type: 'POST'
        },
        columnDefs: [
            {
                name: 'id',
                targets: 0
            },
            {
                name: 'user',
                targets: 1
            },
            {
                name: 'amount',
                targets: 2
            },
            {
                name: 'date',
                targets: 3
            },
            {
                name: 'status',
                targets: 4
            },
            {
                name: 'action',
                targets: 5
            },
            {
                className: 'text-center',
                targets: [
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            }
        ],
        order: [[
                0,
                'desc'
            ]],
        bDestroy: true,
        columns: [
            { data: 'id' },
            { data: 'user' },
            { data: 'amount' },
            { data: 'date' },
            { data: 'status' },
            { data: 'action' }
        ]
    });
}
function ViewPayments(_0x5a7f9b) {
    var _0x2f7a33 = _0x5a7f9b;
    if (_0x2f7a33 == '') {
        Toastify({
            text: 'PaymentID is empty!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    $.ajax({
        url: 'rest/tickets/close',
        type: 'post',
        data: { ticketid: ticketid },
        cache: false,
        success: function (_0x2f65ea) {
            const _0x3e9849 = JSON.parse(_0x2f65ea);
            var _0x314cb1 = _0x3e9849.status, _0x536eb6 = _0x3e9849.message;
            if (_0x314cb1 == 'error') {
                Toastify({
                    text: _0x536eb6,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
                GetActiveTickets();
            } else {
                Toastify({
                    text: _0x536eb6,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                GetActiveTickets();
            }
        }
    });
}
function GetActiveTickets() {
    $('#activetickets-table').DataTable({
        processing: true,
        serverSide: true,
        bFilter: false,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No active tickets' },
        ajax: {
            url: 'rest/tickets/get',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    1,
                    2,
                    3,
                    4
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'priority' },
            { data: 'user' },
            { data: 'action' }
        ]
    });
}
function PlanPurchases() {
    $('#planpurchases-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No purchases' },
        ajax: {
            url: 'rest/purchases/plan',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'user' },
            { data: 'plan' },
            { data: 'amount' },
            { data: 'date' }
        ]
    });
}
function AddonPurchases() {
    $('#addonspurchases-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No purchases' },
        ajax: {
            url: 'rest/purchases/addons',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3,
                    5
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'user' },
            { data: 'concs' },
            { data: 'time' },
            { data: 'blacklist' },
            { data: 'apiaccess' },
            { data: 'date' }
        ]
    });
}
function ViewTicket(_0x5e8be7) {
    var _0x1c63fd = _0x5e8be7;
    if (_0x1c63fd == '') {
        Toastify({
            text: 'TicketID is empty!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    $('#ticket-label').text('Ticket # ' + _0x1c63fd);
    $('#viewticket-modal').modal('show');
    $.ajax({
        url: 'rest/tickets/view',
        type: 'post',
        data: { ticketid: _0x1c63fd },
        cache: false,
        success: function (_0x4b49b1) {
            document.getElementById('ticket-content').innerHTML = _0x4b49b1;
        }
    });
    $('#reply-btn').attr('onclick', 'ReplyTicket(' + _0x1c63fd + ')');
    $('#closeticket-btn').attr('onclick', 'CloseTicket(' + _0x1c63fd + ')');
}
function CloseTicket(_0x1619af) {
    var _0x38b0dc = _0x1619af;
    if (_0x38b0dc == '') {
        Toastify({
            text: 'TicketID is empty!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    $.ajax({
        url: 'rest/tickets/close',
        type: 'post',
        data: { ticketid: _0x38b0dc },
        cache: false,
        success: function (_0x1a7c23) {
            const _0xfdb7e4 = JSON.parse(_0x1a7c23);
            var _0x2a3447 = _0xfdb7e4.status, _0x1bff50 = _0xfdb7e4.message;
            if (_0x2a3447 == 'error') {
                Toastify({
                    text: _0x1bff50,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
                GetActiveTickets();
            } else {
                Toastify({
                    text: _0x1bff50,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                GetActiveTickets();
            }
        }
    });
}
function ReplyTicket(_0x483046) {
    var _0x795458 = _0x483046, _0x2957c2 = $('#replyarea').val();
    if (_0x795458 == '') {
        Toastify({
            text: 'TicketID is empty!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    if (_0x2957c2 == '') {
        Toastify({
            text: 'Please fill all required fields!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    $.ajax({
        url: 'rest/tickets/reply',
        type: 'post',
        data: {
            ticketid: _0x795458,
            reply: _0x2957c2
        },
        cache: false,
        success: function (_0x24f9ab) {
            const _0x1180d3 = JSON.parse(_0x24f9ab);
            var _0x4d9889 = _0x1180d3.status, _0x3d25e5 = _0x1180d3.message;
            if (_0x4d9889 == 'error') {
                Toastify({
                    text: _0x3d25e5,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
                GetActiveTickets();
            } else {
                Toastify({
                    text: _0x3d25e5,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                GetActiveTickets();
                $('#viewticket-modal').modal('hide');
            }
        }
    });
}
function AddInvoice() {
    Swal.fire({
        title: 'Add Invoice',
        html: '<input type="text" id="user" class="swal2-input" placeholder="User">\n           <input type="text" id="amount" class="swal2-input" placeholder="Amount">\n           <select class="swal2-input" id="type">\n             <option value="BITCOIN" selected>BITCOIN</option>\n             <option value="PAYPAL" selected>PAYPAL</option>\n             <option value="LITECOIN" selected>LITECOIN</option>\n             <option value="BITCOINCASH" selected>BITCOINCASH</option>\n             <option value="ETHEREUM" selected>ETHEREUM</option>\n             <option value="MONERO" selected>MONERO</option>\n             <option value="NANO" selected>NANO</option>\n             <option value="SOLANA" selected>SOLANA</option>\n             <option value="GIFTCARD" selected>GIFTCARD</option>\n           </select>\n           <input type="datetime-local" id="date" class="swal2-input" placeholder="Date">\n\n    ',
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            const _0x121df1 = Swal.getPopup().querySelector('#user').value, _0x13ec1b = Swal.getPopup().querySelector('#amount').value, _0x1a1086 = Swal.getPopup().querySelector('#type').value, _0xdf2d3e = Swal.getPopup().querySelector('#date').value;
            return {
                user: _0x121df1,
                amount: _0x13ec1b,
                type: _0x1a1086,
                date: _0xdf2d3e
            };
        }
    }).then(_0x1a0d9e => {
        $.ajax({
            url: 'rest/payments/add',
            type: 'post',
            data: {
                user: _0x1a0d9e.value.user,
                amount: _0x1a0d9e.value.amount,
                type: _0x1a0d9e.value.type,
                date: _0x1a0d9e.value.date
            },
            cache: false,
            success: function (_0x26a01e) {
                const _0x2403f6 = JSON.parse(_0x26a01e);
                var _0x96d0ff = _0x2403f6.status, _0x413514 = _0x2403f6.message;
                if (_0x96d0ff == 'error') {
                    Toastify({
                        text: _0x413514,
                        duration: 1500,
                        backgroundColor: 'red'
                    }).showToast();
                } else {
                    Toastify({
                        text: _0x413514,
                        duration: 1500,
                        backgroundColor: 'green'
                    }).showToast();
                    LastPayments();
                }
            }
        });
    });
}
function ViewPayment(_0x507cb3) {
    var _0x52b449 = _0x507cb3;
    $.ajax({
        url: 'rest/payments/view',
        type: 'post',
        data: { paymentid: _0x52b449 },
        cache: false,
        success: function (_0x5e6a8a) {
            const _0x32f030 = JSON.parse(_0x5e6a8a);
            var _0x463d84 = _0x32f030.status, _0x480a50 = _0x32f030.message;
            if (_0x463d84 == 'error') {
                Toastify({
                    text: _0x480a50,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Swal.fire({
                    title: 'Payment # ' + _0x32f030.uniqid,
                    html: '<b>Gateway</b> - ' + _0x32f030.gateway + ' <br><b>Hash</b> - <a href="https://mempool.space/tx/' + _0x32f030.hash + '">' + _0x32f030.hash + '</a><br><b>Status</b> - ' + _0x32f030.status + '<br><b>Created At</b> - ' + _0x32f030.created
                });
            }
        }
    });
}
function GetAttacks() {
    $('#live-attacks').load('rest/user/attacks');
}
function CountAttackTime(_0x5a0c0d, _0x43360b) {
    var _0x379e5f = setInterval(function () {
        if (_0x43360b <= 0) {
            clearInterval(_0x379e5f);
            $('#expires-' + _0x5a0c0d).html('Expired');
            GetAttacks();
        } else {
            $('#expires-' + _0x5a0c0d).html(new Date(_0x43360b * 1000).toISOString().substr(11, 8));
        }
        _0x43360b -= 1;
    }, 1000);
}
function StopAttack(_0x4e0e16) {
    var _0x3fe357 = _0x4e0e16;
    $('#stop_def').hide();
    $('#stop_loadi').show();
    $('#stopbtn').prop('disabled', true);
    if (_0x3fe357 == '') {
        Toastify({
            text: 'Attack ID is empty!',
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
    }
    $.ajax({
        url: 'rest/admin/stop',
        type: 'post',
        data: { attackid: _0x3fe357 },
        cache: false,
        success: function (_0x108589) {
            const _0x3326e7 = JSON.parse(_0x108589);
            var _0x4412ad = _0x3326e7.status, _0x278185 = _0x3326e7.message;
            if (_0x4412ad == 'error') {
                Toastify({
                    text: _0x278185,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
                $('#stop_def').show();
                $('#stop_loadi').hide();
                $('#stopbtn').prop('disabled', false);
                GetAttacks();
            } else {
                Toastify({
                    text: _0x278185,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#stop_def').show();
                $('#stop_loadi').hide();
                $('#stopbtn').prop('disabled', false);
                GetAttacks();
            }
        }
    });
}
function StopAllAttacks() {
    $('#stopall_def').hide();
    $('#stopall_loadi').show();
    $('#stopallbtn').prop('disabled', true);
    $.ajax({
        url: 'rest/admin/stopall',
        type: 'post',
        cache: false,
        success: function (_0x41f24b) {
            const _0xa88733 = JSON.parse(_0x41f24b);
            var _0x202d6a = _0xa88733.status, _0x30aea2 = _0xa88733.message;
            if (_0x202d6a == 'error') {
                Toastify({
                    text: _0x30aea2,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
                $('#stopall_def').show();
                $('#stopall_loadi').hide();
                $('#stopallbtn').prop('disabled', false);
            } else {
                Toastify({
                    text: _0x30aea2,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#stopall_def').show();
                $('#stopall_loadi').hide();
                $('#stopallbtn').prop('disabled', false);
                GetAttacks();
            }
        }
    });
}
function OpenBlacklist() {
    $('#blacklist-modal').modal('show');
    $('#blacklist-body').load('rest/blacklist/get');
}
function BlackList() {
    Swal.fire({
        title: 'Blacklist Target',
        html: '<input type="text" id="target" class="swal2-input" placeholder="Target">\n           <select id="type" class="swal2-input">\n             <option value="ASN">ASN</option>\n             <option value="IP">IP</option>\n             <option value="URL">URL</option>\n             <option value="DOMAIN">DOMAIN</option>\n           </select>\n    ',
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            const _0x113a7e = Swal.getPopup().querySelector('#target').value, _0x5724f7 = Swal.getPopup().querySelector('#type').value;
            return {
                target: _0x113a7e,
                type: _0x5724f7
            };
        }
    }).then(_0x1cff27 => {
        $.ajax({
            url: 'rest/blacklist/add',
            type: 'post',
            data: {
                target: _0x1cff27.value.target,
                type: _0x1cff27.value.type
            },
            cache: false,
            success: function (_0x1885c5) {
                const _0x5932b1 = JSON.parse(_0x1885c5);
                var _0x14d2f5 = _0x5932b1.status, _0x54d370 = _0x5932b1.message;
                if (_0x14d2f5 == 'error') {
                    Toastify({
                        text: _0x54d370,
                        duration: 1500,
                        backgroundColor: 'red'
                    }).showToast();
                } else {
                    Toastify({
                        text: _0x54d370,
                        duration: 1500,
                        backgroundColor: 'green'
                    }).showToast();
                    OpenBlacklist();
                }
            }
        });
    });
}
function UnBlackList(_0x3055e6) {
    var _0x2b76f0 = _0x3055e6;
    $.ajax({
        url: 'rest/blacklist/delete',
        type: 'post',
        data: { id: _0x2b76f0 },
        cache: false,
        success: function (_0x4bf04c) {
            const _0x185934 = JSON.parse(_0x4bf04c);
            var _0x93aca7 = _0x185934.status, _0x1d1b3e = _0x185934.message;
            if (_0x93aca7 == 'error') {
                Toastify({
                    text: _0x1d1b3e,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x1d1b3e,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                OpenBlacklist();
            }
        }
    });
}
function OpenLogs() {
    $('#logs-modal').modal('show');
    $('#logs-body').load('rest/logs/get');
}
// function ClearLogs() {
//     $.ajax({
//         url: 'rest/logs/clear',
//         type: 'post',
//         cache: false,
//         success: function (_0x41c503) {
//             const _0xe1880b = JSON.parse(_0x41c503);
//             var _0x163d1a = _0xe1880b.status, _0x36b684 = _0xe1880b.message;
//             if (_0x163d1a == 'error') {
//                 Toastify({
//                     text: _0x36b684,
//                     duration: 1500,
//                     backgroundColor: 'red'
//                 }).showToast();
//             } else {
//                 Toastify({
//                     text: _0x36b684,
//                     duration: 1500,
//                     backgroundColor: 'green'
//                 }).showToast();
//                 OpenLogs();
//             }
//         }
//     });
// }
function PlanList() {
    $('#allplans-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No plans' },
        ajax: {
            url: 'rest/plans/get',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'name' },
            { data: 'price' },
            { data: 'time' },
            { data: 'concs' },
            { data: 'length' },
            { data: 'premium' },
            { data: 'api' },
            { data: 'private' },
            { data: 'users' },
            { data: 'action' }
        ]
    });
}
function CreatePlan() {
    var _0x3129c7 = $('#planname').val(), _0x2f39e4 = $('#planprice').val(), _0x5b0be2 = $('#planlength').val(), _0x1a9b1b = $('#plantype').val(), _0x4a5987 = $('#planpublic').val(), _0x382db8 = $('#planconcs').val(), _0x359441 = $('#plantime').val(), _0x254c79 = $('#planpremium').val(), _0x189f10 = $('#planapi').val(), _0x5109e7 = $('#planprivate').val(), _0xf3feee = $('#plancustom').val(), _0x263947 = $('#planprio').val();
    $.ajax({
        url: 'rest/plans/create',
        type: 'post',
        data: {
            name: _0x3129c7,
            price: _0x2f39e4,
            length: _0x5b0be2,
            lengthtype: _0x1a9b1b,
            pagelength: _0x4a5987,
            concs: _0x382db8,
            time: _0x359441,
            premium: _0x254c79,
            api: _0x189f10,
            'private': _0x5109e7,
            custom: _0xf3feee,
            priority: _0x263947
        },
        cache: false,
        success: function (_0x33df57) {
            const _0x477f6d = JSON.parse(_0x33df57);
            var _0x245040 = _0x477f6d.status, _0x4f9dd5 = _0x477f6d.message;
            if (_0x245040 == 'error') {
                Toastify({
                    text: _0x4f9dd5,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x4f9dd5,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                PlanList();
            }
        }
    });
}
function IncreaseExpModal() {
    $('#expire-modal').modal('show');
}
function IncreaseExp() {
    var _0x2f34bc = $('#explength').val(), _0x1c44fc = $('#expunit').val();
    $.ajax({
        url: 'rest/plans/increase',
        type: 'post',
        data: {
            length: _0x2f34bc,
            unit: _0x1c44fc
        },
        cache: false,
        success: function (_0x481207) {
            const _0x2ccdf2 = JSON.parse(_0x481207);
            var _0x5f5922 = _0x2ccdf2.status, _0x319647 = _0x2ccdf2.message;
            if (_0x5f5922 == 'error') {
                Toastify({
                    text: _0x319647,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x319647,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#expire-modal').modal('hide');
            }
        }
    });
}
function CreateCoupon() {
    Swal.fire({
        title: 'Create Coupon',
        html: '<input type="text" id="code" class="swal2-input" placeholder="Code">\n           <input type="text" id="percent" class="swal2-input" placeholder="Percent">\n           <select id="type" class="swal2-input">\n             <option selected="">Select type</option>\n             <option value="plans">Plans</option>\n             <option value="addons">Addons</option>\n           </select>\n           <input type="datetime-local" id="date" class="swal2-input" placeholder="Date">\n    ',
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            const _0x5209d7 = Swal.getPopup().querySelector('#code').value, _0xf1ee27 = Swal.getPopup().querySelector('#percent').value, _0x3a0206 = Swal.getPopup().querySelector('#type').value, _0xe6f204 = Swal.getPopup().querySelector('#date').value;
            return {
                code: _0x5209d7,
                percent: _0xf1ee27,
                type: _0x3a0206,
                expire: _0xe6f204
            };
        }
    }).then(_0x158260 => {
        $.ajax({
            url: 'rest/coupons/create',
            type: 'post',
            data: {
                code: _0x158260.value.code,
                percent: _0x158260.value.percent,
                type: _0x158260.value.type,
                expire: _0x158260.value.expire
            },
            cache: false,
            success: function (_0x154ac4) {
                const _0x2e5711 = JSON.parse(_0x154ac4);
                var _0x324821 = _0x2e5711.status, _0x494f82 = _0x2e5711.message;
                if (_0x324821 == 'error') {
                    Toastify({
                        text: _0x494f82,
                        duration: 1500,
                        backgroundColor: 'red'
                    }).showToast();
                } else {
                    Toastify({
                        text: _0x494f82,
                        duration: 1500,
                        backgroundColor: 'green'
                    }).showToast();
                }
            }
        });
    });
}

function PowerProofsList() {
    $('#powerproofs-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No power proofs' },
        ajax: {
            url: 'rest/powerproofs/get',
            type: 'POST'
        },
        bDestroy: true,
        columns: [
            { data: 'title' },
            { data: 'action' }
        ]
    });
}

function NewsList() {
    $('#news-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No news' },
        ajax: {
            url: 'rest/news/get',
            type: 'POST'
        },
        columnDefs: [
            {
                name: 'title',
                targets: 0
            },
            {
                name: 'icon',
                targets: 1
            },
            {
                name: 'created_at',
                targets: 2
            },
            {
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3
                ]
            }
        ],
        bDestroy: true,
        columns: [
            { data: 'title' },
            { data: 'icon' },
            { data: 'created_at' },
            { data: 'action' }
        ]
    });
}
function AddPowerProof() {
    $.ajax({
        url: 'rest/powerproofs/create',
        type: 'post',
        data: {
            prooftitle: $("#prooftitle").val(),
            proofurl: $("#proofurl").val(),
            proofmethod: $("#proofmethod").val(),
            proofconcs: $("#proofconcs").val(),
        },
        cache: false,
        success: function (_0x4bf837) {
            const _0x4bc6cf = JSON.parse(_0x4bf837);
            var _0x174dee = _0x4bc6cf.status, _0x38c93a = _0x4bc6cf.message;
            if (_0x174dee == 'error') {
                Toastify({
                    text: _0x38c93a,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x38c93a,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                PowerProofsList();
            }
        }
    });
}

function AddNews() {
    var _0x23c48a = $('#newstitle').val(), _0x4c2bd3 = $('#newsdesc').val(), _0x586ac9 = $('#newsicon').val();
    $.ajax({
        url: 'rest/news/create',
        type: 'post',
        data: {
            title: _0x23c48a,
            desc: _0x4c2bd3,
            icon: _0x586ac9
        },
        cache: false,
        success: function (_0x4bf837) {
            const _0x4bc6cf = JSON.parse(_0x4bf837);
            var _0x174dee = _0x4bc6cf.status, _0x38c93a = _0x4bc6cf.message;
            if (_0x174dee == 'error') {
                Toastify({
                    text: _0x38c93a,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x38c93a,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                NewsList();
            }
        }
    });
}

function sssssssssssssssssssssssssssss() {
    Toastify({
        text: "test",
        duration: 1500,
        backgroundColor: 'green'
    }).showToast();
}

function DeletePowerProof(_0x36dff0) {
    var _0x1b9430 = _0x36dff0;
    $.ajax({
        url: 'rest/powerproofs/delete',
        type: 'post',
        data: { id: _0x1b9430 },
        cache: false,
        success: function (_0x355722) {
            const _0x3f9d88 = JSON.parse(_0x355722);
            var _0x18595c = _0x3f9d88.status, _0x4841a4 = _0x3f9d88.message;
            if (_0x18595c == 'error') {
                Toastify({
                    text: _0x4841a4,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x4841a4,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                PowerProofsList();
            }
        }
    });
}

function DeleteNews(_0x36dff0) {
    var _0x1b9430 = _0x36dff0;
    $.ajax({
        url: 'rest/news/delete',
        type: 'post',
        data: { newsid: _0x1b9430 },
        cache: false,
        success: function (_0x355722) {
            const _0x3f9d88 = JSON.parse(_0x355722);
            var _0x18595c = _0x3f9d88.status, _0x4841a4 = _0x3f9d88.message;
            if (_0x18595c == 'error') {
                Toastify({
                    text: _0x4841a4,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x4841a4,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                NewsList();
            }
        }
    });
}
function ServerList() {
    $('#servers-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No servers' },
        ajax: {
            url: 'rest/api/servers',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'apiurl' },
            { data: 'slots' },
            { data: 'type' },
            { data: 'network' },
            { data: 'methods' },
            { data: 'status' },
            { data: 'actions' }
        ]
    });
}
function EditServer(_0x20af72) {
    $('#editserver-modal').modal('show');
    var _0x2f82ef = _0x20af72;
    $.ajax({
        url: 'rest/api/serverinfo',
        type: 'post',
        data: { serverid: _0x2f82ef },
        cache: false,
        success: function (_0x43f4b7) {
            const _0x1acc2d = JSON.parse(_0x43f4b7);
            var _0x535883 = _0x1acc2d.status, _0x2d5852 = _0x1acc2d.message;
            if (_0x535883 == 'error') {
                Toastify({
                    text: _0x2d5852,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                $('#s-name').val(_0x1acc2d.name);
                $('#s-status').val(_0x1acc2d.status);
                $('#s-slots').val(_0x1acc2d.slots);
                $('#s-premium').val(_0x1acc2d.premium);
                $('#s-type').val(_0x1acc2d.type);
                $('#s-apiurl').val(_0x1acc2d.apiurl);
                $('#s-methods').val(_0x1acc2d.methods);
                $('#saveserver-btn').attr('onclick', 'SaveServer(' + _0x2f82ef + ')');
            }
        }
    });
}
function SaveServer(_0x533b8f) {
    var _0x5a5e02 = _0x533b8f, _0x4a5d81 = $('#s-name').val(), _0xb5bfd8 = $('#s-status').val(), _0x731458 = $('#s-slots').val(), _0x325dba = $('#s-premium').val(), _0xa0b66a = $('#s-type').val(), _0x362a19 = $('#s-apiurl').val(), _0x912685 = $('#s-methods').val();
    $.ajax({
        url: 'rest/api/serverupdate',
        type: 'post',
        data: {
            serverid: _0x5a5e02,
            name: _0x4a5d81,
            status: _0xb5bfd8,
            slots: _0x731458,
            premium: _0x325dba,
            type: _0xa0b66a,
            apiurl: _0x362a19,
            methods: _0x912685
        },
        cache: false,
        success: function (_0x3d71b3) {
            const _0x5e6f7d = JSON.parse(_0x3d71b3);
            var _0x50b17e = _0x5e6f7d.status, _0xb93d7d = _0x5e6f7d.message;
            if (_0x50b17e == 'error') {
                Toastify({
                    text: _0xb93d7d,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0xb93d7d,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#editserver-modal').modal('hide');
                ServerList();
            }
        }
    });
}
function AddServerModal() {
    $('#addserver-modal').modal('show');
}
function AddServer() {
    var _0x3f1427 = $('#s-servername').val(), _0x5db48e = $('#s-serverstatus').val(), _0x3e1a22 = $('#s-serverslots').val(), _0x23b8ba = $('#s-serverpremium').val(), _0x2d1171 = $('#s-servertype').val(), _0x35e1ea = $('#s-serverapiurl').val(), _0x11ec82 = $('#s-servermethods').val();
    $.ajax({
        url: 'rest/api/serveradd',
        type: 'post',
        data: {
            name: _0x3f1427,
            status: _0x5db48e,
            slots: _0x3e1a22,
            premium: _0x23b8ba,
            type: _0x2d1171,
            apiurl: _0x35e1ea,
            methods: _0x11ec82
        },
        cache: false,
        success: function (_0x2a3304) {
            const _0x47e021 = JSON.parse(_0x2a3304);
            var _0x5b8f73 = _0x47e021.status, _0x38fa7d = _0x47e021.message;
            if (_0x5b8f73 == 'error') {
                Toastify({
                    text: _0x38fa7d,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x38fa7d,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#addserver-modal').modal('hide');
                ServerList();
            }
        }
    });
}
function DeleteServer(_0x798895) {
    var _0x503df6 = _0x798895;
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(_0x223ab0 => {
        if (_0x223ab0.isConfirmed) {
            $.ajax({
                url: 'rest/api/serverdelete',
                type: 'post',
                data: { serverid: _0x503df6 },
                cache: false,
                success: function (_0x30a3df) {
                    const _0x222e13 = JSON.parse(_0x30a3df);
                    var _0x35f398 = _0x222e13.status, _0x1d0788 = _0x222e13.message;
                    if (_0x35f398 == 'error') {
                        Toastify({
                            text: _0x1d0788,
                            duration: 1500,
                            backgroundColor: 'red'
                        }).showToast();
                    } else {
                        Toastify({
                            text: _0x1d0788,
                            duration: 1500,
                            backgroundColor: 'green'
                        }).showToast();
                        ServerList();
                    }
                }
            });
        }
    });
}
function Methods() {
    $('#methods-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No methods' },
        ajax: {
            url: '/dash/rest/api/methods/',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'apiname' },
            { data: 'publicname' },
            { data: 'type' },
            { data: 'premium' },
            { data: 'timelimit' },
            { data: 'action' }
        ]
    });
}
function AddMethod() {
    Swal.fire({
        title: 'Add Method',
        html: '<input type="text" id="apiname" class="swal2-input" placeholder="API Name">\n           <input type="text" id="publicname" class="swal2-input" placeholder="Public Name - shows in stress panel">\n           <select id="type" class="swal2-input">\n             <option selected="">Select type</option>\n             <option value="FREEL4">FREEL4</option>\n             <option value="AMP">AMP</option>\n             <option value="UDP">UDP</option>\n             <option value="TCP">TCP</option>\n         <option value="GAME">GAME</option>\n           <option value="LAYER3">LAYER3</option>\n          <option value="SPECIAL">SPECIAL</option>\n             <option value="BOTNET">BOTNET</option>\n             <option value="FREEL7">FREEL7</option>\n             <option value="BASICL7">BASICL7</option>\n             <option value="PREMIUML7">PREMIUML7</option>\n           </select>\n           <select id="premium" class="swal2-input">\n             <option selected="">Is method premium?</option>\n             <option value="1">Yes</option>\n             <option value="0">No</option>\n           </select>\n           <input type="text" id="timelimit" class="swal2-input" placeholder="Timelimit - 0 for unlimited">\n    ',
        confirmButtonText: 'Add',
        focusConfirm: false,
        preConfirm: () => {
            const _0x39d626 = Swal.getPopup().querySelector('#apiname').value, _0x4ad0d7 = Swal.getPopup().querySelector('#publicname').value, _0x16fcee = Swal.getPopup().querySelector('#type').value, _0x24b821 = Swal.getPopup().querySelector('#premium').value, _0x3ffe98 = Swal.getPopup().querySelector('#timelimit').value;
            return {
                apiname: _0x39d626,
                publicname: _0x4ad0d7,
                type: _0x16fcee,
                premium: _0x24b821,
                timelimit: _0x3ffe98
            };
        }
    }).then(_0x5a2995 => {
        $.ajax({
            url: 'rest/api/methodadd',
            type: 'post',
            data: {
                apiname: _0x5a2995.value.apiname,
                publicname: _0x5a2995.value.publicname,
                type: _0x5a2995.value.type,
                premium: _0x5a2995.value.premium,
                timelimit: _0x5a2995.value.timelimit
            },
            cache: false,
            success: function (_0x5607da) {
                const _0x1ebf58 = JSON.parse(_0x5607da);
                var _0x3f13f5 = _0x1ebf58.status, _0x111ae0 = _0x1ebf58.message;
                if (_0x3f13f5 == 'error') {
                    Toastify({
                        text: _0x111ae0,
                        duration: 1500,
                        backgroundColor: 'red'
                    }).showToast();
                } else {
                    Toastify({
                        text: _0x111ae0,
                        duration: 1500,
                        backgroundColor: 'green'
                    }).showToast();
                    Methods();
                }
            }
        });
    });
}
function DeleteMethod(_0x592bb0) {
    var _0x3a2948 = _0x592bb0;
    $.ajax({
        url: 'rest/api/methoddelete',
        type: 'post',
        data: { methodid: _0x3a2948 },
        cache: false,
        success: function (_0x7e1b54) {
            const _0x3e75aa = JSON.parse(_0x7e1b54);
            var _0x2f07b8 = _0x3e75aa.status, _0x4233e1 = _0x3e75aa.message;
            if (_0x2f07b8 == 'error') {
                Toastify({
                    text: _0x4233e1,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x4233e1,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                Methods();
            }
        }
    });
}
function AllTickets() {
    $('#alltickets-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No tickets' },
        ajax: {
            url: 'rest/tickets/getall',
            type: 'POST'
        },
        columnDefs: [{
                className: 'text-center',
                targets: [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5
                ]
            }],
        bDestroy: true,
        columns: [
            { data: 'user' },
            { data: 'subject' },
            { data: 'status' },
            { data: 'priority' },
            { data: 'created' },
            { data: 'action' }
        ]
    });
}
function DeleteTicket(_0x8a546b) {
    var _0x351f5c = _0x8a546b;
    $.ajax({
        url: 'rest/tickets/delete',
        type: 'post',
        data: { ticketid: _0x351f5c },
        cache: false,
        success: function (_0x3321b5) {
            const _0x14c470 = JSON.parse(_0x3321b5);
            var _0x371fae = _0x14c470.status, _0x5c6db4 = _0x14c470.message;
            if (_0x371fae == 'error') {
                Toastify({
                    text: _0x5c6db4,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x5c6db4,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                AllTickets();
            }
        }
    });
}
function AllGiftCards() {
    $('#giftcards-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No giftcards' },
        ajax: {
            url: 'rest/giftcards/all',
            type: 'POST'
        },
        columnDefs: [
            {
                name: 'id',
                targets: 0
            },
            {
                name: 'plan',
                targets: 1
            },
            {
                name: 'code',
                targets: 2
            },
            {
                name: 'usedBy',
                targets: 3
            },
            {
                name: 'action',
                targets: 4
            },
            {
                className: 'text-center',
                targets: [
                    1,
                    2,
                    3,
                    4,
                ]
            }
        ],
        order: [[
                0,
                'asc'
            ]],
        bDestroy: true,
        columns: [
            { data: 'id' },
            { data: 'plan' },
            { data: 'code' },
            { data: 'usedBy' },
            { data: 'action' }
        ]
    });
}
function RemoveGiftCard(id) {
    var _0x351f5c = id;
    $.ajax({
        url: 'rest/giftcards/delete',
        type: 'post',
        data: { giftcardid: _0x351f5c },
        cache: false,
        success: function (_0x3321b5) {
            const _0x14c470 = JSON.parse(_0x3321b5);
            var _0x371fae = _0x14c470.status, _0x5c6db4 = _0x14c470.message;
            if (_0x371fae == 'error') {
                Toastify({
                    text: _0x5c6db4,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x5c6db4,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                AllGiftCards();
            }
        }
    });
}

function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
  
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
  
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Fallback: Copying text command was ' + msg);
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
    }
  
    document.body.removeChild(textArea);
}
function AddGiftCard() {
    if($("#giftcardPlan").val() == "not-selected") {
        Toastify({
            text: "You need to select plan!",
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    if(isNaN(parseInt($("#giftcardAmount").val()))) {
        Toastify({
            text: "Invalid amount!",
            duration: 1500,
            backgroundColor: 'red'
        }).showToast();
        return;
    }
    $.ajax({
        url: 'rest/giftcards/generate',
        type: 'post',
        data: {
            amount: $("#giftcardAmount").val(),
            plan: $("#giftcardPlan").val(),
        },
        cache: false,
        success: function (_0x33df57) {
            const _0x477f6d = JSON.parse(_0x33df57);
            var _0x245040 = _0x477f6d.status, _0x4f9dd5 = _0x477f6d.message, codes = _0x477f6d.codes;
            if (_0x245040 == 'error') {
                Toastify({
                    text: _0x4f9dd5,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x4f9dd5,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();

                let codesText = "";

                codes.forEach(code => {
                    codesText += code + "\n"
                })

                fallbackCopyTextToClipboard(codesText)
                AllGiftCards();
            }
        }
    });
}


function AllUsers() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        sPaginationType: 'full_numbers',
        serverMethod: 'post',
        responsive: true,
        language: { emptyTable: 'No users' },
        ajax: {
            url: 'rest/user/all',
            type: 'POST'
        },
        columnDefs: [
            {
                name: 'id',
                targets: 0
            },
            {
                name: 'username',
                targets: 1
            },
            {
                name: 'email',
                targets: 2
            },
            {
                name: 'plan',
                targets: 3
            },
            {
                name: 'lastlogin',
                targets: 4
            },
            {
                name: 'rank',
                targets: 5
            },
            {
                name: 'action',
                targets: 6
            },
            {
                className: 'text-center',
                targets: [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6
                ]
            }
        ],
        order: [[
                0,
                'asc'
            ]],
        bDestroy: true,
        columns: [
            { data: 'id' },
            { data: 'username' },
            { data: 'email' },
            { data: 'plan' },
            { data: 'lastlogin' },
            { data: 'rank' },
            { data: 'action' }
        ]
    });
}

function EditUser(_0x588b28) {
    var _0x21d144 = _0x588b28;
    $.ajax({
        url: 'rest/user/info',
        type: 'post',
        data: { userid: _0x21d144 },
        cache: false,
        success: function (_0xacb116) {
            const _0x5c945a = JSON.parse(_0xacb116);
            var _0xe5f244 = _0x5c945a.status, _0xae3ed = _0x5c945a.message;
            if (_0xe5f244 == 'error') {
                Toastify({
                    text: _0xae3ed,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                $('#edituser-modal').modal('show');
                $('#edituser-header').text('Edit User - ' + _0x5c945a.username);
                $('#m-username').val(_0x5c945a.username);
                $('#m-email').val(_0x5c945a.email);
                $('#m-rank').val(_0x5c945a.rank);
                $('#m-plan').val(_0x5c945a.plan);
                $('#m-cexpire').val(_0x5c945a.expire);
                $('#m-balance').val(_0x5c945a.balance);
                $('#m-premium').val(_0x5c945a.premium);
                $('#m-apiaccess').val(_0x5c945a.apiaccess);
                $('#m-apikey').val(_0x5c945a.apikey);
                $('#m-concs').val(_0x5c945a.aconcs);
                $('#m-time').val(_0x5c945a.atime);
                $('#m-blacklist').val(_0x5c945a.ablacklist);
                $('#m-secret').val(_0x5c945a.secret);
                $('#m-created').val(_0x5c945a.created);
                $('#m-lastlogin').val(_0x5c945a.lastlogin);
                $('#saveuser-btn').attr('onclick', 'SaveUser(' + _0x21d144 + ')');
                $('#resetuser-btn').attr('onclick', 'ResetUser(' + _0x21d144 + ')');
            }
        }
    });
}

function __copy(selector){
    var $temp = $("<div>");
    $("body").append($temp);
    $temp.attr("contenteditable", true)
         .html($(selector).html()).select()
         .on("focus", function() { document.execCommand('selectAll',false,null); })
         .focus();
    document.execCommand("copy");
    $temp.remove();
  }

function ResetUser(_0x47cc4a) {
    $.ajax({
        url: 'rest/user/resetpassword',
        type: 'post',
        data: {
            userid: _0x47cc4a,
        },
        cache: false,
        success: function (RESDATA) {
            const X_DATA = JSON.parse(RESDATA);

            if (X_DATA.status == 'error') {
                Toastify({
                    text: X_DATA.message,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                const newDataX = atob(X_DATA.newPass)
                navigator.clipboard.writeText(newDataX)

                Toastify({
                    text: X_DATA.message,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();

                $('#edituser-modal').modal('hide');
                AllUsers();
            }
        }
    });
}

function SaveUser(_0x47cc4a) {
    var _0x34acd2 = _0x47cc4a, _0x204775 = $('#m-username').val(), _0x1db07b = $('#m-email').val(), _0x4382aa = $('#m-rank').val(), _0x363212 = $('#m-plan').val(), _0x42f28d = $('#m-expire').val(), _0x5222d7 = $('#m-balance').val(), _0x40af56 = $('#m-premium').val(), _0x2875b4 = $('#m-apiaccess').val(), _0x3723fe = $('#m-apikey').val(), _0x50e4c6 = $('#m-aconcs').val(), _0x3698f7 = $('#m-atime').val(), _0x328542 = $('#m-blacklist').val(), _0x3882ff = $('#m-secret').val();
    $.ajax({
        url: 'rest/user/update',
        type: 'post',
        data: {
            userid: _0x34acd2,
            username: _0x204775,
            email: _0x1db07b,
            rank: _0x4382aa,
            plan: _0x363212,
            expire: _0x42f28d,
            balance: _0x5222d7,
            premium: _0x40af56,
            apiaccess: _0x2875b4,
            apikey: _0x3723fe,
            aconcs: _0x50e4c6,
            atime: _0x3698f7,
            blacklist: _0x328542,
            secret: _0x3882ff,
            addon_concs: $("#m-concs").val(),
            addon_time: $("#m-time").val(),
        },
        cache: false,
        success: function (_0x554ed4) {
            const _0x347b78 = JSON.parse(_0x554ed4);
            var _0xbae629 = _0x347b78.status, _0x1ade4d = _0x347b78.message;
            if (_0xbae629 == 'error') {
                Toastify({
                    text: _0x1ade4d,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x1ade4d,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                $('#edituser-modal').modal('hide');
                AllUsers();
            }
        }
    });
}
function BannedUsers() {
    $('#bannedusers-modal').modal('show');
    $('#banned-users').load('rest/user/banlist');
}

function BanUser(_0x1e1bde) {
    var _0x52c271 = _0x1e1bde;
    Swal.fire({
        title: 'Ban User',
        html: '<input type="text" id="reason" class="swal2-input" placeholder="Reason">\n           <input type="datetime-local" id="date" class="swal2-input" placeholder="Date">\n    ',
        confirmButtonText: 'Ban',
        focusConfirm: false,
        preConfirm: () => {
            const _0x4ef231 = Swal.getPopup().querySelector('#reason').value, _0x2aa135 = Swal.getPopup().querySelector('#date').value;
            return {
                reason: _0x4ef231,
                date: _0x2aa135
            };
        }
    }).then(_0x1fe18a => {
        $.ajax({
            url: 'rest/user/ban',
            type: 'post',
            data: {
                userid: _0x52c271,
                reason: _0x1fe18a.value.reason,
                expire: _0x1fe18a.value.date
            },
            cache: false,
            success: function (_0x5925a1) {
                const _0x34fa3e = JSON.parse(_0x5925a1);
                var _0x3a7409 = _0x34fa3e.status, _0x31347f = _0x34fa3e.message;
                if (_0x3a7409 == 'error') {
                    Toastify({
                        text: _0x31347f,
                        duration: 1500,
                        backgroundColor: 'red'
                    }).showToast();
                } else {
                    Toastify({
                        text: _0x31347f,
                        duration: 1500,
                        backgroundColor: 'green'
                    }).showToast();
                    BannedUsers();
                }
            }
        });
    });
}
function UnBanUser(_0x11c571) {
    var _0x1237a1 = _0x11c571;
    $.ajax({
        url: 'rest/user/unban',
        type: 'post',
        data: { banid: _0x1237a1 },
        cache: false,
        success: function (_0x51aa63) {
            const _0x48139c = JSON.parse(_0x51aa63);
            var _0x37c7a5 = _0x48139c.status, _0x1829c7 = _0x48139c.message;
            if (_0x37c7a5 == 'error') {
                Toastify({
                    text: _0x1829c7,
                    duration: 1500,
                    backgroundColor: 'red'
                }).showToast();
            } else {
                Toastify({
                    text: _0x1829c7,
                    duration: 1500,
                    backgroundColor: 'green'
                }).showToast();
                BannedUsers();
            }
        }
    });
}
function SignOut() {
    Toastify({
        text: 'You are successfully signed out. Redirecting..',
        duration: 1000,
        destination: '../login.php',
        newWindow: false,
        close: false,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
        style: { background: '#FFC700' }
    }).showToast();
    setTimeout(function () {
        window.location.replace('rest/admin/logout');
    }, 1000);
}