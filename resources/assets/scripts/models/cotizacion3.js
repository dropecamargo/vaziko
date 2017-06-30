/**
* Class Cotizacion3Model extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.Cotizacion3Model = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('cotizaciones.detallearea.index') );
        },
        idAttribute: 'id',
        defaults: {
        }
    });

})(this, this.document);
