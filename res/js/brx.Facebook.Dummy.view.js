(function($, _){
    _.declare('brx.Facebook.Dummy', $.brx.View, {
        
        nlsNamespace: 'brx.Facebook.Dummy',
        
        options:{
            name: 'John Smith'
        },
        
        postCreate: function(){
            console.log('widget warm-up');
        },
        
        buttonHelloClicked: function(){
            var name = this.get('myInput').val() || this.get('name');
            $.brx.Modals.alert('Hello '+name+'!');
        }
    });
}(jQuery, _));


