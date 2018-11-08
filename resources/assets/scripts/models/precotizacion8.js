/**
* Class PreCotizacion8Model extend of Backbone Model
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

app || (app = {});

(function (window, document, undefined){

    app.PreCotizacion8Model = Backbone.Model.extend({

        urlRoot: function () {
            return window.Misc.urlFull( Route.route('precotizaciones.productos.maquinas.index') );
        },
        idAttribute: 'id',
        defaults: {

        }
    });

}) (this, this.document);
