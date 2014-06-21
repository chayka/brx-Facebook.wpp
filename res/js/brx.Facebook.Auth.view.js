(function($, _){
    _.declare('brx.Facebook.Auth', $.brx.View, {
        
        nlsNamespace: 'brx.Facebook.Auth',
        
        options:{
            fb: null
        },
        
        postCreate: function(){
            console.log('widget warm-up');
            this.getFB();
            $(document).on('logout', $.proxy(this.logout, this));
        },
        
        getFB: function(){
            if(!this.get('fb') && window.FB){
                // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
                // for any authentication related change, such as login, logout or session refresh. This means that
                // whenever someone who was previously logged out tries to log in again, the correct case below 
                // will be handled. 
                window.FB.Event.subscribe('auth.authResponseChange', $.proxy(this.fbStatusChanged, this));
                this.set('fb', window.FB);
                this.parseXFBML();
            }
            return this.get('fb');
        },
        
        parseXFBML: function(){
            if(window.FB){
                window.FB.XFBML.parse(this.el);
            }
        },
        
        logout: function(){
            if(this.getFB() && this.getFBuserId() && !this.get('fb_not_authorized')){
                $.wp.currentUser.set('meta.fb_user_id', null);
                this.getFB().logout();
            }
        },
        
        getFBuserId: function(){
            return $.wp.currentUser.get('meta.fb_user_id');
        },

        onFacebookLoginButtonClicked: function(event){
            event.preventDefault();
            var fb = this.getFB();
            if(fb){
                fb.getLoginStatus($.proxy(this.fbStatusChanged, this));
            }
        },
        
        fbStatusChanged: function(response) {
            // Here we specify what we do with the response anytime this event occurs. 
            if (response.status === 'connected') {
                // The response object is returned with a status field that lets the app know the current
                // login status of the person. In this case, we're handling the situation where they 
                // have logged in to the app.
                // testAPI();
                this.getFBuserId() !== response.authResponse.userID && this.onFBlogin(response);
            } else if (response.status === 'not_authorized') {
                // In this case, the person is logged into Facebook, but not into the app, so we call
                // FB.login() to prompt them to do so. 
                // In real-life usage, you wouldn't want to immediately prompt someone to login 
                // like this, for two reasons:
                // (1) JavaScript created popup windows are blocked by most browsers unless they 
                // result from direct interaction from people using the app (such as a mouse click)
                // (2) it is a bad experience to be continually prompted to login upon page load.
//                FB.login();
                this.set('fb_not_autorized', true);
                this.getFBuserId() && this.buttonLogoutClicked();
            } else {
                // In this case, the person is not logged into Facebook, so we call the login() 
                // function to prompt them to do so. Note that at this stage there is no indication
                // of whether they are logged into the app. If they aren't then they'll see the Login
                // dialog right after they log in to Facebook. 
                // The same caveats as above apply to the FB.login() call here.
                this.set('fb_not_autorized', true);
                this.getFBuserId() && this.buttonLogoutClicked();
//                FB.login();
            }
        },
        
        onFBlogin: function(FBResponse){
            console.dir({FBResponse: FBResponse});
//            this.getSpinner().show(this.nls('message_spinner_sign_in'));//'Выполняется выход...');
//            return; 
            this.ajax('/api/facebook/login', {
                data: FBResponse.authResponse,
                spinner: false,
                showMessage: false,
                errorMessage: this.nls('message_error_auth_failed'),
                success:$.proxy(function(data){
                    console.dir({'data': data});
//                    this.setMessage(this.nls('message_welcome'));//'Выход выполнен, до новых встреч!');
                    $.wp.currentUser.set(data.payload);
                    $(document).trigger('userChanged', data.payload);
                    $(document).trigger('Facebook.Auth.userLoggedIn');
//                    if(data.payload.id !== $.wp.currentUser.id){
//                        $.brx.utils.loadPage();
//                    }
                },this),
                complete: $.proxy(function(data){
//                    this.enableInputs();
//                    this.getSpinner().hide($.proxy(this.showMessage, this));
                },this)
            });

        }
        
        
    });
}(jQuery, _));


