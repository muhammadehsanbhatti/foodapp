<?php 
// echo '<pre>';print_r($notification);echo '</pre>';exit;

?>
<style>
    .not_read_noti{  
        background-color: #d3eafd;
    }
    .not_read_noti:hover{  
        background-color: #d3eafd !important;
    }
    .read_noti{
        
    }
    .notification-text{  
        color: #6d6d73 !important;
    }
    .header-navbar .navbar-container .dropdown-menu-media .media-list .media:hover{
        opacity: .8;
        background:none;
    }
</style>

<li class="nav-item dropdown dropdown-notification mr-25">
    <a class="nav-link" href="javascript:void(0);" data-toggle="dropdown">
        <i class="ficon" data-feather="bell"></i>
        <span class="badge badge-pill badge-danger badge-up unreadNotification" style="display: none">0</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
        <li class="dropdown-menu-header">
            <div class="dropdown-header d-flex">
                <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                <div class="badge badge-pill badge-light-primary new_notif"><span class="unreadNotification">0</span> New</div>
            </div>
        </li>
        <li class="scrollable-container media-list notification_empty_message" style="display: none;">
            <div class="dropdown-header d-flexe" >
                <p class="notification-title mb-0 mr-auto" style="font-weight: 400;">Don't have notifications</p>
            </div>
        </li>
        <li class="scrollable-container media-list notification_list">
           
        </li>
        <li class="dropdown-menu-footer">
        <a class="btn btn-primary btn-block readmore" style="display: none" href="javascript:void(0)">Load More</a>
        <a class="btn btn-primary btn-block readall" style="display: none" href="javascript:void(0)">Read All</a>
    </li>
    </ul>
</li>

@section('notificationScript')

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script> 

 
<script>
var notificationPage =1; 
var notificationReadAll = false; 
$(document).ready(function() {
    loadNotification(notificationPage);
    setInterval(function () { loadNotification(notificationPage); }, 10000);
});

$('.readmore').on('click', function () {
    notificationPage += 1;
    loadNotification(notificationPage, true);
});
$('.readall').on('click', function () { 
    notificationReadAll = true; 
    loadNotification(notificationPage, false, true);
});

function loadNotification(page, loader = false, readAllLoader = false) {
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if(loader){
        var btn_txt = $('.readmore').text();
        var btn_loader = '<i class="fa fa-spinner fa-pulse"></i>';
        $('.readmore').html(btn_txt +" "+ btn_loader);
    }
    if(readAllLoader){ 
        var btn_txt = $('.readall').text();
        var btn_loader = '<i class="fa fa-spinner fa-pulse"></i>';
        $('.readall').html(btn_txt +" "+ btn_loader);
    }

    $.ajax({
        type: "GET",
        url: "{{ URL::to('notification') }}",
        data: {
            "_token": "{{ csrf_token() }}",
            "page":page,
            "notificationReadAll":notificationReadAll,
            
        },
        dataType: 'json',
        success: function(data) {
            notificationReadAll = false;
            $(".notification_empty_message").hide();

            if(loader){ 
                $('.readmore').text(btn_txt);
            }
            if(readAllLoader){ 
                $('.readall').text(btn_txt);
            }

            if(page == 1){
                $('.notification_list').html(data.notificationListing);
            }else{
                $('.notification_list').append(data.notificationListing);
            }
            $('.unreadNotification').html(data.unreadNotification);

            if(data.notificationListing == '' && page == 1){
                $(".notification_empty_message").show();
            }
            if(data.notificationListing == ''){
              
                $('.readmore').hide();
                $('.dropdown-menu-footer').hide();
               
            }
            if(data.unreadNotification == 0) {
                $(".unreadNotification").hide();
                $('.readall').hide();
                $(".new_notif").hide();
                
            }else{
                $(".unreadNotification").show();
                $('.readall').show();
                $(".new_notif").show();
            }

            if(data.totlNotification == 10){
                $(".readmore").show();
            }else{
                $(".readmore").hide();
            }
        },
        error: function(e) {
        }
    });
}

    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyB9akTOvqLB9ZKzIPfgK1TIzZ7IRKts9Ik",
        authDomain: "laravelfcm-6a89e.firebaseapp.com",
        projectId: "laravelfcm-6a89e",
        storageBucket: "laravelfcm-6a89e.appspot.com",
        messagingSenderId: "923933224928",
        appId: "1:923933224928:web:bf123100f8f8d470158585",
        // databaseURL: "https://************.firebaseio.com",
        measurementId: "G-SF8X1DKGTB"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig); 
    //firebase.analytics();
    const messaging = firebase.messaging(); 
    messaging
        .requestPermission()
        .then(function () {
            //MsgElem.innerHTML = "Notification permission granted." 
            console.log("Notification permission granted.");

            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function (token) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // print the token on the HTML page 
         
            $.ajax({
                type: "POST",
                url: "{{ URL::to('notification_token') }}",
                
                data: {
                    "_token": "{{ csrf_token() }}",
                    "device_token": token
                },
                dataType: 'json',
                success: function(data) {
                },
                error: function(e) {
                }
            });
        })
        .catch(function (err) {
            console.log("Unable to get permission to notify.", err);
        });

    messaging.onMessage(function (payload) {
        console.log('onMessage');
        // console.log(payload);
        notificationPage = 1; 
        loadNotification(notificationPage);
        var notify;
        notify = new Notification(payload.notification.title, {
            body: payload.notification.body,
            icon: payload.notification.icon,
            tag: "Dummy"
        });
        // console.log(payload.notification);
        // console.log(payload.notification.title);
 
        // console.log(payload.data.device_ids); 
        // console.log('{{Session::getId()}}'); 
        // console.log("{{ asset('app-assets/audio_file.mp3') }}");
        var audio = new Audio("{{ asset('app-assets/audio_file.mp3') }}");
        // var audio = new Audio('/app-assets/audio_file.mp3');
        audio.play();
    });

    //firebase.initializeApp(config);
    var database = firebase.database().ref().child("/users/");

    database.on('value', function (snapshot) {
        console.log('value');
        renderUI(snapshot.val());
    });

    // On child added to db
    database.on('child_added', function (data) {
        console.log("Comming");
        if (Notification.permission !== 'default') {
            var notify;

            notify = new Notification('CodeWife - ' + data.val().username, {
                'body': data.val().message,
                'icon': 'bell.png',
                'tag': data.getKey()
            });
            notify.onclick = function () {
                alert(this.tag);
            }
        } else {
            alert('Please allow the notification first');
        }
    });

    self.addEventListener('notificationclick', function (event) {
        event.notification.close();
    });
    
</script>
@endsection