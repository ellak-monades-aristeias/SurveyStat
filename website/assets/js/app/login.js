var LGN = {

	conf: {
		appId : '730709513617496',
		scope : 'email'
	},


	loggedIn : false,
	userData : false,
	token : '',
	status: false,
	
	handleStatus : function (res) {

		this.status = res.status;
		
		switch (res.status)
		{
			case 'connected':
				//user authed the app...fetch data and register user if needed
				LGN.token = res.authResponse.accessToken;
				LGN.fetchUserData();
				break;
		
			default:
				
				break;
		}
	
	},
	
	fetchUserData : function() {
		if (LGN.userData === false) {
			FB.api('/me', function(user) {
				LGN.userData = user;
				$('#pEmail').val(user.email);
				//LGN.authenticate();
			});
		}
	},
	
	authenticate : function() {
		if (LGN.userData === false) {return false;}
		var data = jQuery.extend({}, LGN.userData);
		data.token = LGN.token;
		$.ajax({
			url: 'auth/fb',
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(d) {
				
				if (d.msg == 'user') {
					//approved on facebook, but already user, was auto logged in with email, reload
					window.location.reload();
				}
				
				if (d.msg == 'user created') {
					//approved on facebook, account created and logged in, reload
					window.location.reload();
				}
				
			}
		});
	}
}

addEvent(window, 'load', function() {

	//load jquery if needed
	if (! window.jQuery) {alert('Please load jQuery for login to work.'); return false;}

	
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/en_UK/all.js', function(){
		FB.init({
			appId: LGN.conf.appId,
			xfbml: true,
			status: true
		});
		
		//status change
		FB.Event.subscribe('auth.login', function(res) {
			LGN.handleStatus(res);
		});
		
		$('.fb-login').click(function() {
			FB.login(function(res) {FB.getLoginStatus(LGN.handleStatus);}, {scope: LGN.conf.scope});
		});
		
	});


});

function addEvent(el, evt, fn) {
	if (el.addEventListener) {
		el.addEventListener(evt, fn, false);
	}
	else if (el.attachEvent) {
		el.attachEvent('on'+evt, fn);
	} else {
		el['on'+evt] = fn;
	}
}
