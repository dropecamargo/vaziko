/**
* Class AreasProductopOrdenListView  of Backbone Router
* @author KOI || @dropecamargo
* @link http://koi-ti.com
*/

//Global App Backbone
app || (app = {});

(function ($, window, document, undefined) {

    app.AreasProductopOrdenListView = Backbone.View.extend({

        el: '#browse-orden-producto-areas-list',
        events: {
            'click .item-producto-areap-orden-remove': 'removeOne',
            'click .item-producto-areap-orden-edit': 'editOne',
            'click .item-producto-areap-orden-success': 'successEdit'
        },
        parameters: {
        	wrapper: null,
            dataFilter: {}
        },

        /**
        * Constructor Method
        */
        initialize : function(opts){
            // extends parameters
            if( opts !== undefined && _.isObject(opts.parameters) )
                this.parameters = $.extend({},this.parameters, opts.parameters);

            // References
            this.$total = this.$('#total');

            // Events Listeners
            this.listenTo( this.collection, 'add', this.addOne );
            this.listenTo( this.collection, 'reset', this.addAll );
            this.listenTo( this.collection, 'store', this.storeOne );
            this.listenTo( this.collection, 'request', this.loadSpinner);
            this.listenTo( this.collection, 'sync', this.responseServer);

            if (this.parameters.dataFilter.orden2)
                this.collection.fetch({ data: this.parameters.dataFilter, reset: true });
        },

        /**
        * Render view contact by model
        * @param Object ordenp6Model Model instance
        */
        addOne: function (ordenp6Model) {
            var view = new app.AreasProductopItemOrdenView({
                model: ordenp6Model,
                parameters: {
                    edit: this.parameters.edit
                }
            });
            ordenp6Model.view = view;
            this.$el.append( view.render().el );
        },

        /**
        * Render all view Marketplace of the collection
        */
        addAll: function () {
            this.$el.find('tbody').html('');
            this.collection.forEach( this.addOne, this );

            // Totaliza
            this.totalize();
        },

        /**
        * store
        * @param form element
        */
        storeOne: function (data, form) {
            var _this = this;

            // Validar carrito temporal
            var valid = this.collection.validar( data );
            if(!valid.success) {
                alertify.error(valid.message);
                return;
            }

            // Set Spinner
            window.Misc.setSpinner( this.parameters.wrapper );

            // Add model in collection
            var ordenp6Model = new app.Ordenp6Model();
            ordenp6Model.save(data, {
                success : function(model, resp) {
                    if(!_.isUndefined(resp.success)) {
                        window.Misc.removeSpinner( _this.parameters.wrapper );

                        var text = resp.success ? '' : resp.errors;
                        if( _.isObject( resp.errors ) ) {
                            text = window.Misc.parseErrors(resp.errors);
                        }

                        if( !resp.success ) {
                            alertify.error(text);
                            return;
                        }

                        // Add model in collection
                        window.Misc.clearForm(form);
                        _this.collection.add(model);
                        _this.totalize();
                    }
                },
                error : function(model, error) {
                    window.Misc.removeSpinner( _this.parameters.wrapper );
                    alertify.error(error.statusText)
                }
            });
        },

        /**
        * Event remove item
        */
        removeOne: function (e) {
            e.preventDefault();

            var resource = $(e.currentTarget).attr("data-resource"),
                model = this.collection.get(resource),
                _this = this;

            if ( model instanceof Backbone.Model ) {
                var cancelConfirm = new window.app.ConfirmWindow({
                    parameters: {
                        dataFilter: { orden6_nombre: model.get('orden6_nombre'), orden6_areap: model.get('areap_nombre')},
                        template: _.template( ($('#orden-delete-areap-confirm-tpl').html() || '') ),
                        titleConfirm: 'Eliminar área de producción',
                        onConfirm: function () {
                            model.view.remove();
                            _this.collection.remove(model);
                            _this.totalize();
                        }
                    }
                });

                cancelConfirm.render();
            }
        },

        /**
        * Event edit item
        */
        editOne: function(e){
            e.preventDefault();

            var resource = $(e.currentTarget).attr("data-resource"),
                model = this.collection.get(resource);

            if ( model instanceof Backbone.Model ) {
                var view = new app.AreasProductopItemOrdenView({
                    model: model,
                    parameters: {
                        action: 'edit',
                    }
                });
                model.view.$el.replaceWith( view.render().el );
            }
        },

        /**
        * Event success edit item
        */
        successEdit: function (e) {
            e.preventDefault();

            var resource = $(e.currentTarget).attr("data-resource"),
                model = this.collection.get(resource);

            if ( model instanceof Backbone.Model ) {
                var hour = this.$('#orden6_horas_' + model.get('id')).val();
                    minute = this.$('#orden6_minutos_' + model.get('id')).val();

                if (hour < 0 || _.isNaN(parseInt(hour)) ) {
                    alertify.error('El campo de horas no es valido.');
                    return;
                }

                if (minute < 0 || minute >= 60 || _.isNaN(parseInt(minute)) ) {
                    alertify.error('El campo de minutos no es valido.');
                    return;
                }

                var attributes = {};
                if (model.get('orden6_horas') != parseInt(hour))
                    attributes.orden6_horas = parseInt(hour);

                if (model.get('orden6_minutos') != parseInt(minute))
                    attributes.orden6_minutos = parseInt(minute)

                model.set(attributes, {silent: true});
                this.collection.trigger('reset');
            }
        },

        /**
        *Render totales the collection
        */
        totalize: function(){
            var data = this.collection.totalize();

            if(this.$total.length) {
                this.$total.empty().html( window.Misc.currency( data.total ) );

                this.model.trigger('totalize');
            }
        },

        /**
        * Load spinner on the request
        */
        loadSpinner: function ( target, xhr, opts ) {
            window.Misc.setSpinner( this.parameters.wrapper );
        },

        /**
        * response of the server
        */
        responseServer: function ( target, resp, opts ) {
            window.Misc.removeSpinner( this.parameters.wrapper );
        },
   });

})(jQuery, this, this.document);
