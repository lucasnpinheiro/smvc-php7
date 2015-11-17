<?php
namespace Helpers;

class BootstrapUI
{

    public static function alertBox($message)
    {
        if ($message == '')
            return;
        return ' <div class="alert alert-danger"><p><i class="fa fa-exclamation-triangle"></i> ' . $message . '  </p></div>';
    }

    public static function dangerCallout($message = null)
    {
        if ($message == '')
            return;
        return ' <div class="alert alert-danger"><p>' . $message . '</p></div>';
    }

    public static function infoCallout($message = null)
    {
        if ($message == '')
            return;
        return ' <div class="alert alert-info"><p>' . $message . '</p></div>';
    }

    public static function warningCallout($message = null)
    {
        if ($message == '')
            return;
        return ' <div class="alert alert-warning"><p>' . $message . '</p></div>';
    }

    public static function validationSummary($validatorObject)
    {
        if (! is_object($validatorObject))
            return;
        
        if ($validatorObject->hasErrors()) {
            $validationErrors = $validatorObject->getAllErrors();
            
            return ' <div class="alert alert-danger"><p style="text-align:left"> &bull; &nbsp; ' . implode('<br /> &bull; &nbsp; ', $validationErrors) . '</p></div>';
        }
    }
    
    // ckeditor
    public static function ckeditorBasic($formElementName, $editorHeight = '200', $projectGUID = null)
    {
        // http://ckeditor.com/latest/samples/plugins/toolbar/toolbar.html
        return "<script>
		CKEDITOR.replace( \"" . $formElementName . "\" , {
                    height:" . $editorHeight . ",
                        
			// Define the toolbar groups as it is a more accessible solution.
			toolbar: [
                         { name: 'document', groups: [ 'mode' ], items: [ 'Maximize', 'Source'] },
                         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize','TextColor', 'BGColor'  ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',  ] },
                        { name: 'insert', items: [  'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', ] },
                        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },                        
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Subscript,Superscript,Anchor,Specialchar',
                        filebrowserBrowseUrl : '" . BASE_URL . "/assets/kcfinder/browse.php?opener=ckeditor&type=files&project=" . $projectGUID . "',
                        filebrowserImageBrowseUrl :'" . BASE_URL . "/assets/kcfinder/browse.php?opener=ckeditor&type=images&project=" . $projectGUID . "',
                        filebrowserUploadUrl : '" . BASE_URL . "/assets/kcfinder/upload.php?opener=ckeditor&type=files&project=" . $projectGUID . "',
                        filebrowserImageUploadUrl : '" . BASE_URL . "/assets/kcfinder/upload.php?opener=ckeditor&type=images&project=" . $projectGUID . "',
                 } );
	</script>";
    }

    public static function ckeditorSimple($formElementName, $imagesBrowserPath = null, $imagesUploadPath = null, $editorHeight = '200')
    {
        // http://ckeditor.com/latest/samples/plugins/toolbar/toolbar.html
        return "<script>
		CKEDITOR.replace( \"" . $formElementName . "\" , {
                    height:" . $editorHeight . ",

			// Define the toolbar groups as it is a more accessible solution.
			toolbar: [
                         { name: 'document', groups: [ 'mode' ], items: [ 'Maximize', 'Source'] },
                         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ] },
                        { name: 'styles', items: [ 'FontSize'  ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', '-', 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',    ] },
                        { name: 'insert', items: [  'Table', 'HorizontalRule', ] },
                        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },                        
                        
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Subscript,Superscript,Anchor,Specialchar'," . ($imagesBrowserPath != '' ? "filebrowserImageBrowseUrl : '" . $imagesBrowserPath . "'," . "\n" : "") . ($imagesUploadPath != '' ? "filebrowserImageUploadUrl : '" . $imagesUploadPath . "'," . "\n" : "") . " } );
	</script>";
    }

    public static function formTextbox($labelText, $fieldName, $defaultValue = '', $placeholderValue = '')
    {
        if ($labelText == null) {
            $string = '<div class="form-group row">
                   <p class="col-md-12">
                        <input type="text" class="form-control" placeholder="' . $placeholderValue . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" /></p>
                </div>';
        } else {
            $string = '<div class="form-group row">
                    <p class="col-md-3"><label for="' . $fieldName . '">' . $labelText . '</label></p>
                    <p class="col-md-8">
                        <input type="text" class="form-control" placeholder="' . $placeholderValue . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" /></p>
                </div>';
        }
        return $string;
    }

    public static function formSelect($labelText, $fieldName, $keyIndexedValuesArray = '', $selectedValue = '')
    {
        if ($labelText != '')
            $string = '<div class="form-group row">
                    <div class="col-md-3"><label for="' . $fieldName . '">' . $labelText . '</label></div>
                    <div class="col-md-8">';
        else
            $string = '<div class="form-group">';
        
        $string .= ' <select class="form-control" id="' . $fieldName . '" name="' . $fieldName . '">';
        
        foreach ($keyIndexedValuesArray as $key => $value) {
            if (is_array($value)) {
                $string .= '<optgroup label="' . $key . '">' . "\n";
                foreach ($value as $optionKey => $optionValue) {
                    $string .= '<option value="' . $optionKey . '">' . $optionKey . '</option>' . "\n";
                }
                $string .= '</optgroup>' . "\n";
            } else {
                $string .= '<option value="' . $key . '">' . $value . '</option>' . "\n";
            }
        }
        $string .= '</select>';
        $string .= ($labelText != '' ? '</div>' : '') . '</div>';
        return $string;
    }

    public static function formTextarea($labelText, $fieldName, $defaultValue = '', $placeholderValue = '', $rows = 3)
    {
        if ($labelText != '') {
            $string = '<div class="form-group row">
                        <div class="col-md-3"><label for="' . $fieldName . '">' . $labelText . '</label></div>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="' . $rows . '" id="' . $fieldName . '" name="' . $fieldName . '" placeholder="' . $placeholderValue . '">' . $defaultValue . '</textarea></div>
                    </div>';
        } else {
            $string = '<div class="form-group row">
                       <div class="col-md-12">
                            <textarea class="form-control" rows="' . $rows . '" id="' . $fieldName . '" name="' . $fieldName . '" placeholder="' . $placeholderValue . '">' . $defaultValue . '</textarea></div>
                    </div>';
        }
        return $string;
    }

    public static function formDateTextbox($labelText, $fieldName, $defaultValue = '')
    {
        if ($labelText != '')
            $string = '<div class="form-group row">
                <div class="col-md-3 col-sm-3"><label for="' . $fieldName . '">' . $labelText . '</label></div>
                <div class="col-md-8">
                    <div class="input-group ">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input type="text" class="form-control" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" placeholder="dd-MMM-yyyy">
                    </div>
                </div>
            </div>';
        else
            $string = '<div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" placeholder="dd-MMM-yyyy">
                    </div>';
        return $string;
    }

    public static function formTimeTextbox($labelText = '', $fieldName = '', $defaultValue = '')
    {
        if ($labelText != '') {
            $string = '<div class="form-group row">
                    <p class="col-md-3"><label for="' . $fieldName . '">' . $labelText . '</label></p>
                    <p class="col-md-8">
                        <input type="text" class="form-control" placeholder="09:30 AM" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" /></p>
                </div>';
        } else {
            $string = '<div class="form-group">
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" class="form-control" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $defaultValue . '" placeholder="hh:mm" >
                        <span class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </span>
                    </div>
                </div>';
        }
        return $string;
    }

    public static function formSubmit($textOnButton, $fieldName)
    {
        $string = '<div class="form-group row">
                    <div class="col-md-3"> </div>
                    <div class="col-md-8">
                        <input type="submit" class="form-control" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $textOnButton . '" /></div>
                </div>';
        return $string;
    }
}