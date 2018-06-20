/**
* Class ImpresionesProductopCotizacionList of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.ImpresionesProductopCotizacionList = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('cotizaciones.productos.impresiones.index') );
        },
        model: app.Cotizacion7Model,

        /**
        * Constructor Method
        */
        initialize : function(){

        },
   });

})(this, this.document);
