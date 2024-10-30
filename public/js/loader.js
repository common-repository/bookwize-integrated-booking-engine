function Loader() {
    var self = this;

    var visible = ko.observable(false);

    self.$loader = $('.loader');

    self.title = ko.observable('Please wait ...');

    self.message = ko.observable(new IBE.Models.Message());

    self.showError = ko.computed(function () {
        return (self.message().severity == 'critical');
    });

    self.clear = function () {
        self.message(new IBE.Models.Message());
        self.title('');
    };

    self.reload = function () {
        self.clear();
        window.location.reload();
    };

    self.visible = ko.computed({
        read: function () {
            return visible();
        },
        write: function (newValue) {

            // if error message exists, always show loader screen with error message
            if (self.message().id > 0) {
                visible(true);
            } else {
                visible(newValue);
            }

            if (visible()) {
                self.$loader.show();
            } else {
                self.$loader.hide();
            }
        }
    }).extend({ notify: 'always' });
}