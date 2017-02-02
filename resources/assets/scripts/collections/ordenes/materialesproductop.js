/**
* Class MaterialesProductopList of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.MaterialesProductopList = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('ordenes.productos.materiales.index') );
        },
        model: app.Ordenp4Model,

        /**
        * Constructor Method
        */
        initialize : function(){

        }
   });

})(this, this.document);
