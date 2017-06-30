/**
* Class DetalleCotizacion3List of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.DetalleCotizacion3List = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('cotizaciones.detallearea.index') );
        },
        model: app.Cotizacion3Model,

        /**
        * Constructor Method
        */
        initialize : function(){
        },
   });

})(this, this.document);
