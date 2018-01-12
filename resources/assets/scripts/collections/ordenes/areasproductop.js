/**
* Class AreasProductopList of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.AreasProductopList = Backbone.Collection.extend({

        url: function() {
            return window.Misc.urlFull( Route.route('ordenes.productos.areas.index') );
        },
        model: app.Ordenp6Model,

        /**
        * Constructor Method
        */
        initialize : function(){
        },

        validar: function( data ) {
            var error = { success: false, message: '' };

            // Validate exist
            if( !_.isNull(data.orden6_areap) && !_.isUndefined(data.orden6_areap) && data.orden6_areap != ''){
                var modelExits = _.find(this.models, function(item) {
                    return item.get('orden6_areap') == data.orden6_areap;
                });
            }else{
                var modelExits = _.find(this.models, function(item) {
                    return item.get('orden6_nombre') == data.orden6_nombre;
                });
            }

            if(modelExits instanceof Backbone.Model ) {
                error.message = 'El area que intenta ingresar ya existe.'
                return error;
            }

            error.success = true;
            return error;
        },

        total: function() {
            var _this = this;

            return this.reduce(function(sum, model){

                var func =  _this.convertirMinutos( model );
                return sum + func * parseFloat(model.get('orden6_valor'));

            }, 0);
        },

        totalRow: function( ){
            var _this = this;

            _.each( this.models, function(item) {

                var func = _this.convertirMinutos( item );
                var total = func * parseFloat(item.get('orden6_valor'));
                item.set('total', Math.round( total ));

            });
        },

        convertirMinutos: function ( model ){
            var tiempo = model.get('orden6_tiempo').split(':'),
                horas = parseInt( tiempo[0] ),
                minutos = parseInt( tiempo[1] );

            // Regla de 3 para convertir min a horas
            var total = horas + (minutos / 60);

            return parseFloat( total );
        },


        totalize: function(  ) {
            var total = this.total();
            this.totalRow();
            return { 'total': Math.round( total ) }
        },
   });

})(this, this.document);
