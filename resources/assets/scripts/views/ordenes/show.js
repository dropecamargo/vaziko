/**
* Class ShowOrdenesView
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.ShowOrdenesView = Backbone.View.extend({

        el: '#ordenes-show',

        /**
        * Constructor Method
        */
        initialize : function() {
            // Model exist
            if( this.model.id != undefined ) {

                this.productopOrdenList = new app.ProductopOrdenList();
                this.despachopOrdenList = new app.DespachopOrdenList();

                // Reference views
                this.referenceViews();
            }
        },

        /**
        * reference to views
        */
        referenceViews: function () {
            // Productos list
            this.productopOrdenListView = new app.ProductopOrdenListView( {
                collection: this.productopOrdenList,
                parameters: {
                    wrapper: this.$('#wrapper-productop-orden'),
                    dataFilter: {
                        'orden2_orden': this.model.get('id')
                    }
               }
            });

            // Despachos list
            this.despachopOrdenListView = new app.DespachopOrdenListView( {
                collection: this.despachopOrdenList,
                parameters: {
                    wrapper: this.$el,
                    dataFilter: {
                        'despachop1_orden': this.model.get('id')
                    }
               }
            });
        }
    });

})(jQuery, this, this.document);
