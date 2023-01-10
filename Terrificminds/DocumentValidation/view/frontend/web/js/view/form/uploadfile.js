define([
    'ko',
    'uiComponent',
    'jquery',
    'underscore',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/url-builder',
], function (ko,Component,$, _,urlFormatter, modal,urlBuilder,) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Terrificminds_DocumentValidation/form/uploadfile'
        },
        attachmentList: ko.observableArray([]),
        initialize: function () {
            this._super();
            this.uploadingfilefolder = ko.observable(this.checkcategory());
            var documentConfigValue = this.ConfigValue();
            var itemsFromQuote = window.checkoutConfig.quoteItemData;
            var array = JSON.parse(documentConfigValue);
            this.allowedExtensions = array.DocumentExtention;
            this.maxFileSize = array.DocumentSize;
            this.invalidExtError = array.DocumentInvalidExt;
            this.invalidSizeError = array.DocumentInvalidSize;
            this.filename = ko.observable();
            this.attachments = window.checkoutConfig.attachments;
            // console.log(this.attachments);
            var attachmentData = this.getDocumentName();
            var documentName = JSON.parse(attachmentData);
            this.attachmentList(documentName);
          },
          getDocumentName:function(){
            var Documents = null;
            var result1 = true;
            var url = urlFormatter.build('terrificminds/Document/GetDocumentAdded');
            $.ajax({
                url: url,
                type: 'POST',
                async: false,
                success: function(response){
                    Documents = response; 
                },
            }).fail(
                function (response) {
                    result1 = false;
                }
            ).done(
                function(response) {
                    result1 = true;
                }
            );
            if(Documents != null){
                this.attachmentList(Documents); 
                return Documents;
            }
          },
            ConfigValue: function(){
            var Configuration = null;
            var result1 = true;
            var url = urlFormatter.build('terrificminds/Document/GetConfigValue');
            console.log(url);
            $.ajax({
                url: url,
                type: 'POST',
                async: false,
                success: function(response){
                    Configuration = response; 
                },
            }).fail(
                function (response) {
                    result1 = false;
                }
            ).done(
                function(response) {
                    result1 = true;
                }
            );
            if(Configuration != null){ 
                return Configuration;
            }
          },
        checkcategory: function(){
            var result = true;
            var fileuploader = ko.observable();
            var url = urlFormatter.build('terrificminds/Checkout/Checkcategory');
            var ajaxResult = null;
            $.ajax({
                url: url,
                type: 'POST',
                async: false,
                success: function(response){
                    ajaxResult = response; 
                    
                },
            }).fail(
                function (response) {
                    result = false;
                }
            ).done(
                function(response) {
                    result = true;
                }
            );
            if(ajaxResult != null){ 
                return ajaxResult["success"];
            }
          },
          selectFiles: function() {
                $('#order-attachment').trigger('click');
            },
          showRowLoader: function() {
            $('body').trigger('processStart');
        },
            hideRowLoader: function() {
           $('body').trigger('processStop');
        },
            dragEnter: function(data, event) {},

            dragOver: function(data, event) {},

            drop: function(data, event) {
            $('.order-attachment-drag-area').css("border", "2px dashed #1979c3");
            var droppedFiles = event.originalEvent.dataTransfer.files;
            for (var i = 0; i < droppedFiles.length; i++) {
                this.processingFile(droppedFiles[i]);
            }
        },
        fileUpload: function(data, e) {
    
            var file    = e.target.files;
            for (var i = 0; i < file.length; i++) {
                this.filename(file[i].name);
                 this.processingFile(file[i]);
            }
            if(this.filename() != null){ 
                return this.filename();
            }
         },
         processingFile: function(file) {
            var error = this.validateFile(file);
            if (error) {
                this.addError(error);
                this.filename(" ");
            } else {
                    var uniq = Math.random().toString(32).slice(2);
                    this.upload(file, uniq);
                }
            },
        addError: function(error) {
            var html = null;
            html = '<div class="sp-attachment-error danger"><strong class="close">X</strong>'+ error +'</div>';
            $('.attachment-container').before(html);
            $(".sp-attachment-error .close").on('click', function() {
                var el = $(this).closest("div");
                if (el.hasClass('sp-attachment-error')) {
                    $(el).slideUp('slow', function() {
                        $(this).remove();
                    });
                }
            });
        },
        validateFile: function(file) {
            if (!this.checkFileExtension(file)) {//if not true
                return this.invalidExtError;
            }
            if (!this.checkFileSize(file)) {
                return this.invalidSizeError;
            }

            return null;
        },
        checkFileExtension: function(file) {
            var fileExt = file.name.split(".").pop().toLowerCase();
            
            var allowedExt = this.allowedExtensions.split(",");
            if (-1 == $.inArray(fileExt, allowedExt)) {
                return false;
            }
            return true;
        },
        checkFileSize: function(file) {
            if ((file.size / 1024) > this.maxFileSize) {
                return false;
            }
            return true;
        },
        upload: function(file, pos) {
            var formAttach = new FormData(),
            self = this;

            this.showRowLoader();
            formAttach.append($('#order-attachment').attr("name"), file);
            if (window.FORM_KEY) {
                formAttach.append('form_key', window.FORM_KEY);
            }
            var url = urlFormatter.build('terrificminds/Document/Uploade');
            $.ajax({
                url: url,
                type: "POST",
                data: formAttach,
                success: function(data) {
                    var result = JSON.parse(data);
                    console.log(result);
                //     // self.attachments.push(result);
                //     // self.attachmentList(self.attachments);
                //     // if(result['attachment_count']){
                //     //     self.files = result['attachment_count'];
                //     // }
                    self.hideRowLoader();
                },
                // error: function(xhr, ajaxOptions, thrownError) {
                //     self.addError(thrownError);
                //     self.hideRowLoader();
                // },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });
});