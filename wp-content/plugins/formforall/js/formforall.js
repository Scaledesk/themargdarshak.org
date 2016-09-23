/**
 * Additionnal plugin for tinymce
 */
jQuery(document).ready(function($) {
    tinymce.create('tinymce.plugins.formforall', {
        init : function(ed, url) {
	    
	    // Triggered when button is clicked
	    ed.addCommand(
		'formforall_openpopup', 
		function() {
		    ajaxForm.open(ed, '', '');
		});

            // Register button
            ed.addButton('formforall', {title : 'FormForAll', cmd : 'formforall_openpopup', image: url + '/../images/logo-ffa.png' });
	    
	    // Triggered when click on the text
	    ed.onMouseUp.add(function(ed, e) {
		if (e.target.firstChild.nodeValue != null) {
		    if (e.target.firstChild.nodeValue.indexOf('[formforall formid=') > -1) {
			var nodevalue = e.target.firstChild.nodeValue;
			ed.selection.select(e.target);
			var currentform = nodevalue.split('formid="')[1].split('"')[0];
			var currentts = nodevalue.split('ts="')[1].split('"')[0];
			ajaxForm.open(ed, currentform, currentts);
		    }
		}
	    });
    
	    // Form to be displayed everytime the button is clicked
	    var form = jQuery('<div id="formforall-form">\
	    <div id="formforall-formcontent">\
		<table id="formforall-table" class="form-table">\
		    <tr>\
			    <th><label for="formforall-size">' + ed['settings']['formforall_trans_form'] + '</label></th>\
			    <td><select name="formid" id="formforall-formid">\
			    </select></td>\
		    </tr>\
		</table>\
		<p class="submit">\
		    <input type="button" id="formforall-submit" class="button-primary" value="' + ed['settings']['formforall_trans_addform'] + '" name="submit" />\
		</p>\
	    </div>\
	    <div id="formforall-error" class="error" style="padding: 5px; display: none"><a href="options-general.php?page=formforall-settings">' + ed['settings']['formforall_trans_errorparam'] + '</a></div>\
	    </div>');

	    var table = form.find('table');
	    form.appendTo('body').hide();

	    // Triggered when the submit button is clicked
	    form.find('#formforall-submit').click(function(){
		var ts = new Date().getTime();
		var div_code = '[formforall formid="' + table.find('#formforall-formid').val() + '" ts="' + ts + '"]';

		// inserts the shortcode into the active editor
		if (ajaxForm.currentselect != '') {
		    node = ed.selection.getNode();
		    var newElement = document.createTextNode("");
		    node.parentNode.replaceChild(newElement, node);
		}
		ed.execCommand('mceInsertContent', 0, div_code);

		// reinit and closes Thickbox
		ajaxForm.currentts = '';
		ajaxForm.currentselect = '';
		tb_remove();
	    });
        }
    });

    // Register the TinyMCE plugin
    tinymce.PluginManager.add('formforall', tinymce.plugins.formforall);
    
    // Class to manage the form
    var ajaxForm = {
	currentts : '',
	currentselect : '',
	
	// Opening the form
	open : function(ed, currentform, currentts) {
	    var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
	    W = W - 80;
	    H = H - 84;
	    tb_show('FormForAll', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=formforall-form' );

	    ajaxForm.currentselect = currentform;
	    ajaxForm.currentts = currentts;

	    var user_id = 'c10a7ebe-0c0b-4f88-b885-18b30f3f0e28';
	    user_id = ed['settings']['formforall_user_id'];
	    var api_key = 'uHiXqWzVsHvGD5bq5W1CygAS3VyLLk1G0UzG5EjQvy1V6BPnmDbywjnnsuJ1Fz815ITaPNnJUsULsr3ZLnpIuMDXVGJG6ZgbcQz4';
	    api_key = ed['settings']['formforall_api_key'];
	    ajaxForm.get(user_id, api_key);
	},
	
	// Request to get the infos
	get : function(user_id, api_key) {
	    $.ajax({
		type: 'GET',
		url: 'https://www.formforall.com/api/users/'+user_id+'/forms',
		beforeSend: function(xhr) {
		    xhr.setRequestHeader("Authorization", api_key);
		},
		async: true,
		cache: false,
		dataType : "text",
		data: '',
		success: function(jsonData) {
		    ajaxForm.refresh(jsonData);
		}, 
		error: function(XMLHttpRequest, textStatus, errorThrown) {
		    ajaxForm.showError();
		}
	    });
	},
	
	// Refresh the form if the request successed
	refresh : function(jsonData) {
	    $('#formforall-formcontent').show();
	    $('#formforall-error').hide();
	    $('select#formforall-formid').empty();
	    if (jsonData != undefined) {
		jsonData = $.parseJSON(jsonData);
		$(jsonData).each (function(){
		    var isAllowed = (this.allowedHost.length == 0);
		    if (!isAllowed) {
			for (var i = 0; i < this.allowedHost.length; i++) {
			    if (document.URL.indexOf(this.allowedHost[i]) > -1) isAllowed = true;
			}
		    }
		    
		    if (isAllowed) {
			if (ajaxForm.currentselect != '' && ajaxForm.currentselect == this.id) $('select#formforall-formid').append($('<option>', {value: this.id, text: this.title, selected: "selected"}));
			else $('select#formforall-formid').append($('<option>', {value: this.id, text: this.title}));
		    } 
		});
	    }
	},
	
	// Refresh to show the error if the request failed
	showError: function() {
	    $('#formforall-formcontent').hide();
	    $('#formforall-error').show();
	}
    };
});
