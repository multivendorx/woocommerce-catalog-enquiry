jQuery(function($) {
    var cssText = '',
        forValue = '';

    var cssStuff = {};
    var cssValues = {};

    // if user set custom style for button //
    if (wcmp_catalog_btn.custom_cssStuff != '') {

        var userStyle = JSON.parse(wcmp_catalog_btn.custom_cssStuff);
        if ($.isEmptyObject(userStyle)) {
            cssStuff['buttonPadding'] = "5px 10px";
            cssStuff['backgroundBottom'] = "#65a9d7";
            cssStuff['backgroundTop'] = "#3e779d";
            cssStuff['borderColor'] = "#96d1f8";
            cssStuff['borderRadius'] = "8px";
            cssStuff['borderSize'] = "1px";
            cssStuff['textColor'] = "white";
            cssStuff['hoverColor'] = "#ccc";
            cssStuff['hoverBackground'] = "#28597a";
            cssStuff['activeBackground'] = "#1b435e";
            cssStuff['fontSize'] = "14px";
            cssStuff['fontStack'] = "";
        } else {
            $.each(userStyle, function(index, value) {
                cssStuff[index] = value;
            });
        }
    } else {
        cssStuff['buttonPadding'] = "5px 10px";
        cssStuff['backgroundBottom'] = "#65a9d7";
        cssStuff['backgroundTop'] = "#3e779d";
        cssStuff['borderColor'] = "#96d1f8";
        cssStuff['borderRadius'] = "8px";
        cssStuff['borderSize'] = "1px";
        cssStuff['textColor'] = "white";
        cssStuff['hoverColor'] = "#ccc";
        cssStuff['hoverBackground'] = "#28597a";
        cssStuff['activeBackground'] = "#1b435e";
        cssStuff['fontSize'] = "14px";
        cssStuff['fontStack'] = "";
    }
    
    // custom css generate fields values //
    if (wcmp_catalog_btn.custom_cssValues != '') {

        var userStyleValue = JSON.parse(wcmp_catalog_btn.custom_cssValues);
        if ($.isEmptyObject(userStyleValue)) {
            cssValues['sizer_value'] = 10;
            cssValues['fontsizer_value'] = 12;
            cssValues['borderrounder_value'] = 8;
            cssValues['bordersizer_value'] = 1;
            cssValues['backgroundBottom'] = "65a9d7";
            cssValues['backgroundTop'] = "3e779d";
            cssValues['borderColor'] = "96d1f8";
            cssValues['hoverBackground'] = "28597a";
            cssValues['textColor'] = "white";
            cssValues['hoverColor'] = "cccccc";
            cssValues['activeBackground'] = "1b435e";
        } else {
            $.each(userStyleValue, function(index, value) {
                cssValues[index] = value;
            });
        }
    } else {
        cssValues['sizer_value'] = 10;
        cssValues['fontsizer_value'] = 12;
        cssValues['borderrounder_value'] = 8;
        cssValues['bordersizer_value'] = 1;
        cssValues['backgroundBottom'] = "65a9d7";
        cssValues['backgroundTop'] = "3e779d";
        cssValues['borderColor'] = "96d1f8";
        cssValues['hoverBackground'] = "28597a";
        cssValues['textColor'] = "white";
        cssValues['hoverColor'] = "cccccc";
        cssValues['activeBackground'] = "1b435e";
    }
    
    
    function createCSSValue() {
        $("#custom_enquiry_buttons_cssValues").val(JSON.stringify(cssValues));
    }


    function createCSS() {
        cssText = "  .custom_enquiry_buttons_css_new { ";
        cssText += "     border: " + cssStuff['borderSize'] + " solid " + cssStuff['borderColor'] + " !important;";

        cssText += "     background: " + cssStuff['backgroundBottom'] + " !important;";
        cssText += "     background: -webkit-gradient(linear, left top, left bottom, from(" + cssStuff['backgroundTop'] + "), to(" + cssStuff['backgroundBottom'] + "))!important;";
        cssText += "     background: -moz-linear-gradient(top, " + cssStuff['backgroundTop'] + ", " + cssStuff['backgroundBottom'] + ")!important;";

        cssText += "     padding: " + cssStuff['buttonPadding'] + "!important;";

        cssText += "     -webkit-border-radius: " + cssStuff['borderRadius'] + " !important;";
        cssText += "     -moz-border-radius: " + cssStuff['borderRadius'] + " !important;";
        cssText += "     border-radius: " + cssStuff['borderRadius'] + " !important;";

        /*      cssText             += "     -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0 !important;";
                cssText             += "     -moz-box-shadow: rgba(0,0,0,1) 0 1px 0 !important;";
                cssText             += "     box-shadow: rgba(0,0,0,1) 0 1px 0 !important;";
                
                cssText             += "     text-shadow: rgba(0,0,0,.4) 0 1px 0 !important;";*/

        cssText += "     color: " + cssStuff['textColor'] + " !important;";
        cssText += "     font-size: " + cssStuff['fontSize'] + " !important;";
        cssText += "     font-family: " + cssStuff['fontStack'] + " !important;";
        cssText += "     text-decoration: none !important;";
        cssText += "     vertical-align: middle !important;";

        cssText += "  }";

        cssText += "  .custom_enquiry_buttons_css_new:hover { ";
        cssText += "     border-top-color: " + cssStuff['hoverBackground'] + " !important;";
        cssText += "     background: " + cssStuff['hoverBackground'] + " !important;";
        cssText += "     color: " + cssStuff['hoverColor'] + " !important;";
        cssText += "  }";

        cssText += "  .custom_enquiry_buttons_css_new:active { ";
        cssText += "     border-top-color: " + cssStuff['activeBackground'] + " !important;";
        cssText += "     background: " + cssStuff['activeBackground'] + " !important;";
        cssText += "  }";

        $("head").append("<style type='text/css'>" + cssText + "</style>");
        $("#custom_enquiry_buttons_css").val(cssText);
        $("#custom_enquiry_buttons_cssStuff").val(JSON.stringify(cssStuff));
    }

    function reCenterButton() {
        $(".custom_enquiry_buttons_css_new").position({
            "my": "center center",
            "at": "center center",
            "of": $(".button-box")
        });
    };

    //$("head").append("<style type='text/css'></style>"); //

    if (wcmp_catalog_btn.custom_css != '') {
        $("head").append("<style type='text/css'>" + wcmp_catalog_btn.custom_css + "</style>");
        $("#custom_enquiry_buttons_css").val(wcmp_catalog_btn.custom_css);
    } else {
        reCenterButton();
        createCSS();
        createCSSValue();
    }
    
    var sizeHandler = $('#sizer-handle');
    $('#Enquiry_Btn_wrapper #sizer').slider({
        value: cssValues['sizer_value'],
        min: 4,
        max: 40,
        create: function() {
            sizeHandler.text( $( this ).slider( "value" ) );
        },
        slide: function(event, ui) {
            cssStuff['buttonPadding'] = ui.value / 2 + "px " + ui.value + "px";
            reCenterButton();
            createCSS();
            createCSSValue();
            cssValues['sizer_value'] = ui.value;
            sizeHandler.text( ui.value );
        }
    });
    
    var fontsizeHandler = $('#font-sizer-handle');
    $('#Enquiry_Btn_wrapper #font-sizer').slider({
        value: cssValues['fontsizer_value'],
        min: 8,
        max: 24,
        create: function() {
            fontsizeHandler.text( $( this ).slider( "value" ) );
        },
        slide: function(event, ui) {
            cssStuff['fontSize'] = ui.value + "px";
            reCenterButton();
            createCSS();
            createCSSValue();
            cssValues['fontsizer_value'] = ui.value;
            fontsizeHandler.text( ui.value );
        }
    });
    
    var borderrounderHandler = $('#border-rounder-handle');
    $('#Enquiry_Btn_wrapper #border-rounder').slider({
        value: cssValues['borderrounder_value'],
        min: 0,
        max: 40,
        create: function() {
            borderrounderHandler.text( $( this ).slider( "value" ) );
        },
        slide: function(event, ui) {
            cssStuff['borderRadius'] = ui.value + "px";
            createCSS();
            createCSSValue();
            cssValues['borderrounder_value'] = ui.value;
            borderrounderHandler.text( ui.value );
        }
    });
    
    var bordersizeHandler = $('#border-sizer-handle');
    $('#Enquiry_Btn_wrapper #border-sizer').slider({
        value: cssValues['bordersizer_value'],
        min: 0,
        max: 12,
        create: function() {
            bordersizeHandler.text( $( this ).slider( "value" ) );
        },
        slide: function(event, ui) {
            cssStuff['borderSize'] = ui.value + "px";
            createCSS();
            createCSSValue();
            cssValues['bordersizer_value'] = ui.value;
            bordersizeHandler.text( ui.value );
        }
    });
    
    $('#Enquiry_Btn_wrapper .pickable').each(function(){
        var forValue = $(this).attr("rel");
        if (typeof cssValues[forValue] != "undefined") {
            console.log(cssValues[forValue]);
            $('.'+forValue).css('background-color','#'+cssValues[forValue]);
        }
        
    });

    $('#Enquiry_Btn_wrapper .pickable').ColorPicker({
        color: 'ff0000',
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).val(hex).css("background", "#" + hex);
            $(el).ColorPickerHide();

            forValue = $(el).attr("rel");

            cssStuff[forValue] = "#" + hex;
            cssValues[forValue] = hex;
            createCSS();
            createCSSValue();

        },
        onChange: function(hsb, hex, rgb, el) {

            $($(this).data('colorpicker').el).val(hex).css("background", "#" + hex);

            forValue = $($(this).data('colorpicker').el).attr("rel");

            cssStuff[forValue] = "#" + hex;
            cssValues[forValue] = hex;
            createCSS();
            createCSSValue();

        },
        onBeforeShow: function() {
            $(this).ColorPickerSetColor(this.value);
        }
    });

    $("#Enquiry_Btn_wrapper #fontSelector").change(function() {

        cssStuff['fontStack'] = $(this).val();
        cssValues['fontStack'] = $(this).val();
        createCSS();
        createCSSValue();

    });


});