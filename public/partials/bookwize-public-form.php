<div class="step1-content bookwize-integrated-form" data-bind="with:step1">
    <div class="row">
        <div data-bind="if: hotel()" class="col-sm-12">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="r" data-guests/>
                        <div data-bind="visible: hasMessage()">
                            <div data-bind="if: parseInt(message().id()) != 3001" class="message">
                                <p data-bind="css: 'alert alert-' + (message().severity() == 'error' ? 'danger' : message().severity())">
                                    <span data-bind="translate: message().text()"></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                </p>
                            </div>
                        </div>
                        <div data-bind="if: !bookingProcess().isValidStay">
                            <p class="alert alert-danger">
                                <span data-bind="text: IBE.Utils.translate('invalidCheckOutDate')"></span>
                            </p>
                        </div>

                        <div data-bind="visible: !isValidStay()">
                            <div class="alert alert-warning">
                                <span data-bind="text: IBE.Utils.translate('invalidDateRange')"></span>
                            </div>
                        </div>
                        <div class="row" data-bind="if: requestedRooms().length > 0">

                            <div class="col-xs-12 col-sm-2 nopadding">
                                <div class="form-group">
                                    <label for="CheckIn" data-bind="text: IBE.Utils.translate('checkIn')"></label>

                                    <div class="datepicker-holder">
                                        <input data-bind="datepicker: checkIn, bindMinDate: minCheckInDate(), lang: lang" readonly="readonly" id="CheckIn" class="form-control" required="required" data-arrow-offset="100">
                                        <i class="datepicker-trigger fa fa-calendar datepick-trigger"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 nopadding">
                                <div class="form-group">
                                    <label for="CheckOut" data-bind="text: IBE.Utils.translate('checkOut')"></label>

                                    <div class="datepicker-holder">
                                        <input data-bind="datepicker: checkOut, bindMinDate: minCheckOutDate(), lang: lang" readonly="readonly" id="CheckOut" class="form-control" required="required" data-arrow-offset="190">
                                        <i class="datepicker-trigger fa fa-calendar datepick-trigger"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 nopadding" data-bind="css: { 'col-sm-4' : requestedRooms()[0].guests.length > 1, 'col-sm-2' : requestedRooms()[0].guests.length === 1 }">
                                    <div data-bind="with: requestedRooms()[0]">
                                        <div class="request-room">
                                                <!-- ko foreach: guests -->
                                                <div class="col-xs-12 nopadding" data-bind="css: { 'col-sm-6' : $parent.guests.length === 2, 'col-sm-12' : $parent.guests.length === 1 , 'col-sm-4' : $parent.guests.length === 3, 'col-sm-3' : $parent.guests.length === 4}">
                                                    <div class="form-group">
                                                        <!-- ko if: $parent.guests.length > 1 -->
                                                        <label data-bind="text: IBE.Utils.translate('guestType.' + ageCategory())"></label>
                                                        <!-- /ko -->
                                                        <!-- ko if: $parent.guests.length == 1 -->
                                                        <label data-bind="text: IBE.Utils.translate('guests')"></label>
                                                        <!-- /ko -->
                                                        <span data-bind="if: label() != ''" class="guest-category-age">
                                                (<span data-bind="text: label"></span>)
                                            </span>
                                                        <select class="form-control" data-bind="options: options, value: pax"></select>
                                                    </div>
                                                </div>
                                                <!-- /ko -->

                                                <!-- ko if: guests.length > 3 -->
                                                <div class="clearfix"></div>
                                                <!-- /ko -->

                                                <div data-bind="visible: step1.requestedRooms().length > 1, css: { 'col-xs-2' : guests.length < 4 }"
                                                     class="text-center">
                                                    <div class="form-group">
                                                        <!-- ko if: guests.length < 4 -->
                                                        <label>&nbsp;</label>
                                                        <br class="hidden-xs" />
                                                        <!-- /ko -->
                                                        <button type="button" class="btn-remove-room no-outline"
                                                                data-bind="click: step1.removeRequestedRoom.bind($data), attr: {title : IBE.Utils.translate('removeRoom') }">
                                                            <i class="fa fa-times-circle"></i>
                                                            <span data-bind="translate: 'remove'"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>
                            <!--ko if: boardTypes().length > 1-->
                            <div class="col-xs-12 col-sm-2 nopadding">
                                    <div class="board-type">


                                            <div  data-bind="if: requestedRooms().length > 0">
                                                <label for="promoCode" data-bind="text: IBE.Utils.translate('selectMealPlan')"></label>
                                                    <select class="form-control" data-bind="options: boardTypes, value: boardType, optionsValue: 'boardType', optionsText: 'name'"></select>


                                                    <div data-bind="if: board">
                                                        <div data-bind="visible: board().description != ''">
                                                            <div data-bind="html: board().description"></div>
                                                        </div>
                                                    </div>

                                            </div>

                                    </div>
                            </div>
                            <!-- /ko-->

                            <div class="col-xs-12 col-sm-2 nopadding">
                                <div class="step1-submit-holder text-center">
                                    <button type="submit" class="btn btn-primary bookwize-integrated-form-button"
                                            data-bind="text: IBE.Utils.translate('book'), click: function(){step1.submit($data)}, enable: allowSubmit"></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
