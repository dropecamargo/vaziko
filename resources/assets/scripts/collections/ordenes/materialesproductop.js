/**
* Class MaterialesProductopList of Backbone Collection
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function (window, document, undefined) {

    app.MaterialesProductopOrdenList = Backbone.Collection.extend({

        url: function () {
            return window.Misc.urlFull(Route.route('ordenes.productos.materiales.index'));
        },
        model: app.Ordenp4Model,

        validar: function (data) {
            var error = {
                success: false,
                message: ''
            };

            var current_value = parseFloat(data.orden4_valor_unitario),
                previous_value = parseFloat(data.previo);

            if (current_value != previous_value) {
                var porcent_min = previous_value - (previous_value * 0.05),
                    porcent_max = previous_value + (previous_value * 0.05);

                if (current_value < porcent_min || current_value > porcent_max) {
                    error.message = 'El valor ingresado excede el límite permitido.';
                    return error;
                }
            }

            error.success = true;
            return error;
        },

        totalMaterialp: function () {
            _.each(this.models, function (model) {
                var total = parseFloat(model.get('orden4_valor_unitario')) * model.get('orden4_cantidad');
                model.set('orden4_valor_total', total);
            });
        },

        total: function () {
            return this.reduce(function (sum, model) {
                return sum + parseFloat(model.get('orden4_valor_unitario')) * model.get('orden4_cantidad');
            }, 0);
        },

        totalize: function () {
            var total = this.total();
                this.totalMaterialp();

            return {
                total: total
            }
        }
   });

})(this, this.document);
