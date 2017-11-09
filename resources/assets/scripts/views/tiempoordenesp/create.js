/**
* Class CreateTiempoOrdenpView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.CreateTiempoOrdenpView = Backbone.View.extend({

        el: '#tiempoordenp-create',
        template: _.template( ($('#add-tiempoordenp-tpl').html() || '') ),
        events: {
            // 'submit #form-create-empresa': 'onStore'
        },

        /**
        * Constructor Method
        */
        initialize : function() {
            // Attributes
            this.$wraperForm = this.$('#render-form-tiempoordenp');
            console.log(this.$('#render-form-tiempoordenp'));

            // Events
            this.listenTo( this.model, 'change', this.render );
            this.listenTo( this.model, 'sync', this.responseServer );
            this.listenTo( this.model, 'request', this.loadSpinner );
        },

        /*
        * Render View Element
        */
        render: function(){
            var attributes = this.model.toJSON();
            this.$wraperForm.html( this.template(attributes) );

            console.log(attributes);

            this.ready();
        },

        /**
        * fires libraries js
        */
        ready: function () {
            // to fire plugins
            if( typeof window.initComponent.initToUpper == 'function' )
                window.initComponent.initToUpper();

           	if( typeof window.initComponent.initInputMask == 'function' )
                window.initComponent.initInputMask();

            if( typeof window.initComponent.initSelect2 == 'function' )
                window.initComponent.initSelect2();

       		if( typeof window.initComponent.initICheck == 'function' )
                window.initComponent.initICheck();
        },

        /**
        * Event Create Forum Post
        */
        onStore: function (e) {

            if (!e.isDefaultPrevented()) {

                e.preventDefault();
                var data = window.Misc.formToJson( e.target );
                this.model.save( data, {patch: true} );
            }
        },

        /**
        * Load spinner on the request
        */
        loadSpinner: function (model, xhr, opts) {
            window.Misc.setSpinner( this.el );
        },

        /**
        * response of the server
        */
        responseServer: function ( model, resp, opts ) {
            window.Misc.removeSpinner( this.el );

            if(!_.isUndefined(resp.success)) {
	            // response success or error
	            var text = resp.success ? '' : resp.errors;
	            if( _.isObject( resp.errors ) ) {
	                text = window.Misc.parseErrors(resp.errors);
	            }

	            if( !resp.success ) {
	                alertify.error(text);
	                return;
	            }

                alertify.success('Empresa fue actualizada con éxito.');
	     	}
        }
    });

})(jQuery, this, this.document);
